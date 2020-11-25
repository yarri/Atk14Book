Migrace databázového schématu
=============================

ATK14 Framework obsahuje nástroj na postupné aplikování většinou malých změn (_patches_) do databázového schématu tak, aby databázové schéma bylo vždy v poslední požadované verzi. Pro tento mechanismus se zažil zkrácený název _migrace_.

Jednotlivé změny jsou ukládány do číslovaných souborů v adresáři db/migrations/. Číslování zajišťuje aplikaci změn ve správném pořadí. Jednou provedený změnový soubor již nebude ve stejné databáze znovu prováděn (pakliže si to nevynutíte).

Změnový soubor může být _SQL patch_.

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


Změnový soubor však může být i PHP třída &mdash; dědic _Atk14Migration_.

    <?php
    // file: db/migrations/0005_sample_article_migration.php
    class SampleArticleMigration extends Atk14Migration {
      function up(){

        Article::CreateNewRecord([
          "title" => "Happy Millenium",
          "teaser" => "Many wishes to the new millennium",
          "body" => "
    We wish you Happy Millenium!

    May all ATK14 developers are doing their job in peace.

    ATK14 Development Team",
          "author" => "Charlie Root",
          "published_at" => "2000-01-01",
        ]);

      }
    }

Aplikace čekajících změn bude provedena spuštěním příkazu ```./scripts/migrate```.

    $ ./scripts/migrate
    2017-06-06 08:55:56 migration[30153]: about to start migration 0004_articles.sql
    2017-06-06 08:55:56 migration[30153]: migration 0004_articles.sql has been successfully finished
    2017-06-06 08:55:56 migration[30153]: about to start migration 0005_sample_article_migration.php
    2017-06-06 08:55:56 migration[30153]: migration 0005_sample_article_migration.php has been successfully finished

Opakované spuštění tohoto příkazu již neprovede žádnou změnu.

    $ ./scripts/migrate 
    2017-06-06 08:57:50 migration[30203]: there is nothing to migrate
