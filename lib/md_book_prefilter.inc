<?php
class MdBookPrefilter {
	function filter($raw){
		$out = array();
		foreach(explode("\n",$raw) as $line){
			if(preg_match('/^Include (app\/[^\s]*)/',$line,$matches)){
				$out[] = $this->_place_source($matches[1]);
				continue;
			}	
			$out[] = $line;
		}

		return join("\n",$out);
	}

	function _place_source($filename){
		$uf = new UrlFetcher("http://www.atk14.net/en/sources/detail/?file=".urlencode($filename)."&format=raw");
		$content = $uf->getContent();
		$content = str_replace("\t","  ",$content);

		$out = array();

		if(preg_match('/(php|inc)$/',$filename)){
			/*
			$out[] = "<pre>";
			$out[] = str_replace("\n","",highlight_string($content,true));
			$out[] = "</pre>";
			return join("",$out);
			*/
			return $this->_highlight_syntax($content,"php");
		}elseif(preg_match('/(tpl)$/',$filename)){
			return $this->_highlight_syntax($content,"smarty");
		}else{
			foreach(explode("\n",$content) as $line){
				$out[]  = "    $line";
			}
		}
		return join("\n",$out);
	}

	function _highlight_syntax($source,$lang){
		$geshi = new GeSHi($source, $lang);
		return $geshi->parse_code();
	}
}
