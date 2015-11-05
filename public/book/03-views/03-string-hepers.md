Řetězcové funkce
================

* ### h ([block](https://github.com/atk14/Atk14/blob/master/src/atk14/helpers/block.h.php) a [modifier](https://github.com/yarri/Atk14/blob/master/src/atk14/helpers/modifier.h.php))

	Escapuje řídící znaky pro HTML. Obdoba PHP funkce [htmlspecialchars](http://php.net/manual/en/function.htmlspecialchars.php).

		{* blokové použití *}
		{h}You are logged in as {$user->getName()}{/h}

		{* použití jako modifikátor *}
		You are logged in as {$user->getName()|h}

	A teď něco velmi důležitého. Modifikátor **_h_ je standardně nastaven jako automatický modifikátor pro tisk veškerých hodnot** (viz konfigurační konstanta ATK14\_SMARTY\_DEFAULT\_MODIFIER).
	Helper _h_ je tím pádem nejpoužívanějším helperem a přitom ho v šablonách téměř neuvidíte.

		{* ve skutečnosti totiž zápis *}
		You are logged in as {$user->getName()}

		{* ve skutečnosti znamená *}
		You are logged in as {$user->getName()|h}

		{* pokud si defaultní escapování nepřejete, zapište *}
		You are logged in as {$user->getName() nofilter}

		{* nebo jednoduše použijte výkřičník *}
		You are logged in as {!$user->getName()}


* ### javascript_tag ([block](https://github.com/atk14/Atk14/blob/master/src/atk14/helpers/block.javascript_tag.php))

	Vytiskne řetězec v uvozovkách použitelný v javascriptových konstrukcích.

		{* file: app/users/edit.xhr.tpl *}
		{* jQuery notation *}
		$form.replaceWith({jstring}{render partial=form}{/jstring});

* ### t ([block](https://github.com/atk14/Atk14/blob/master/src/atk14/helpers/block.t.php))

	Gettext ve Smarty.

		{t name=$user->getName()}You are logged in as %1{/t}

		{t name=$user->getName()|h escape=no}You are logged in as <em>%1</em>{/t}

* ### trim ([block](https://github.com/atk14/Atk14/blob/master/src/atk14/helpers/block.trim.php))

    Smaže všechny bílé znaky na začátku a na konci obsahu.

* ### camelize ([modifier](https://github.com/atk14/Atk14/blob/master/src/atk14/helpers/modifier.camelize.php))
