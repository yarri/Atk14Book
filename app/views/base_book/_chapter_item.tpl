<li>
	<h4>{$chapter->getNo()} {a action=detail id=$chapter}{$chapter->getTitle()}{/a}</h4>
	{if $chapter->hasSubChapters()}
		<ul>
		{render partial="base_book/chapter_item" from=$chapter->getSubChapters() item=chapter}
		</ul>
	{/if}
</li>
