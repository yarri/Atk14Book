<?php
require_once(dirname(__FILE__)."/base_book.php");

class Atk14BookController extends BaseBookController{

	function _before_filter(){
		global $ATK14_GLOBAL;

		$this->book_dir = $ATK14_GLOBAL->getPublicRoot()."book/";

		parent::_before_filter();
	}

	function detail(){
		parent::detail();
		$this->template_name = "detail";

		$navigation = new Navigation();
		$chapter = $this->chapter;
		($parent_chapter = $chapter->getParentChapter()) || ($parent_chapter = $chapter);

		foreach($this->book->getChapters() as $ch){
			$navigation->add(
				$ch->getNo().". ".$ch->getTitle(),
				$this->_link_to(array(
					"action" => "detail",
					"id" => $ch->getId()
				)),
				array("active" => $ch->getNo()===$chapter->getNo())
			);

			if($ch->getNo()===$parent_chapter->getNo()){
				// vypsani podkapitol
				foreach($parent_chapter->getSubChapters() as $ch){
					$navigation->add(
						'<div style="padding-left: 1em;">'.$ch->getNo()." ".$ch->getTitle().'</div>',
						$this->_link_to(array(
							"action" => "detail",
							"id" => $ch->getId()
						)),
						array("active" => $ch->getNo()===$chapter->getNo())
					);
				}
			}

			$this->tpl_data["navigation"] = $navigation;
		}
	}

	function index(){
		parent::index();
		$this->template_name = "index";
	}
}
