<p id="breadcrumbs">
	{a action=index}{$book_title|h}{/a}
	{if $parent_chapter}
		&rarr; {a action=detail id=$parent_chapter->getId()}{$parent_chapter->getTitle()|h}{/a}
	{/if}
	&rarr; {t chapter=$chapter->getNo()}kapitola %1{/t}
</p>

{$page_content}

<div>
	{if $chapter->getPrevChapter()}
		{assign var=prev value=$chapter->getPrevChapter()}
		{a action=detail id=$chapter->getPrevChapter() _title=$prev->getTitle()|h}{t}předchozí kapitola{/t}{/a}
	{else}	
		{a action=index}{t escape=no}obsah <!-- knihy -->{/t}{/a}
	{/if}
	|
	{if $chapter->getNextChapter()}
		{assign var=next value=$chapter->getNextChapter()}
		{a action=detail id=$chapter->getNextChapter() _title=$next->getTitle()|h}{t}další kapitola{/t}{/a}
	{else}
		{a action=index}{t escape=no}obsah <!-- knihy -->{/t}{/a}
	{/if}
</div>
