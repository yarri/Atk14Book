<?php
class MdBookPrefilter {

	function __construct($options = []){
		$options += [
			"renderer" => function($template_name){ throw new Exception("No renderer given"); }, 
		];

		$this->renderer = $options["renderer"];
	}

	function filter($raw){
		$out = array();
		$GLOBALS["md_book_replaces"] = array();

		$raw = "\n$raw\n";

		$replaces = array();

		if(preg_match_all('/\n\[Include (.+\.(inc|php|sql|tpl))\]\s*/',$raw,$matches_all,PREG_SET_ORDER)){
			foreach($matches_all as $matches){
				$replaces[$matches[0]] = "\n".$this->_place_source($matches[1]);
			}
		}

		if(preg_match_all('/\n\[Render (.+?)\]\s*/',$raw,$matches_all,PREG_SET_ORDER)){
			foreach($matches_all as $matches){
				$renderer = $this->renderer;
				$_content = $renderer($matches[1]);
				$id = "mdbookreplace".uniqid();
				$GLOBALS["md_book_replaces"][$id] = $renderer($matches[1]);
				$replaces[$matches[0]] = "\n".$id;
			}
		}

		$raw = strtr($raw,$replaces);

		$raw = preg_replace_callback('/[\n\r]```([ a-z0-9]*)[\n\r](.*?)\n```[\n\r]/s','_md_book_replace_source',$raw);

		$replaces = array();

		if(preg_match_all('/\n(\t|    )((<\?|--|{\*|<).+?)(\n[^\s]|$)/s',$raw,$matches_all,PREG_SET_ORDER)){
			foreach($matches_all as $matches){
				$tr = array(
					'<' => 'xml',
					'<?' => 'php',
					'{*' => 'smarty',
					'--' => 'sql'
				);
				$lang = $tr[$matches[3]];
				$replaces[$matches[0]] = "\n".$this->_highlight_intext_source($matches[2],$lang).$matches[4];
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
		switch($suffix){
			case "tpl":
				$content = "{* file: $filename *}\n$content";
				break;
			case "php":
			case "inc":
				$content = preg_replace('/^<\?(php|)\n/',"<?\\1\n// file: $filename\n",$content);
				break;
			case "sql":
				$content = "-- file: $filename\n$content";
				break;
		}

		$out = array();

		foreach(explode("\n",$content) as $line){
			$out[]  = "    $line";
		}

		return join("\n",$out);
	}

	function _highlight_syntax($source,$lang){
		$geshi = new GeSHi($source, $lang);
		$geshi->enable_keyword_links(false);
		return $geshi->parse_code();
	}

	function _highlight_intext_source($source,$lang){
		$source = trim($source);
		$source = preg_replace('/\n(\t|    )/',"\n",$source);
		return $this->_highlight_syntax($source,$lang);
	}
}

function _md_book_replace_source($matches){
	($lang = trim($matches[1])) || ($lang = "auto");
	$source = trim($matches[2]);
	$geshi = new GeSHi($source, $lang);
  $geshi->enable_keyword_links(false);
	$id = "mdbookreplace".uniqid();
	$GLOBALS["md_book_replaces"][$id] = $geshi->parse_code();
	return $id;
}

