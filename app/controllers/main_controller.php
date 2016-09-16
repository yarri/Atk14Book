<?php
class MainController extends ApplicationController{
	function index(){
		$this->_redirect_to(array(
			"controller" => "atk14_book",
		));
	}
}
