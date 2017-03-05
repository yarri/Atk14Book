{if $subchapters}
	<ul class="table-of-contents">
		{render partial="base_book/subchapter_item" from=$subchapters item=subchapter}
	</ul>
{/if}
