<?php
class MdBookPostfilter {
	function filter($content){
		$content = strtr($content,$GLOBALS["md_book_replaces"]);

		return $content;
	}
}
