<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="{$lang}" lang="{$lang}">

	<head>
		<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
		<meta http-equiv="content-language" content="{$lang}" />

		{render partial=shared/layout/meta_headers}

		<title>{$page_title|h} | {"ATK14_APPLICATION_NAME"|dump_constant}</title>
		<meta name="description" content="{$page_description|h}" />

		{stylesheet_link_tag file="blueprint/screen.css" media="screen, projection"}
		{stylesheet_link_tag file="blueprint/print.css" media="print"}
		<!--[if IE]>
		{stylesheet_link_tag file="blueprint/ie.css" media="screen, projection"}
		<![endif]-->
		{stylesheet_link_tag file="styles.css" media="screen, projection"}

		<script type="text/javascript" src="http{if $request->ssl()}s{/if}://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>
		<script type="text/javascript" src="http{if $request->ssl()}s{/if}://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/jquery-ui.min.js"></script>
		{javascript_script_tag file="atk14.js"}

		{placeholder for="head"}

		{javascript_tag}
			{placeholder for="js"}
			$(function() \{
				{placeholder for="domready"}
			\});
		{/javascript_tag}	
	</head>

	<body id="body_{$controller}_{$action}">
		<h1 id="logo"><span>{t}ATK14 Book{/t}</span></h1>

		<div class="container">  

		{render partial=shared/layout/flash_message}

		{placeholder}

		</div>

		{* Google analytics code *}
		{javascript_tag}
			var _gaq = _gaq || [];
			_gaq.push(['_setAccount', 'UA-27229703-2']);
			_gaq.push(['_trackPageview']);

			(function() \{
				var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
				ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
				var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
			\})();
		{/javascript_tag}

	</body>

</html>
