<?php
class DrinkMarkdownPrefilter {

	function filter($raw){
		$out = array();
		$GLOBALS["wiki_replaces"] = array();

		$raw = "\n$raw\n";
		
		$replaces = array();

		$uniqid = uniqid();

		$raw = preg_replace_callback('/[\n\r]```([ a-z0-9]*)[\n\r](.*?)\n```[\n\r]/s','_drink_markdown_replace_source',$raw);

		preg_match_all('/\n<table\b[^>]*>.*?<\/table>\s*?\n/si',$raw,$matches);
		for($i=0;$i<sizeof($matches[0]);$i++){
			$snippet = $matches[0][$i];
			$table = trim($snippet);
			$replacement = "table.$i.$uniqid";
			$replaces[$snippet] = "\n\n$replacement\n\n";
			
			$GLOBALS["wiki_replaces"]["<p>$replacement</p>"] = $table;
		}

		//var_dump($GLOBALS["wiki_replaces"]); exit;

		$raw = strtr($raw,$replaces);

		//echo $raw; exit;

		return $raw;
	}
}

function _drink_markdown_replace_source($matches){
	($lang = trim($matches[1])); // "php", "sql", "auto"
	$source = trim($matches[2]);

	$id = "drinkreplace.".uniqid();

	if(strlen($lang)){
		$geshi = new GeSHi($source, $lang);
		$geshi->enable_keyword_links(false);
		$geshi->set_overall_style("");
		$geshi->enable_classes(false);
		$source = $geshi->parse_code();

		$source = preg_replace('/^<pre class="[^"]+"/','<pre',$source); // '<pre class="javascript">' -> '<pre>'
	}else{
		$source = '<pre><code>'.htmlentities($source).'</code></pre>';
	}

	$GLOBALS["wiki_replaces"]["<p>$id</p>"] = $source;
	return "\n\n$id\n\n";
}
