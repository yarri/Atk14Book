Hledání záznamů
===============

Předpokládejme tabulku _books_ a třídu _Book_

[Include db/migrations/0005_books.sql]

Třída bude prázdná. Pro naše příklady žádný zvláštní obsah nepotřebujeme.

	<?php
	//file: app/models/book.php
	class Book extends TableRecord{ }

### Základní hledání podle ID

Funkce _GetInstanceById()_ načte záznam z tabulky _books_ s _daným ID_.

	<?php
	$book = Book::GetInstanceById(123); // vrati objekt nebo null, kdyz zaznam 123 neexistuje
	$book = Book::GetInstanceById(null); // vrati null
	
	$book = Book::GetInstanceById(123);
	$book = Book::GetInstanceById($book); // zase vrati objekt vytvoreny ze zaznamu 123

Funkce přijímá rovněž _pole IDéček_, pak vrací pole objektů.

	<?php
	$books = Book::GetInstanceById(array(123,124,125)); // vrati pole objektu - nektere prvky pole mohou byt null
	$books = Book::GetInstanceById(array("a" => 123, "b" => 124, "c" => 125)); // klice ve vystupnim poli zustanou zachovany

	$books = Book::GetInstanceById(array(123,124,125),array("omit_nulls" => true)); // odstrani vsechny nullove prvky
	$books = Book::GetInstanceById($books); //

### Vyhledání jednoho záznamu podle podmínky

Vyhledání prvního záznamu, který splňuje podmínku:

	<?php
	// najde prvni knihu s autorem John Doe
	$author = "John Doe";
	$book = Book::FindFirst(array(
		"conditions" => aray(
			"author" => $author
		)
	));
	// nebo
	$book = Book::FindFirst(array(
		"conditions" => aray(
			"author=:author"
		),
		"bind_ar" => array(":author" => $author)
	));
	// nebo
	$book = Book::FindFirst(array(
		"conditions" => "author=:author",
		"bind_ar" => array(":author" => $author)
	));
	// nebo
	$book = Book::FindFirst(array(
		"conditions" => aray(
			"author" => ":author"
		),
		"bind_ar" => array(":author" => $author)
	));
	// nebo
	$book = Book::FindFirst("author",$author);
	// nebo
	$book = Book::FindFirst("author=:author",array(":author" => $author));
	// nebo (syntactic sugar)
	$book = Book::FindFirstByAuthor($author);

Všechny uvedené příklady povedou na stejný výsledek. Jisté úskalí zde spočívá v tom, že nevíme _jaký záznam přesně_ bude z databáze načten v případě, že v tabulce books je více záznamů s autorem Johnen Doe. Pomůžeme si třídením.

	<?php
	$author = "John Doe";
	$book = Book::FindFirst(array(
		"conditions" => aray(
			"author" => $author
		),
		"order_by" => "created DESC, id DESC"
	));
	// nebo
	$author = Book::FindFirstByAuthor($author,array("order_by" => "created DESC, id DESC"));

### Hledání více záznamů podle podmínky

	<?php
	$author = "John Doe";
	$books = Book::FindAll(array(
		"conditions" => array(
			"author" => $author
		),
		"order_by" => "created DESC, id DESC",
	));

	// komplexnejsi podminka
	$q = "Dick";
	$years = array(2003,2004,2005);
	$books = Book::FindAll(array(
		"conditions" => array(
			"author=:author",
			"title LIKE :q",
			"year_of_publication IN :years"
		),
		"bind_ar" => array(
			":author" => $author,
			":q" => "%$q%",
			":years" => $years
		),
		"order_by" => "created DESC, id DESC",
	));

	// strankovani
	$books = Book::FindAll(array(
		"conditions" => array(
			"author" => $author
		),
		"order_by" => "created DESC, id DESC",
		"limit" => 10,
		"offset" => 0,
	));

	// syntactic sugar
	$books = Books::FindAllByAuthor($author,array("order_by" => "created DESC, id DESC", "limit" => 10));

Teď už máte představu, jak se hledají TableRecord objekty.
