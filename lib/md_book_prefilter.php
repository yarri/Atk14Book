<?php
class MdBookPrefilter {

	function __construct($options = []){
		$options += [
			"renderer" => function($template_name){ throw new Exception("No renderer given"); }, 
		];

		$this->renderer = $options["renderer"];
	}

	function filter($raw,$transformer){
		$out = array();
		$GLOBALS["md_book_replaces"] = array();

		$raw = "\n$raw\n";

		$replaces = array();

		if(preg_match_all('/\n\[Include (.+\.(inc|php|sql|tpl))\]\s*?\n/',$raw,$matches_all,PREG_SET_ORDER)){
			foreach($matches_all as $matches){
				$replaces[$matches[0]] = "\n".$this->_place_source($matches[1])."\n";
			}
		}

		if(preg_match_all('/\n\[Render (.+?)\]\s*?\n/',$raw,$matches_all,PREG_SET_ORDER)){
			foreach($matches_all as $matches){
				$renderer = $this->renderer;
				$_content = $renderer($matches[1]);
				$id = "mdbookreplace".uniqid();
				$GLOBALS["md_book_replaces"][$id] = $renderer($matches[1]);
				$replaces[$matches[0]] = "\n$id\n";
			}
		}

		$raw = strtr($raw,$replaces);

		return $raw;
	}

	function _place_source($filename){
		$suffix = preg_replace('/.*\.([^.]+)$/','\1',$filename);
		$uf = new UrlFetcher($url = "http://www.atk14.net/en/sources/detail/?file=".urlencode($filename)."&format=raw");
		if(!$uf->found()){
			$err = "Remote file $filename could not be read";
			trigger_error(sprintf("%s (%s)",$err,$GLOBALS["HTTP_REQUEST"]->getUrl()));
			return '<p class="alert alert-danger"><span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span> '.$err.'</p>';
		}
		$content = $uf->getContent();
		$content = str_replace("\t","  ",$content);
		$lang = "";
		switch($suffix){
			case "tpl":
				$lang = "smarty";
				$content = "{* file: $filename *}\n$content";
				break;
			case "php":
			case "inc":
				$lang = "php";
				$content = preg_replace('/^<\?(php|)\n/',"<?\\1\n// file: $filename\n",$content);
				break;
			case "sql":
				$lang = "sql";
				$content = "-- file: $filename\n$content";
				break;
		}

		return "```$lang\n$content\n```";
	}
}
