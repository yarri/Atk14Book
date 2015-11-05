Vkladání dílčích šablon a dalšího obsahu
========================================

* ### render ([function](https://github.com/atk14/Atk14/blob/master/src/atk14/helpers/function.render.php))

	Vložení dílčí šablony

		{* zde bude vložena šablona app/views/shared/_user_info.tpl *}
		{render partial="shared/user_info"}

	Projítí kolekce a vykreslení dílčí šablony pro každý prvek

		{* file: app/views/articles/index.tpl *}
		<ul>
		{*
		 * Sem bude vložena šablona _article_item.tpl tolikrát, kolik je článků.
		 * V každém průchodu bude k dispozici akt. článek v proměnné $article.
		 *}
		{render partial="article_item" from=$articles item=article}
		</ul>

	Dílčí šablonka

		{* file: app/views/articles/_article_item.tpl *}
		<li>
			{a action="detail" id=$article}{$article->getTitle()}{/a}
		</li>

* ### placeholder ([function](https://github.com/atk14/Atk14/blob/master/src/atk14/helpers/function.placeholder.php))
	
	Označí místo (typicky v layoutu), kam bude něco vyrenderováno někde jinde pomocí {content}.

		{placeholder for="side_navigation"}

* ### content ([block](https://github.com/atk14/Atk14/blob/master/src/atk14/helpers/block.content.php))

	Vyrenderuje obsah pro stejně označené místo pomocí {placeholder}.

		{* file app/views/main/index.tpl *}

		<h2>Welcome</h2>

		<p></p>

		{content for="side_navigation"}
			<ul>
				<li>{a action="main/about"}About us{/a}</li>
			</ul>
		{/content}

* ### render_component ([function](https://github.com/atk14/Atk14/blob/master/src/atk14/helpers/function.render_component.php))

	Vyrenderuje výstup z jiného kontroleru a akce. Toto se někdy hodí. Spíš se však snažte to vůbec nepoužívat.
		
		{render_component controller=navigation action=index}
