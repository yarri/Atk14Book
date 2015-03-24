{*
 * The page Layout template
 *
 * Placeholders
 * ------------
 * head						 	located whithin the <head> tag
 * main							the main (or default) one
 * js_script_tags				place for javascript script tags
 * js							place for javascript code
 * domready						place for domready javascript code
 *
 * Variables
 * ------------
 * $lang
 * $controller
 * $action
 * $namespace
 * $logged_user
 * $page_description
 *
 * Constants
 * ------------
 * $DEVELOPMENT
 *}
<!DOCTYPE html>
<html lang="{$lang}">

	<head>
		<meta charset="utf-8">

		<title>{trim}
			{if $controller=="main" && $action=="index" && $namespace==""}
				{"ATK14_APPLICATION_NAME"|dump_constant}
			{else}
				{$page_title} | {"ATK14_APPLICATION_NAME"|dump_constant}
			{/if}
		{/trim}</title>

		<meta name="description" content="{$page_description}" />
		<meta name="viewport" content="width=device-width,initial-scale=1.0">

		{if $DEVELOPMENT}
			{render partial="shared/layout/dev_info"}

			{stylesheet_link_tag file="../dist/css/app.css" media="screen"}
		{else}
			{stylesheet_link_tag file="../dist/css/app.min.css" media="screen"}
		{/if}

		<!-- HTML5 shiv and Respond.js IE8 support of HTML5 elements and media queries -->
		<!--[if lt IE 9]>
			<script src="{$public}dist/vendor/js/html5shiv.js"></script>
			<script src="{$public}dist/vendor/js/respond.min.js"></script>
		<![endif]-->
	</head>

	<body class="body_{$controller}_{$action}" data-controller="{$controller}" data-action="{$action}">

		<header class="navbar navbar-static-top bs-docs-nav" id="top" role="banner">
			<div class="container">
				<div class="navbar-header">
					<button class="navbar-toggle collapsed" type="button" data-toggle="collapse" data-target=".bs-navbar-collapse">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<a href="/" class="navbar-brand"><i class="glyphicon glyphicon-home"></i></a>
				</div>
				<nav class="collapse navbar-collapse bs-navbar-collapse">
					<ul class="nav navbar-nav">
						<li>
							<a href="http://www.atk14.net/">ATK14</a>
						</li>
						<li>
							<a href="http://api.atk14.net/">API Reference</a>
						</li>
						<li>
							<a href="http://www.atk14sites.net/">Who uses ATK14?</a>
						</li>
					</ul>
				</nav>
			</div>
		</header>

		 <!-- Docs page layout -->
    <div class="bs-docs-header" id="content" tabindex="-1">
      <div class="container">
        <h1>ATK14 Book</h1>
				<p>{t}Read, hear, and study the ATK14 Book. Grow your skills. Relax. Again.{/t}</p>
      </div>
    </div>

		<div class="container{if $section_navigation} has-nav-section{/if}">
			{*render partial="shared/layout/header" *}

			<div class="body">
				{if $section_navigation}
					<nav class="nav-section">
						{render partial="shared/layout/section_navigation"}
					</nav>
				{/if}

				<div class="content-main">
					{render partial="shared/layout/flash_message"}
					{placeholder}
				</div>
			</div>

			{*
			{render partial="shared/layout/footer"}
			*}
		</div>

		{if $DEVELOPMENT}
			{javascript_script_tag file="../dist/js/app.js"}
			<script src="//localhost:35729/livereload.js"></script>
		{else}
			{javascript_script_tag file="../dist/js/app.min.js"}
		{/if}
	</body>
</html>
