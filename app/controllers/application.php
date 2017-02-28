<?php
class ApplicationController extends Atk14Controller{
	function index(){
		$this->_execute_action("error404");
	}

	function error404(){
		$this->page_title = "Page not found";
		$this->response->setStatusCode(404);
		$this->template_name = "application/error404";
	}

	function _initialize(){
		$this->_prepend_before_filter("application_before_filter");
		$this->_append_after_filter("application_after_filter");

		if(!$this->rendering_component){
			// Definujme toto jako posledni krok v _initialize()!
			// Docilime toho, ze filtr _begin_database_transaction() bude zavolan uplne jako prvni before filter
			// a _end_database_transaction() zase jako posledni after filtr.
			$this->_prepend_before_filter("begin_database_transaction");
			$this->_append_after_filter("end_database_transaction");
		}
	}

	function _application_before_filter(){
		$this->response->setContentType("text/html");
		$this->response->setContentCharset("UTF-8");
		$this->tpl_data["current_year"] = date("Y");

		$this->tpl_data["search_form"] = Atk14Form::GetInstanceByFilename("searches/search_form.php",$this);
	}

	function _application_after_filter(){
    if(DEVELOPMENT){
      $bar = Tracy\Debugger::getBar();
      $bar->addPanel(new DbMolePanel($this->dbmole));
			$bar->addPanel(new TemplatesPanel());
    }
	}

	function _begin_database_transaction(){
		$this->dbmole->begin(array(
			"execute_after_connecting" => true
		));
	}

	function _end_database_transaction(){
		$this->dbmole->commit();
	}
}
