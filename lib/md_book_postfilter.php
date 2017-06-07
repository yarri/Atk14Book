<?php
class MdBookPostfilter {

	function filter($content,$transformer){
		$content = strtr($content,$GLOBALS["md_book_replaces"]);

		$replaces = array();

		// Source code examples with no syntax highlighting
		preg_match_all('/<pre><code>(.*?)<\/code><\/pre>/si',$content,$matches);
		for($i=0;$i<sizeof($matches[0]);$i++){
			$snippet = $matches[0][$i];
			$source_code = trim(html_entity_decode($matches[1][$i]));

			$lang = "";
			
			foreach(array(
				"php" => '/^<\?php/',
				"yaml" => '/file: .*\.yml/',
				"sql" => '/file: .*\.sql/',
				"smarty" => '/\{\*/',
				"javascript" => '/\{/'
			) as $l => $pattern){
				if(preg_match($pattern,$source_code)){
					$lang = $l;
					break;
				}
			}

			if(!$lang){ continue; }

			$source_code = $transformer->formatSourceCode($source_code,array(
				"lang" => $lang
			));
			$replaces[$snippet] = $source_code;
		}

		$content = EasyReplace($content,$replaces);

		return $content;
	}
}
