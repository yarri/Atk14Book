Inserting partial templates and other content
=============================================

* ### render ([function](https://github.com/atk14/Atk14/blob/master/src/atk14/helpers/function.render.php))

	Inserts a partial template.

		{* the template app/views/shared/_user_info.tpl will be inserted here *}
		{render partial="shared/user_info"}

	Iterates over a collection and renders a partial template for each item.

		{* file: app/views/articles/index.tpl *}
		<ul>
		{*
		 * The template _article_item.tpl will be inserted as many times as there are articles.
		 * In each iteration the current article is available in the $article variable.
		 *}
		{render partial="article_item" from=$articles item=article}
		</ul>

	The partial template:

		{* file: app/views/articles/_article_item.tpl *}
		<li>
			{a action="detail" id=$article}{$article->getTitle()}{/a}
		</li>

* ### placeholder ([function](https://github.com/atk14/Atk14/blob/master/src/atk14/helpers/function.placeholder.php))
	
	Marks a position (typically in a layout) where something will be rendered elsewhere using {content}.

		{placeholder for="side_navigation"}

* ### content ([block](https://github.com/atk14/Atk14/blob/master/src/atk14/helpers/block.content.php))

	Renders content for the matching {placeholder} position.

		{* file app/views/main/index.tpl *}

		<h2>Welcome</h2>

		<p></p>

		{content for="side_navigation"}
			<ul>
				<li>{a action="main/about"}About us{/a}</li>
			</ul>
		{/content}

* ### render_component ([function](https://github.com/atk14/Atk14/blob/master/src/atk14/helpers/function.render_component.php))

	Renders the output of another controller and action. This can sometimes be useful. Try to avoid it if possible, though.
		
		{render_component controller=navigation action=index}
