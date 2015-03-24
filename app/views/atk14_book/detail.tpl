<p id="breadcrumbs">
	{render partial="base_book/breadcrumbs"}
</p>

<div class="col-md-9" role="main">
	{$page_content nofilter}
	{render partial="base_book/subchapters"}
	<hr />
	<div>
		{render partial="base_book/navigation"}
	</div>
</div>

{if $siblings}
	<div class="col-md-3" role="complementary">
	<nav class="bs-docs-sidebar hidden-print hidden-xs hidden-sm affix-top">
		<div id="siblings">
			{render partial="base_book/siblings"}
		</div>
	</nav>
	</div>
{/if}

