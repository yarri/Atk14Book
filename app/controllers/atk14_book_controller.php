<?php
require_once(dirname(__FILE__)."/base_book.php");

class Atk14BookController extends BaseBookController{

	function _before_filter(){
		global $ATK14_GLOBAL;

		$this->book_title = "Atk14 Book";
		$this->book_dir = $ATK14_GLOBAL->getPublicRoot()."book/";

		parent::_before_filter();
	}
}
