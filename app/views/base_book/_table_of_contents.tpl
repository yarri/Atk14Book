<h2>{t}Obsah{/t}</h2>

<ul class="table-of-contents">
	{render partial="base_book/chapter_item" from=$book->getChapters() item=chapter}
</ul>
