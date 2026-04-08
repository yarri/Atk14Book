Modely
======

Framework ATK14 obsahuje vlastní jednoduchou ORM knihovnu _TableRecord_, kterou lze provozovat nad databázemi PostgreSQL nebo MySQL (ukázky v této knize jsou pro PostgreSQL). Navíc je zde k dispozici i systém tzv. _migrací_, který zajišťuje automatickou aplikaci změn databázového schématu.

Uvažujme tabulku pro ukládání článků. Existuje migrace pro vytvoření tabulky _articles_. Migrace je očíslovaný patch, který je aplikován do databáze v určeném pořadí.

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

Spuštěním příkazu `./scripts/migrate` dojde k aplikaci čekajících migrací do aktuální databáze. O migracích se dozvíš více v příslušné kapitole.

Poté, co byla tabulka articles založena, vytvoříme modelovou třídu _Article_. V praxi modely nedědí přímo z `TableRecord`, ale z `ApplicationModel` — třídy definované v `app/models/application_model.php`, která `TableRecord` rozšiřuje o automatické vyplňování polí jako `created_at`, `updated_at`, `created_by_user_id` a dalších. Třída `ApplicationModel` je vhodná pro rozšiřování vlastností a funkčností specifických pro modely ve tvém projektu.

```php
<?php
// file: app/models/article.php
class Article extends ApplicationModel {

}
```

Existuje konvence v pojmenování tabulky, sekvence a modelové třídy. Pokud je tato konvence dodržena, je zcela _automaticky_ třída Article propojena s tabulkou articles a sekvencí seq_articles. Stejně tak by byla třída HappyPerson ze souboru `app/models/happy_person.php` propojena s tabulkou `happy_people` a sekvencí `seq_happy_people`.

V legacy projektech, kde název tabulky a sekvence nelze odvodit od názvu třídy, je lze zadat přímo v konstruktoru.

```php
<?php
// file: app/models/newsletter_subscriber.php
class NewsletterSubscriber extends ApplicationModel {

  function __construct(){
    parent::__construct("tab_recipients",[
      "sequence_name" => "seq_recipients_id",
    ]);
  }
}
```

### Vytváření záznamu

```php
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
```

### Čtení hodnot z objektu

Základní metoda pro přečtení hodnoty z objektu je _getValue()_. Existuje i jednopísmenný alias _g()_.

```php
<?php
echo $article->getValue("title"); // "Top ten songs of the week"
echo $article2->g("title"); // "Another article"
```

Pomocí kouzel s metodou ___call()_ je zajištěno, že lze volat i neexistující metodu s CamelCase zápisem názvu požadovaného políčka.

```php
<?php
echo $article->getTitle(); // "Top ten songs of the week"
echo $article->getPublishedAt(); // "2017-06-01 00:00:00"
```

### Změna hodnot

Pro změnu jedné hodnoty je určena metoda _setValue()_, pro změnu více hodnot metoda _setValues()_. Jednopísmenný alias _s()_ zvládá obojí, proto se nejčastěji používá právě ten.

```php
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
```

Za zmínku stojí, že během volání těchto metod dochází k automatické změně dat i v databázi. TableRecord tedy zajišťuje průběžnou persistenci dat, a proto neobsahuje metody jako `persist()`, `save()`, `write()` apod.

### Vyhledání záznamu

Základní metoda _GetInstanceById()_ vyhledá záznam v příslušné tabulce podle _ID_. Metoda přijímá jako parametr i pole.

```php
<?php
$article = Article::GetInstanceById(123); // vrátí null, pokud článek #123 neexistuje
$articles = Article::GetInstanceById([123, 124]); // vrátí pole objektů
$articles = Article::GetInstanceById(["a" => 123, "b" => 124]); // vrátí asociativní pole objektů
```

K vyhledání jednoho záznamu podle něčeho jiného než ID (ale i podle ID) se používá metoda _FindFirst()_ (s aliasem _Find()_) nebo metody _FindBySomething()_.

```php
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
```

Metody `FindBySomething()` ve skutečnosti neexistují. Jejich volání je zachyceno a šikovně zpracováno pomocí ___callStatic()_. Název políčka se přidává v CamelCase zápisu přímo do názvu metody. Podívej se na příklady použití.

```php
<?php
$article = Article::FindById(123);
$article = Article::FindByPublishedAt("2017-06-01 00:00:00");
$article = Article::FindByTitle("Another great article", ["order_by" => "published_at DESC"]);
```

### Vyhledání více záznamů

Pro vyhledání více záznamů slouží metoda _FindAll()_ a analogicky i metody _FindAllBySomething()_.

```php
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
```

### Smazání záznamu

Voláním metody _destroy()_ je příslušný záznam odstraněn z databáze.

```php
<?php
$article->destroy();
```

Pokud tabulka obsahuje sloupec `deleted`, provede `destroy()` tzv. _soft delete_ — záznam zůstane v databázi, ale příznak `deleted` se nastaví na `true` a při dalších dotazech je automaticky filtrován. Fyzické smazání vynutíš předáním parametru `true`.

```php
<?php
$article->destroy(true); // fyzické smazání bez ohledu na sloupec deleted
```
