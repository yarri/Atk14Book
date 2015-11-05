<ol class="breadcrumb">
	<li>{a action=index}{$book_title|h}{/a}</li>
	{if $parent_chapter}
		<li>{a action="detail" id=$parent_chapter->getId()}{t chapter=$parent_chapter->getNo()}{$parent_chapter->getTitle()}{/t}{/a}</li>
	{/if}
	<li>{$chapter->getTitle()}</li>
</ol>
