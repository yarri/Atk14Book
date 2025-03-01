<?php
/**
 * Place source code of the specific file from the site www.atk14.net
 *
 * Usage in markdown:
 *
 * [include file="app/controllers/users_controller.php"]
 *
 */
function smarty_function_drink_shortcode__include($params, $template) {
	$filename = $params["file"];
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

	$text = "```$lang\n$content\n```";

	$markdown = new DrinkMarkdown();
	return $markdown->transform($text);
}
