Modely
======

Framework ATK14 obsahuje vlastní jednoduchou ORM knihovnu _TableRecord_, kterou lze provozovat nad databázemi Postgresql nebo MySQL (ukázky v této knize jsou pro Postgresql). Navíc je k dispozici systém tzv. _migrací_, který zajišťuje automatickou aplikaci změn databázového schématu.

Uvažujme tabulku pro ukládání článků. Existuje migrace pro vytvoření tabulky _articles_. Migrace je očíslovaný patch, který je aplikován do databáze v určeném pořadí. 

[Include db/migrations/0004_articles.sql]

Spuštěním příkazu ```./scripts/migrate``` dojde k aplikaci čekajících migrací do aktuální databáze. O migracích bude pojednáno později.

Poté, co byla tabulka articles založena, vytvoříme modelovou třídu _Article_ jako dědice TableRecord.

    <?php
    // file: app/models/article.php
    class Article extends TableRecord {

    }

Existuje jmenná konvence v pojmenování tabulky, sekvence a modelové třídy, pokud je tato konvence dodržena, automaticky je třída Article propojena s tabulkou articles a sekvencí seq_articles. Stejně by byla třída HappyPerson ze souboru app/models/happy_person.php propojena s tabulkou happy_people a sekvencí seq_happy_people.

### Vytváření záznamu

    <?php
    $article = Article::CreateNewRecord([
      "title" => "Top ten songs of the week",
      "teaser" => "The ten most popular songs of the week based on...",
      "body" => "This week brings a big surprise...",
      "published_at" => "2017-06-01 00:00:00",
    ]);
    
    echo $article->getId(); // automatically assigned value by the sekvence, e.g. 123

    $article2 = Article::CreateNewRecord([
       "id" => 2233,
       "title" => "Another article",
    ]);

    echo $article2->getId(); // 2233

### Čtení hodnot z objektu

Základní metoda pro přečtení hodnoty z objektu je _getValue()_. Existuje i jednopísmenný alias _g()_.

    <?php
    echo $article->getValue("title"); // "Top ten songs of the week"
    echo $article2->g("title"); // "Another article"

Pomocí kouzel s metodou ___call()_ je zajištěno, že lze volat i neexistující metodu s CamelCase zápisem názvu požadovaného políčka.

    <?php
    echo $article->getTitle(); // "Top ten songs of the week"
    echo $article->getPublishedAt(); // "2017-06-01 00:00:00"


### Změna hodnot

Pro změnu jedné hodnoty je určena hodnota _setValue()_, pro změnu více hodnota _setValues()_. Jednopísmenný alias _s()_ zvláda obojí, proto se nejčastěji používá právě ten.

    <?php
    // single value assignment
    $article2->setValue("title","Another great article");

    // mass assignment
    $article2->setValues([
      "teaser" => "Great teaser of the great article",
      "body" => "..."
    ]);

    // using s() method

    $article2->s("title","Another great article");

    $article2->s([
      "teaser" => "Great teaser of the great article",
      "body" => "..."
    ]);

Za zmínku stojí, že během volání těchto metod dochází k automatické změně dat i v databázi. TableRecord tedy zajišťuje průběžnou persistenci dat, tím pádem neobsahuje metody jako _persist()_, saveToDb() a pod.

### Vyhledání záznamu

Základní metoda _GetInstanceById()_ vyhledá záznam v příslušné tabulce podle _ID_. Metoda přijímá jako parametr i pole.

    <?php
    $article = Article::GetInstanceById(123); // returns null if the article #123 doesn't exist
    $articles = Article::GetInstanceById([123,124]); // returns array of objects
    $articles = Article::GetInstanceById(["a" => 123,"b" => 124]); // returns associative array of objects


### Smazání záznamu

Voláním metody _destroy()_ je příslušný záznam odstraněn z databáze. 

