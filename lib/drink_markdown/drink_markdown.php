<?php
class DrinkMarkdown{

	function __construct($options = array()){
		$options += array(
			"prefilter" => new DrinkMarkdownPrefilter(),
			"postfilter" => new DrinkMarkdownPostfilter(),
		);

		$this->prefilter = $options["prefilter"];
		$this->postfilter = $options["postfilter"];
	}

	function transform($raw){
		if($this->prefilter){ $raw = $this->prefilter->filter($raw); }

		$out = Michelf\MarkdownExtra::defaultTransform($raw);

		if($this->postfilter){ $out = $this->postfilter->filter($out); }

		return $out;
	}
}
