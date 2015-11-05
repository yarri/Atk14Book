Finder - listovátko
===================

_Finder_ nám pomáhá vyrovnat se se ctí se stránkovaným seznamem záznamů a vlastně se jedná jen o rozšíření metody _FindAll()_.

	<?php
	// parametry od uzivatele
	$search = "Prague";
	$offset = 0;

	$finder = Book::Finder(array(
		"conditions" => array("title LIKE :q"),
		"bind_ar" => array(":q" => "%$search%"),
		"order_by" => "title, author",
		"limit" => 20,
		"offset" => $offset,
	));

	$finder->getTotalAmount(); // celkovy pocet nalezenych zaznamu
	$books = $finder->getRecords(); // zaznamy v okne urcenem pomoci offset a limit

Prozkoumejte ukázkovou [CRUD implementaci na www.atk14.net](http://www.atk14.net/en/books/), kdy je vyhledání záznamů realizováno pomocí Finderu. Všimněte si použití helperu _paginator_ v šabloně [index.tpl](http://www.atk14.net/en/sources/detail/?file=app%2Fviews%2Fbooks%2Findex.tpl) - _paginator_ automaticky uvažuje listovací parametry z finderu, který je do šablony vložen v metodě [BooksController::index()](http://www.atk14.net/en/sources/detail/?file=app%2Fcontrollers%2Fbooks_controller.php)

Další možnosti použití Finderu najdete např. v [unit testu](https://github.com/atk14/Atk14/blob/master/src/tablerecord/test/tc_finder.php)
