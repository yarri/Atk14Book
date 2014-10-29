<?php
class MdBookPostfilter {
	function filter($content){
		$content = preg_replace('/(<(p|h[0-9]|pre|ul)(| [^>]*)>)/e','_mark_element("\1")',$content);

		$content = strtr($content,$GLOBALS["wiki_replaces"]);

		return $content;
	}
}

function _mark_element($element){
	static $COUNTER;
	if(!isset($COUNTER)){ $COUNTER = array(); }
	preg_match('/<([a-z]+)/',$element,$matches);
	$type = $matches[1]; // p, code, h

	if(!isset($COUNTER[$type])){ $COUNTER[$type] = 0; }
	$cnt = &$COUNTER[$type];
	$cnt++;

	$element = preg_replace('/^<[^>]+/',"\\0 id=\"comment_{$type}_{$cnt}\"",$element);
	if(preg_match('/^<[^>]+\sclass="/',$element)){
		$element = preg_replace('/^<[^>]+\sclass="[^"]+/',"\\0 commentable",$element);
	}else{
		$element = preg_replace('/^<[^>]+/',"\\0 class=\"commentable\"",$element);
	}
	
	return $element;
}
