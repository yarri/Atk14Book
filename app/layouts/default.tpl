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
<html lang="{$lang}" class="no-js">

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

		{* Indication of active javascript *}
		{javascript_tag}
			document.documentElement.className = document.documentElement.className.replace( /\bno-js\b/, "js" );
		{/javascript_tag}

		<link rel="preconnect" href="https://fonts.googleapis.com">
		<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
		<link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:ital,wght@0,100..700;1,100..700&family=JetBrains+Mono:ital,wght@0,100..800;1,100..800&display=swap" rel="stylesheet">

		{stylesheet_link_tag file="$public/dist/styles/vendor.css" hide_when_file_not_found=true}
		{stylesheet_link_tag file="$public/dist/styles/application_styles.css"}

		
		{render partial="shared/layout/favicons"}

		{placeholder for=head} {* a place for <link rel="canonical" ...>, etc. *}
	</head>

	<body class="body_{$controller}_{$action}" data-controller="{$controller}" data-action="{$action}" data-bs-theme="{if $request->getCookieVar("dark_mode")}dark{else}light{/if}">

		{render partial="shared/layout/header"}

		<div class="container-fluid{if $section_navigation} has-nav-section{/if}">
			{*render partial="shared/layout/header" *}
			{render partial="shared/breadcrumbs"}

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
		
		{*render partial="shared/layout/footer"*}
		{if $DEVELOPMENT}<!-- USING_BOOTSTRAP4: {USING_BOOTSTRAP4}, USING_BOOTSTRAP5: {USING_BOOTSTRAP5}/-->{/if}
		{render partial="shared/layout/devcssinfo"}

		{javascript_script_tag file="$public/dist/scripts/vendor.min.js"}
		{javascript_script_tag file="$public/dist/scripts/application.min.js"}
		{javascript_tag}
			{placeholder for="js"}
		{/javascript_tag}
	</body>
</html>
