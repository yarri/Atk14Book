<?php
class DrinkMarkdownPrefilter {
	function filter($raw){
		$out = array();
		$GLOBALS["wiki_replaces"] = array();

		$raw = "\n$raw\n";
		
		$replaces = array();

		$raw = strtr($raw,$replaces);

		return $raw;
	}
}
