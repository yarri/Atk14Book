Finder — paginated listing
==========================

_Finder_ helps you handle paginated record listings with ease — it is essentially just an extension of the _FindAll()_ method.

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

Explore the sample [CRUD implementation at www.atk14.net](http://www.atk14.net/en/books/), where record searching is implemented using Finder. Notice the use of the _paginator_ helper in the [index.tpl](http://www.atk14.net/en/sources/detail/?file=app%2Fviews%2Fbooks%2Findex.tpl) template — _paginator_ automatically takes the pagination parameters from the finder, which is passed into the template in the [BooksController::index()](http://www.atk14.net/en/sources/detail/?file=app%2Fcontrollers%2Fbooks_controller.php) method.

More usage examples can be found in the [unit test](https://github.com/atk14/Atk14/blob/master/src/tablerecord/test/tc_finder.php).
