<?php
class MdBookPrefilter {
	function filter($raw){
		$out = array();
		$GLOBALS["wiki_replaces"] = array();

		$raw = "\n$raw\n";

		$replaces = array();

		if(preg_match_all('/\nInclude (.+\.(inc|php|sql|tpl))/',$raw,$matches_all,PREG_SET_ORDER)){
			foreach($matches_all as $matches){
				$replaces[$matches[0]] = "\n".$this->_place_source($matches[1]);
			}
		}
		$raw = strtr($raw,$replaces);

		$raw = preg_replace_callback('/[\n\r]```([ a-z0-9]*)[\n\r](.*?)\n```[\n\r]/s','_wiki_replace_source',$raw);


		$replaces = array();

		if(preg_match_all('/\n(\t|    )((<\?|--|{\*|<).+?)(\n[^\s])/s',$raw,$matches_all,PREG_SET_ORDER)){
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


		/*
		foreach(explode("\n",$raw) as $line){
			if(preg_match('/^Include (app\/[^\s]*)/',$line,$matches)){
				$out[] = $this->_place_source($matches[1]);
				continue;
			}	
			$out[] = $line;
		}
		*/

		return $raw;

		
		return join("\n",$out);
	}

	function _place_source($filename){
		$suffix = preg_replace('/.*\.([^.]+)$/','\1',$filename);
		$uf = new UrlFetcher($url = "http://www.atk14.net/en/sources/detail/?file=".urlencode($filename)."&format=raw");
		if(!$uf->found()){
			return '<p class="error">'."Vzdálený soubor $filename není možné načíst!".'</p>';
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

function _wiki_replace_source($matches){
	($lang = trim($matches[1])) || ($lang = "auto");
	$source = trim($matches[2]);
	$geshi = new GeSHi($source, $lang);
  $geshi->enable_keyword_links(false);
	$id = "wikireplace".uniqid();
	$GLOBALS["wiki_replaces"][$id] = $geshi->parse_code();
	return $id;
}

