<?php
class SearchForm extends ApplicationForm{
	function set_up(){
		$this->set_method("get");
		$this->set_action(Atk14Url::BuildLink("searches/search"));
		$this->set_attr("id","search_form");

		$this->add_field("q",new CharField(array(
			"max_length" => 1000,
			"required" => true,
		)));
	}
}
