<?
/**
 * Chci, aby to vypadalo jako http://progit.org/book/index.html
 * Chci pouzivat Markdown http://daringfireball.net/projects/markdown/
 */
class BaseBookController extends ApplicationController{
	var $book_name = "";
	var $book_dir = "";

	function index(){
		$this->template_name = "base_book/index";

		$this->page_title = $this->book_title;
	}

	function detail(){
		$this->template_name = "base_book/detail";

		if(!$chapter = $this->book->getChapter($this->params->getString("id"))){
			return $this->_execute_action("error404");
		}

		$this->tpl_data["chapter"] = $chapter;
		$this->tpl_data["page_content"] = $chapter->getContent();
		$this->page_title = $chapter->getTitle();
	}

	function _before_filter(){

		$this->book = $this->tpl_data["book"] = new MdBook($this->book_dir);
		$this->tpl_data["book_title"] = $this->book_title;
	}
}
