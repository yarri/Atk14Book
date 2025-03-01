Modely
======

Framework ATK14 obsahuje vlastní jednoduchou ORM knihovnu _TableRecord_, kterou lze provozovat nad databázemi Postgresql nebo MySQL (ukázky v této knize jsou pro Postgresql). Navíc je zde k dispozici i systém tzv. _migrací_, který zajišťuje automatickou aplikaci změn databázového schématu.

Uvažujme tabulku pro ukládání článků. Existuje migrace pro vytvoření tabulky _articles_. Migrace je očíslovaný patch, který je aplikován do databáze v určeném pořadí. 

[include file=db/migrations/0004_articles.sql]

Spuštěním příkazu ```./scripts/migrate``` dojde k aplikaci čekajících migrací do aktuální databáze. O migracích bude pojednáno později.

Poté, co byla tabulka articles založena, vytvoříme modelovou třídu _Article_ jako dědice TableRecord.

    <?php
    // file: app/models/article.php
    class Article extends TableRecord {

    }

Existuje konvence v pojmenování tabulky, sekvence a modelové třídy. Pokud je tato konvence dodržena, je zcela _automaticky_ třída Article propojena s tabulkou articles a sekvencí seq_articles. Stejně tak by byla třída HappyPerson ze souboru app/models/happy_person.php propojena s tabulkou happy_people a sekvencí seq_happy_people.

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

Za zmínku stojí, že během volání těchto metod dochází k automatické změně dat i v databázi. TableRecord tedy zajišťuje průběžnou persistenci dat, tím pádem neobsahuje metodu jako persist(), save(), write() a pod.

### Vyhledání záznamu

Základní metoda _GetInstanceById()_ vyhledá záznam v příslušné tabulce podle _ID_. Metoda přijímá jako parametr i pole.

    <?php
    $article = Article::GetInstanceById(123); // returns null if the article #123 doesn't exist
    $articles = Article::GetInstanceById([123,124]); // returns array of objects
    $articles = Article::GetInstanceById(["a" => 123,"b" => 124]); // returns associative array of objects

K vyhledání jenoho záznamu podle něčeho jiného než ID (ale i podle ID) se používá metoda _FindFirst()_ (s aliasem _Find()_) nebo metody _FindBySomething()_.

    <?php
    // jediny stringovy parametr je vlozen do SQL dotazu, kterym je zaznam vyhledan
    $article = Article::FindFirst("title='Another great article'");

    // pokud za stringovym parametrem nasleduje pole, jsou to hodnoty,
    // ktere jsou bezpecne vlozeny do SQL dotazu na sva mista
    $article = Article::FindFirst("title=:title OR title=:title2",[
      ":title" => "Another great article",
      ":title2" => "Yet another great article"
    ]);

    // dalsi pole jsou volby ($options)
    $article = Article::FindFirst("title=:title OR title=:title2",[
      ":title" => "Another great article",
      ":title2" => "Yet another great article"
    ],[
      "order_by" => "published_at DESC"
    ]);

    // sudy pocet skalarnich parametru je preveden na dvojice pole = hodnota
    $article = Article::FindFirst("title", "Another great article");

    // bude vyhledan takovy zaznam, u ktereho jsou splneny vsechny podminky
    $article = Article::FindFirst("title", "Another great article", "published_at","2017-06-01 00:00:00");

    // pokud je za sudym poctem parametru pole, jedna se o volby
    $article = Article::FindFirst("title", "Another great article",["order_by" => "published_at DESC"]);

    // to jsou ale vsechno syntakticke cukry pro snadny zapis podminek;
    // genericky zpusob pouzivani metody FindFirst() je ciste pomoci voleb
    $article = Another::FindFirst([
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

Metody FindBySomething() ve skutečnosti neexistují. Jejich volání je zachyceno a šikovně zpracováno pomocí ___callStatic()_. Název políčka se přidává v CamelCase zápisu přímo do názvu metody. Podívejte se na příklady použití.

    <?php
    $article = Article::FindById(123);
    $article = Article::FindByPublishedAt("2017-06-01 00:00:00");
    $article = Article::FindByTitle("Another great article",["order_by" => "published_at DESC"]);

### Vyhledání více záznamů

Pro vyhledání více záznamů souží metoda _FindAll()_ a analogicky i metody _FindAllBySomething()_.

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

    $articles = Article::FindAll("published_at<:now",[":now" => date("Y-m-d H:i:s")],[
      "order_by" => "published_at DESC, id DESC",
      "limit" => 20,
      "offset" => 0,
    ]);

### Smazání záznamu

Voláním metody _destroy()_ je příslušný záznam odstraněn z databáze.

    <?php
    $article->destroy();
