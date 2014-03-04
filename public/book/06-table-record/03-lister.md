Lister - tříděný seznam
=======================

Pakliže máte např. tabulku pro články a u každého článků chcete udržovat seznam autorů jako tříděný seznam, použijte _Lister_.

Následující migrace vytvoří tabulky pro články, autory a pro vazbu mezi nima.

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

Teď třída _Article_

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

No a konečně třída _Author_, kde ale nečekejte nic zvláštního

	<?php
	// file app/models/author.php
	class Author extends TableRecord{ }

Teď vytvořme nějaky článek a par autorů.

	<?php
	$article = Article::CreateNewRecord(array(
		"title" => "First post",
		"body" => "Just testing...",
	));

	$bob = Author::CreateNewRecord(array("name" => "Bob"));
	$bill = Author::CreateNewRecord(array("name" => "Bill"));
	$norman = Author::CreateNewRecord(array("name" => "Norman"));

Tak pojďme přátelé už konečně na nějakou rozumnou práci.

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

Další informace najdete [v dokumentaci třídy _TableRecord\_Lister_](http://api.atk14.net/classes/TableRecord_Lister.html)

Užívejte Lister v pokoji!
