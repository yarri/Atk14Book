Zobrazení seznamu
=================

Uvažujte celkem představitelnou situaci, kdy je třeba na nějakém URL zobrazit seznam knížek
a nabídnou všechny možné související operace - tj. především zobrazení podrobného detailu knihy, založení nového záznamu,
editaci již hotového záznamu a smazání záznamu.

Předpokládejme jednoduchou tabulku pro ukládání knih

Include db/migrations/0005_books.sql

Předpokládejme kontroler s názvem _BooksController_.

Koneckonců podívejte se na adresu <http://www.atk14.net/en/books/>, tam to všechno uvidíte.

Seznam
------

Seznam umístíme do akce _index_.

Nejmenší možná varianta seznamu by mohla vypadat takto. Nenajdete v ní vyhledávání ani třídění podle sloupců.

	<?php
	// file: app/controllers/books_controller.php
	class BooksController extends ApplicationController{

		/**
		 * Provides the list of books.
		 */
		function index(){
			$this->page_title = "Listing books";

			$this->tpl_data["finder"] = Book::Finder(array(
				"order" => "UPPER(title)",
				"limit" => 10,
				"offset" => $this->params->getInt("offset"),
			));
		}

		// ... other actions...
	}

Běžně je však vyhledávání i třídění podle sloupců potřeba.

	<?php
	// file: app/controllers/books_controller.php
	class BooksController extends ApplicationController{

		/**
		 * Provides the list of books.
		 */
		function index(){
			$this->page_title = "Listing books";

			// initialize sorting
			$this->sorting->add("title",array("order_by" => "UPPER(title)"));
			$this->sorting->add("author",array(
				"ascending_ordering" => "UPPER(author), UPPER(title)",
				"descending_ordering" => "UPPER(author) DESC, UPPER(title) DESC"
			));
			$this->sorting->add("code");

			// validate input parameters
			if(!($d = $this->form->validate($this->params))){
				return;
			}

			// build conditions
			$conditions = array();
			$bind_ar = array();
			if($d["search"]){
				$conditions[] = "UPPER(title||author||code||shelfmark) LIKE UPPER(:search)";
				$bind_ar[":search"] = "%$d[search]%";
			}

			$this->tpl_data["finder"] = Book::Finder(array(
				"conditions" => $conditions,
				"bind_ar" => $bind_ar,
				"order" => $this->sorting->getOrder(),
				"limit" => 10,
				"offset" => $this->params->getInt("offset"),
			));
		}

		// ... other actions...
	}

Ve formuláři máme jedno políčko pro vyhledávání. Jednoduché jako facka.

Include app/forms/books/index_form.php

Vykresneí zajišťuje šablona index.tpl s jednou parciální šablonkou, ...

Include app/views/books/index.tpl

... která vykresluje jeden řádek v tabulce.

Include app/views/books/_book_item.tpl

V šabloně _index.tpl_ je použit helper _paginator_, který zajišťuje stránkování nalezených knih. Všechny potřebné údaje si paginator zjistí z proměnné šablonky _$finder_.

Při stránkování záznamů paginátor zachovává v URL všechny parametry a přidává parametr _offset_ (viz kontroler).
