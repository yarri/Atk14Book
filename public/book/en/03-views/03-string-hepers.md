String functions
================

* ### h ([block](https://github.com/atk14/Atk14/blob/master/src/atk14/helpers/block.h.php) and [modifier](https://github.com/atk14/Atk14/blob/master/src/atk14/helpers/modifier.h.php))

	Escapes special HTML characters. Equivalent to PHP's [htmlspecialchars](http://php.net/manual/en/function.htmlspecialchars.php).

		{* block usage *}
		{h}You are logged in as {$user->getName()}{/h}

		{* usage as a modifier *}
		You are logged in as {$user->getName()|h}

	Now something very important. The **_h_ modifier is set as the default automatic modifier for printing all values** (see the ATK14\_SMARTY\_DEFAULT\_MODIFIER configuration constant).
	The _h_ helper is therefore the most commonly used helper, yet you will barely see it in templates.

		{* because in fact this *}
		You are logged in as {$user->getName()}

		{* actually means this *}
		You are logged in as {$user->getName()|h}

		{* if you don't want default escaping, write *}
		You are logged in as {$user->getName() nofilter}

		{* or simply use the exclamation mark *}
		You are logged in as {!$user->getName()}


* ### javascript_tag ([block](https://github.com/atk14/Atk14/blob/master/src/atk14/helpers/block.javascript_tag.php))

	Prints a string in quotes suitable for use in JavaScript constructs.

		{* file: app/users/edit.xhr.tpl *}
		{* jQuery notation *}
		$form.replaceWith({jstring}{render partial=form}{/jstring});

* ### t ([block](https://github.com/atk14/Atk14/blob/master/src/atk14/helpers/block.t.php))

	Gettext in Smarty.

		{t name=$user->getName()}You are logged in as %1{/t}

		{t name=$user->getName()|h escape=no}You are logged in as <em>%1</em>{/t}

* ### trim ([block](https://github.com/atk14/Atk14/blob/master/src/atk14/helpers/block.trim.php))

    Removes all whitespace from the beginning and end of the content.

* ### camelize ([modifier](https://github.com/atk14/Atk14/blob/master/src/atk14/helpers/modifier.camelize.php))
