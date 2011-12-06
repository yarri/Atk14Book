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

		if($this->params->getString("format")=="sitemap"){
			$this->render_template = false;
			$this->response->setContentType("text/xml");
			$this->response->writeln('<?xml version="1.0" encoding="UTF-8"?>');
			$this->response->writeln('<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">');
			foreach($this->book->getChapters() as $chapter){
				$this->response->writeln($this->_get_sitemap_chapter_item($chapter));
				foreach($chapter->getSubChapters() as $subchapter){
					$this->response->writeln($this->_get_sitemap_chapter_item($subchapter));
				}
			}
			$this->response->write('</urlset>');
		}
	}

	function _get_sitemap_chapter_item($chapter){
		return "<url><loc>".$this->_link_to(array(
			"action" => "detail",
			"id" => $chapter,
		),array(
			"with_hostname" => true,
		))."</loc></url>";
	}

	function detail(){
		$this->template_name = "base_book/detail";

		if(!$chapter = $this->book->getChapter($this->params->getString("id"))){
			return $this->_execute_action("error404");
		}

		$this->tpl_data["chapter"] = $chapter;
		$this->tpl_data["parent_chapter"] = $chapter->getParentChapter();
		$this->tpl_data["page_content"] = $chapter->getContent();
		$this->page_title = $chapter->getTitle();
	}

	function _before_filter(){
		$this->book = $this->tpl_data["book"] = new MdBook($this->book_dir,array(
			"prefilter" => new MdBookPrefilter()
		));
		$this->tpl_data["book_title"] = $this->book_title;
	}
}
