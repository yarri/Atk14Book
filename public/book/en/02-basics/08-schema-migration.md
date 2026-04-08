Database schema migration
=========================

The ATK14 Framework includes a tool for incrementally applying mostly small changes (_patches_) to the database schema, keeping it always at the latest required version. This mechanism is commonly referred to as _migrations_.

Individual changes are stored in numbered files in the `db/migrations/` directory. The numbering ensures that changes are applied in the correct order. A change file that has already been applied will not be executed again in the same database (unless you force it).

A change file can be an _SQL patch_.

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


A change file can also be a PHP class — a subclass of _Atk14Migration_.

```php
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
```

Pending changes are applied by running `./scripts/migrate`.

```shell
$ ./scripts/migrate
2017-06-06 08:55:56 migration[30153]: about to start migration 0004_articles.sql
2017-06-06 08:55:56 migration[30153]: migration 0004_articles.sql has been successfully finished
2017-06-06 08:55:56 migration[30153]: about to start migration 0005_sample_article_migration.php
2017-06-06 08:55:56 migration[30153]: migration 0005_sample_article_migration.php has been successfully finished
```

Running the command again does nothing.

```shell
$ ./scripts/migrate 
2017-06-06 08:57:50 migration[30203]: there is nothing to migrate
```
