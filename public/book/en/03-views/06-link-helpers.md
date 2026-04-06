Creating links
--------------
* #### a ([block](https://github.com/atk14/Atk14/blob/master/src/atk14/helpers/block.a.php))

	Renders an &lt;a&gt; HTML tag.

		{a controller="books" action="detail" id=$book}{$book->getTitle()}{/a}

* #### a\_remote ([block](https://github.com/atk14/Atk14/blob/master/src/atk14/helpers/block.a_remote.php))

	Renders an &lt;a&gt; HTML tag that will be handled asynchronously via _atk14.js_.

		{a_remote controller="users" action="logout" _method="post" _confirm="Do you really want to sign out?"}Sign out{/a}

* #### a\_destroy ([block](https://github.com/atk14/Atk14/blob/master/src/atk14/helpers/block.a_destroy.php))

	Renders a link for deleting a record. It is a shortcut for calling a\_remote.

		{a_destroy id=$book}Delete this book entry{/a_destroy}

		{* is the same as this: *}
		{a_remote action="destroy" id=$book _method="post" _confirm="Are you sure?"}Delete this book entry{/a_remote}

* #### link\_to ([function](https://github.com/atk14/Atk14/blob/master/src/atk14/helpers/function.link_to.php))

		<a href="{link_to action="books/detail" id=$book}">{$book->getTitle()}</a>

* #### a\_remote\_with\_onclick ([block](https://github.com/atk14/Atk14/blob/master/src/atk14/helpers/block.a_remote_with_onclick.php))

	This is something you probably won't use at all :)
