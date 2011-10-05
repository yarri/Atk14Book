<h1>{t}Obsah{/t}</h1>

<ul>
	{render partial=base_book/chapter_item from=$book->getChapters() item=chapter}
</ul>
