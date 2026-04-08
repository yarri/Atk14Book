Lister — ordered list
======================

If you have, say, an articles table and you want to maintain a sorted list of authors for each article, use a _Lister_.

The following migration creates tables for articles, authors, and the relationship between them.

```sql
-- file db/migrations/0005_articles_authors.sql
CREATE SEQUENCE seq_articles;
CREATE TABLE articles(
		id INTEGER DEFAULT NEXTVAL('seq_articles') NOT NULL PRIMARY KEY,
		title VARCHAR(255),
		body TEXT,
		created_at DATE,
		updated_at DATE,
		CONSTRAINT fk_articles_images FOREIGN KEY (image_id) REFERENCES images
);

CREATE SEQUENCE seq_authors;
CREATE TABLE authors(
		id INTEGER DEFAULT NEXTVAL('seq_authors') NOT NULL PRIMARY KEY,
		name VARCHAR(255),
		email VARCHAR(255),
		created_at DATE,
		updated_at DATE
);

CREATE SEQUENCE seq_article_authors;
CREATE TABLE article_authors(
	id INTEGER DEFAULT NEXTVAL('seq_article_authors') NOT NULL PRIMARY KEY,
	article_id INTEGER NOT NULL,
	author_id INTEGER NOT NULL,
	rank INTEGER DEFAULT 999 NOT NULL,
	CONSTRAINT fk_article_authors_articles FOREIGN KEY (article_id) REFERENCES articles ON DELETE CASCADE,
	CONSTRAINT fk_article_authors_authors FOREIGN KEY (author_id) REFERENCES authors ON DELETE CASCADE
);
```

Now the _Article_ class:

```php
<?php
// file app/models/article.php
class Article extends TableRecord{

	function getAuthorsLister(){
		return $this->getLister("Authors");
	}

	function getAuthors(){
		$lister = $this->getAuthorsLister();
		return $lister->getRecords();
	}
}
```

And finally the _Author_ class — nothing special here:

```php
<?php
// file app/models/author.php
class Author extends TableRecord{ }
```

Now let's create an article and a few authors.

```php
<?php
$article = Article::CreateNewRecord(array(
	"title" => "First post",
	"body" => "Just testing...",
));

$bob = Author::CreateNewRecord(array("name" => "Bob"));
$bill = Author::CreateNewRecord(array("name" => "Bill"));
$norman = Author::CreateNewRecord(array("name" => "Norman"));
```

Now let's get to some real work.

```php
<?php
$lister = $article->getAuthorsLister();
print_r($lister->isEmpty()); // true
print_r($article->getAuthors()); // array()

$lister->append($bob);
$lister->append($norman);
$lister->prepend($bill);

print_r($article->getAuthors()); // array($bill,$bob,$norman)

// odstranime Boba
$lister->remove($bob);
print_r($lister->contains($bob)); // false
print_r($article->getAuthors()); // array($bill,$norman)

// zmena poradi
$lister->setRecordRank($norman,0);
print_r($article->getAuthors()); // array($norman,$bill)
$lister->setRecordRank(array($bill,$norman));
print_r($article->getAuthors()); // array($bill,$norman)
```

More information can be found in the [_TableRecord\_Lister_ class documentation](http://api.atk14.net/classes/TableRecord_Lister.html).

Enjoy Lister in peace!
