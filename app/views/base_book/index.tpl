<p>
<img src="{$public}images/children.jpg" align="right" style="padding-left: 1em;"/>
Zde vzniká kniha o <a href="http://www.atk14.net/">populárním PHP frameworku</a> pro nebojácné chlapce a děvčata.
Upozorňujeme, že některé texty jsou hodně pracovní a jiné zcela chybí.
</p>
<p>
Milí chlapci a děvčata, přejeme mnoho štěstí a odvahy při četbě.
</p>

<h1>{t}Obsah{/t}</h1>

<ul>
	{render partial="base_book/chapter_item" from=$book->getChapters() item=chapter}
</ul>
