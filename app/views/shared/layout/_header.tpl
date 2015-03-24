<header class="header-main">
	{assign var=appname value="ATK14_APPLICATION_NAME"|dump_constant}

	<div id="logo">
		{if $controller=="main" && $action=="index" && $namespace==""}
			<h1>{$appname}</h1>
		{else}
			{capture assign=link_title}{t}Go to home page{/t}{/capture}
			{a action="main/index" namespace="" _title=$link_title}{$appname}{/a}
		{/if}
	</div>
	<h1>{$appname}</h1>

	<p>{t}Read, hear, and study the ATK14 Book. Grow your skills. Earn some money. Enjoy your free time.{/t}</p>
</header>
