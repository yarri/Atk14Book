Vytváření odkazů
----------------
* #### a ([block](https://github.com/yarri/Atk14/blob/master/src/atk14/helpers/block.a.php))

	Vykreslí html tag &lt;a&gt;

		{a controllers="books" action="detail" id=$book}{$book->getTitle()}{/a}

* #### a\_remote ([block](https://github.com/yarri/Atk14/blob/master/src/atk14/helpers/block.a_remote.php))

	Vykreslí html tag &lt;a&gt;, který bude zpracován asynchronně pomocí _atk14.js_

		{a_remote controllers="users" action="logout" _method="post" _confirm="Do you really want to sign out?"}Sign out{/a}

* #### a\_destroy ([block](https://github.com/yarri/Atk14/blob/master/src/atk14/helpers/block.a_destroy.php))

	Vykreslí odkaz pro smazání nějakého záznamu. Jedná se zkratku pro volání a\_remote.

		{a_destroy id=$book}Delete this book entry{/a_destroy}

		{* je to samé, co tohle: *}
		{a_remote action="destroy" id=$book _method="post" _confirm="Are you sure?"}Delete this book entry{/a_remote}

* #### link\_to ([function](https://github.com/yarri/Atk14/blob/master/src/atk14/helpers/function.link_to.php))

		<a href="{link_to action="books/detail" id=$book}">{$book->getTitle()}</a>

* #### a\_remote\_with\_onclick ([block](https://github.com/yarri/Atk14/blob/master/src/atk14/helpers/block.a_remote_with_onclick.php))

	Tohle je něco, co asi vůbec nepoužijete :)
