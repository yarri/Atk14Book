Finding records
===============

Let's assume a _books_ table and a _Book_ class.

```sql
-- file: db/migrations/0002_books.sql
CREATE SEQUENCE seq_books;
CREATE TABLE books(
  id INT PRIMARY KEY DEFAULT NEXTVAL('seq_books'),
  title VARCHAR(255),
  code VARCHAR(255),
  shelfmark VARCHAR(255),
  author VARCHAR(255),
  year_of_publication INT
);
```

The class will be empty — we don't need any special content for these examples.

	<?php
	//file: app/models/book.php
	class Book extends TableRecord{ }

### Basic lookup by ID

The _GetInstanceById()_ function loads a record from the _books_ table with the _given ID_.

	<?php
	$book = Book::GetInstanceById(123); // vrati objekt nebo null, kdyz zaznam 123 neexistuje
	$book = Book::GetInstanceById(null); // vrati null
	
	$book = Book::GetInstanceById(123);
	$book = Book::GetInstanceById($book); // zase vrati objekt vytvoreny ze zaznamu 123

The function also accepts an _array of IDs_, in which case it returns an array of objects.

	<?php
	$books = Book::GetInstanceById(array(123,124,125)); // vrati pole objektu - nektere prvky pole mohou byt null
	$books = Book::GetInstanceById(array("a" => 123, "b" => 124, "c" => 125)); // klice ve vystupnim poli zustanou zachovany

	$books = Book::GetInstanceById(array(123,124,125),array("omit_nulls" => true)); // odstrani vsechny nullove prvky
	$books = Book::GetInstanceById($books); //

### Finding a single record by condition

Finding the first record that matches a condition:

	<?php
	// najde prvni knihu s autorem John Doe
	$author = "John Doe";
	$book = Book::FindFirst(array(
		"conditions" => array(
			"author" => $author
		)
	));
	// nebo
	$book = Book::FindFirst(array(
		"conditions" => array(
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
		"conditions" => array(
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

All of the above examples produce the same result. One subtle issue is that we don't know _which exact record_ will be loaded from the database if there are multiple books by John Doe in the table. We can help with sorting.

	<?php
	$author = "John Doe";
	$book = Book::FindFirst(array(
		"conditions" => array(
			"author" => $author
		),
		"order_by" => "created DESC, id DESC"
	));
	// nebo
	$author = Book::FindFirstByAuthor($author,array("order_by" => "created DESC, id DESC"));

### Finding multiple records by condition

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

Now you have a good idea of how to search for TableRecord objects.
