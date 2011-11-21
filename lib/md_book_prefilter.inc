<?php
class MdBookPrefilter {
	function filter($raw){
		$out = array();

		$replaces = array();

		if(preg_match_all('/\nInclude (app.+)/',$raw,$matches_all,PREG_SET_ORDER)){
			foreach($matches_all as $matches){
				$replaces[$matches[0]] = "\n".$this->_place_source($matches[1]);
			}
		}

		$raw = strtr($raw,$replaces);

		$replaces = array();

		if(preg_match_all('/\n(\t|    )((<\?|{\*|<).+?)(\n[^\s])/s',$raw,$matches_all,PREG_SET_ORDER)){
			foreach($matches_all as $matches){
				$tr = array(
					'<' => 'xml',
					'<?' => 'php',
					'{*' => 'smarty'
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
		$uf = new UrlFetcher($url = "http://www.atk14.net/en/sources/detail/?file=".urlencode($filename)."&format=raw");
		if(!$uf->found()){
			return '<p class="error">'."Vzdálený soubor $filename není možné načíst!".'</p>';
		}
		$content = $uf->getContent();
		$content = str_replace("\t","  ",$content);

		$out = array();

		foreach(explode("\n",$content) as $line){
			$out[]  = "    $line";
		}

		return join("\n",$out);
	}

	function _highlight_syntax($source,$lang){
		$geshi = new GeSHi($source, $lang);
		return $geshi->parse_code();
	}

	function _highlight_intext_source($source,$lang){
		$source = trim($source);
		$source = preg_replace('/\n(\t|    )/',"\n",$source);
		return $this->_highlight_syntax($source,$lang);
	}
}
