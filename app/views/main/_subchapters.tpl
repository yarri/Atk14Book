{if $subchapters}
	<h2>{t}Seznam podkapitol{/t}</h2>
	<ul class="table-of-contents">
		{foreach $subchapters as $subchapter}
			<li><h4>{$subchapter->getNo()} {a action=detail id=$subchapter}{$subchapter->getTitle()|h}{/a}</h4></li>
		{/foreach}
	</ul>
{/if}
