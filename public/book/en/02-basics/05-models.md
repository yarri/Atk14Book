Models
======

The ATK14 framework includes its own simple ORM library called _TableRecord_, which can run on top of PostgreSQL or MySQL databases (examples in this book use PostgreSQL). In addition, a _migration_ system is available to automatically apply changes to the database schema.

Let's consider a table for storing articles. There is a migration to create the _articles_ table. A migration is a numbered patch that is applied to the database in a defined order.

```sql
-- file: db/migrations/0004_articles.sql
CREATE SEQUENCE seq_articles;
CREATE TABLE articles(
  id INT PRIMARY KEY DEFAULT NEXTVAL('seq_articles'),
  title VARCHAR(255),
  teaser TEXT,
  body TEXT,
  author VARCHAR(255),
  published_at TIMESTAMP NOT NULL DEFAULT NOW(),
  created_at TIMESTAMP NOT NULL DEFAULT NOW()
);
```

Running `./scripts/migrate` applies any pending migrations to the current database. You'll learn more about migrations in the dedicated chapter.

Once the articles table has been created, we define the _Article_ model class. In practice, models don't extend `TableRecord` directly but rather `ApplicationModel` — a class defined in `app/models/application_model.php` that extends `TableRecord` with automatic population of fields like `created_at`, `updated_at`, `created_by_user_id`, and others. `ApplicationModel` is the right place to add properties and behaviours specific to models in your project.

    <?php
    // file: app/models/article.php
    class Article extends ApplicationModel {

    }

There is a naming convention for the table, sequence, and model class. If this convention is followed, the Article class is _automatically_ linked to the articles table and the seq_articles sequence. Similarly, a HappyPerson class in `app/models/happy_person.php` would be linked to the `happy_people` table and the `seq_happy_people` sequence.

In legacy projects where the table and sequence names cannot be derived from the class name, you can set them directly in the constructor.

    <?php
    // file: app/models/newsletter_subscriber.php
    class NewsletterSubscriber extends ApplicationModel {

      function __construct(){
        parent::__construct("tab_recipients",[
          "sequence_name" => "seq_recipients_id",
        ]);
      }
    }

### Creating a record

    <?php
    $article = Article::CreateNewRecord([
      "title" => "Top ten songs of the week",
      "teaser" => "The ten most popular songs of the week based on...",
      "body" => "This week brings a big surprise...",
      "published_at" => "2017-06-01 00:00:00",
    ]);

    echo $article->getId(); // automaticky přiřazená hodnota ze sekvence, např. 123

    $article2 = Article::CreateNewRecord([
       "id" => 2233,
       "title" => "Another article",
    ]);

    echo $article2->getId(); // 2233

### Reading values from an object

The basic method for reading a value from an object is _getValue()_. There is also a single-letter alias _g()_.

    <?php
    echo $article->getValue("title"); // "Top ten songs of the week"
    echo $article2->g("title"); // "Another article"

Through some magic with the ___call()_ method, you can also call non-existent methods using the CamelCase name of the desired field.

    <?php
    echo $article->getTitle(); // "Top ten songs of the week"
    echo $article->getPublishedAt(); // "2017-06-01 00:00:00"

### Changing values

The _setValue()_ method changes a single value, while _setValues()_ changes multiple values. The single-letter alias _s()_ handles both, which is why it is most commonly used.

    <?php
    // změna jedné hodnoty
    $article2->setValue("title", "Another great article");

    // hromadná změna
    $article2->setValues([
      "teaser" => "Great teaser of the great article",
      "body" => "..."
    ]);

    // pomocí metody s()
    $article2->s("title", "Another great article");

    $article2->s([
      "teaser" => "Great teaser of the great article",
      "body" => "..."
    ]);

It is worth noting that calling these methods automatically updates the data in the database as well. TableRecord therefore provides continuous persistence and does not include methods like `persist()`, `save()`, `write()`, etc.

### Finding a record

The basic method _GetInstanceById()_ finds a record in the corresponding table by its _ID_. The method also accepts an array as a parameter.

    <?php
    $article = Article::GetInstanceById(123); // vrátí null, pokud článek #123 neexistuje
    $articles = Article::GetInstanceById([123, 124]); // vrátí pole objektů
    $articles = Article::GetInstanceById(["a" => 123, "b" => 124]); // vrátí asociativní pole objektů

To find a single record by something other than ID (as well as by ID), use the _FindFirst()_ method (with alias _Find()_) or the _FindBySomething()_ methods.

    <?php
    // jediný řetězcový parametr je vložen přímo do SQL dotazu
    $article = Article::FindFirst("title='Another great article'");

    // pokud za řetězcovým parametrem následuje pole, jsou to hodnoty,
    // které jsou bezpečně vloženy do SQL dotazu na jejich místa
    $article = Article::FindFirst("title=:title OR title=:title2", [
      ":title" => "Another great article",
      ":title2" => "Yet another great article"
    ]);

    // další pole jsou volby ($options)
    $article = Article::FindFirst("title=:title OR title=:title2", [
      ":title" => "Another great article",
      ":title2" => "Yet another great article"
    ], [
      "order_by" => "published_at DESC"
    ]);

    // sudý počet skalárních parametrů je převeden na dvojice pole = hodnota
    $article = Article::FindFirst("title", "Another great article");

    // bude vyhledán takový záznam, u kterého jsou splněny všechny podmínky
    $article = Article::FindFirst("title", "Another great article", "published_at", "2017-06-01 00:00:00");

    // pokud je za sudým počtem parametrů pole, jedná se o volby
    $article = Article::FindFirst("title", "Another great article", ["order_by" => "published_at DESC"]);

    // to jsou ale všechno syntaktické cukry pro snadný zápis podmínek;
    // generický způsob používání metody FindFirst() je čistě pomocí voleb
    $article = Article::FindFirst([
      "conditions" => [
        "title=:title OR title=:title2",
        "published_at>=:limit_date"
      ],
      "bind_ar" => [
        ":title" => "Another great article",
        ":title2" => "Yet another great article",
        ":limit_date" => "2017-01-01",
      ],
      "order_by" => "published_at DESC",
    ]);

The `FindBySomething()` methods don't actually exist. Their calls are intercepted and cleverly handled via ___callStatic()_. The field name is appended in CamelCase directly to the method name. See the examples below.

    <?php
    $article = Article::FindById(123);
    $article = Article::FindByPublishedAt("2017-06-01 00:00:00");
    $article = Article::FindByTitle("Another great article", ["order_by" => "published_at DESC"]);

### Finding multiple records

To find multiple records use the _FindAll()_ method and, analogously, the _FindAllBySomething()_ methods.

    <?php
    $articles = Article::FindAll([
      "conditions" => [
        "published_at<:now"
      ],
      "bind_ar" => [
        ":now" => date("Y-m-d H:i:s")
      ],
      "order_by" => "published_at DESC, id DESC",
      "limit" => 20,
      "offset" => 0,
    ]);

    $articles = Article::FindAll("published_at<:now", [":now" => date("Y-m-d H:i:s")], [
      "order_by" => "published_at DESC, id DESC",
      "limit" => 20,
      "offset" => 0,
    ]);

### Deleting a record

Calling the _destroy()_ method removes the corresponding record from the database.

    <?php
    $article->destroy();

If the table has a `deleted` column, `destroy()` performs a _soft delete_ — the record stays in the database but the `deleted` flag is set to `true` and it is automatically filtered out in subsequent queries. To force a physical delete, pass `true` as a parameter.

    <?php
    $article->destroy(true); // fyzické smazání bez ohledu na sloupec deleted
