<p id="breadcrumbs">
	{render partial="base_book/breadcrumbs"}
</p>

<div id="content" class="span-20">
	{$page_content nofilter}
	{render partial="base_book/subchapters"}
</div>

{if $siblings}
	<div class="span-4 last">
	<div id="siblings">
	{render partial="base_book/siblings"}
	</div>
	</div>
{/if}

<hr />
<div>
	{render partial="base_book/navigation"}
</div>
