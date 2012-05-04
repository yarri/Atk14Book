<p>
<img src="{$public}images/children.jpg" align="right" style="padding-left: 1em;"/>
Zde vzniká kniha o <a href="http://www.atk14.net/">populárním PHP frameworku</a> pro nebojácné chlapce a děvčata.
Některé texty jsou hodně pracovní, jiné chybí, další jsou tu zcela zbytečně. Celkově to však není špatné počteníčko.
Přejeme mnoho štěstí a odvahy při hledání smyslu následujících řádek.</p>

<h1>{t}Obsah{/t}</h1>

<ul>
	{render partial=base_book/chapter_item from=$book->getChapters() item=chapter}
</ul>
