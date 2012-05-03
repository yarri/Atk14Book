<?php
class SearchesController extends ApplicationController{
	function search(){
		if($d = $this->form->validate($this->params)){
			$this->_redirect_to("https://www.google.com/search?q=".urlencode("$d[q] site:book.atk14.net"));
		}else{
			$this->_redirect_to("atk14_book/index");
		}
	}
}
