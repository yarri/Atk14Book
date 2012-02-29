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

		<div id="footer">
			<div>
				<a href="http://www.atk14.net/">{t}ATK14 je PHP framework pro nebojácné chlapce a děvčata{/t}</a><br />
				{t}ATK14 Book je kniha o tomto frameworku a můžete do ní nahlédnout, i když mezi ně zatím nepatříte{/t}<br /><br />
				Copyleft
				<!--[if lte IE 8]><span style="filter: progid:DXImageTransform.Microsoft.BasicImage(rotation=2); display: inline-block;"><![endif]-->
				<span style="-webkit-transform: rotate(180deg); -moz-transform: rotate(180deg); -o-transform: rotate(180deg); -khtml-transform: rotate(180deg); -ms-transform: rotate(180deg); transform: rotate(180deg); display: inline-block;">
        &copy;
				</span>
				<!--[if lte IE 8]></span><![endif]--> 2011 - {$current_year} {t}Jaromír Tomek & kolegové{/t}
			</div>
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
