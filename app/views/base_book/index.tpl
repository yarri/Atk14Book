<h2>{$page_title}</h2>

<ul>
	{render partial=base_book/chapter_item from=$book->getChapters() item=chapter}
</ul>
