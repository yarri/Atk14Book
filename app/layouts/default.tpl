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
		{/if}

		{stylesheet_link_tag file="$public/dist/styles/vendor.min.css" hide_when_file_not_found=true}
		{stylesheet_link_tag file="$public/dist/styles/application.min.css"}

		<!-- HTML5 shiv and Respond.js IE8 support of HTML5 elements and media queries -->
		<!--[if lt IE 9]>
			{javascript_script_tag file="$public/dist/scripts/html5shiv.min.js"}
			{javascript_script_tag file="$public/dist/scripts/respond.min.js"}
		<![endif]-->

		{render partial="shared/layout/analyticstracking"}

	</head>

	<body class="body_{$controller}_{$action}" data-controller="{$controller}" data-action="{$action}">

		<header>

		<nav class="navbar navbar-default">
			<div class="container">
				<!-- Brand and toggle get grouped for better mobile display -->
				<div class="navbar-header">
					<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse" aria-expanded="false">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<a class="navbar-brand" href="/"><i class="glyphicon glyphicon-home hidden-xs"></i> <strong class="title hidden visible-xs">{"ATK14_APPLICATION_NAME"|dump_constant}</strong></a>
				</div>

				<!-- Collect the nav links, forms, and other content for toggling -->
				<div class="collapse navbar-collapse" id="navbar-collapse">
					<ul class="nav navbar-nav navbar-right">
						<li>
							<a href="http://www.atk14.net/">ATK14 Framework</a>
						</li>
						<li>
							<a href="http://api.atk14.net/">API Reference</a>
						</li>
						<li>
							<a href="http://www.atk14sites.net/">Who uses ATK14?</a>
						</li>
					</ul>
				</div><!-- /.navbar-collapse -->
			</div> <!-- /.container -->
		</nav>


			<!-- Docs page layout -->
			<div class="bs-docs-header hidden-xs" id="content" tabindex="-1">
				<div class="container">
					<h1>ATK14 Book</h1>
					<p>{t}Read, hear, and study the ATK14 Book. Grow your skills. Relax. Repeat.{/t}</p>
				</div>
			</div>

		</header>
		


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

		{javascript_script_tag file="$public/dist/scripts/vendor.min.js"}
		{javascript_script_tag file="$public/dist/scripts/application.min.js"}
	</body>
</html>
