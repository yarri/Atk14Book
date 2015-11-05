{render partial="base_book/breadcrumbs"}

<div>
	{$page_content nofilter}
	{render partial="base_book/subchapters"}
</div>

{if $siblings}
	<div>
	{render partial="base_book/siblings"}
	</div>
{/if}

<hr />

<div>
	{render partial="base_book/navigation"}
</div>
