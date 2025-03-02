<?php
require_once(__DIR__ . "/md_book_base.php");

class MainController extends MdBookBaseController {

	var $book_dir = ATK14_DOCUMENT_ROOT . "/public/book/";
	var $_book_options = [
		"keep_html_tables_unmodified" => false,
		"table_class" => "",
	];

}
