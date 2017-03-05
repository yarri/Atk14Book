Statické routery
================

Statický router je dědic třídy _Atk14Router_, který obsahuje metodu _setUp()_, ve které volá alespoň jednou metodu _addRoute()_.
Soubor ze zdrojovým kódem routeru musí být umístěn v adresáři _config/routers/_ a do aplikace musí být nahrán v souboru _config/routers/load.php_.

Pomocí statického routeru je možné realizovat pouze formální změnu podoby adresy URL. Statický router není schopen do adresy přidat něco, co nemá v okamžiku sestavování při ruce.

Uvažujte poměrně reálný případ s adresou detailu článku.

	{* a partial template *}
	{a controller=articles action=detail id=$article}{$article->getTitle()|h}{/a}

Uvedený kód může vygenerovat následující HTML kód.
	
	<a href="/en/articles/detail/?id=123">Some very nice article about ATK14`s routing</a>

Pokud chcete realizovat změnu URL detailu článku tímto způsobem, ...

	<!-- english variant -->
	<a href="/article-123.html">Some very nice article about ATK14`s routing</a>

	<!-- czech variant -->
	<a href="/clanek-123.html">Velmi zdařilý článek o routování v ATK14</a>

... použijte s úspěchem statický router.

	<?php
	// file: config/routers/articles_router.php

	class ArticlesRouter extends Atk14Router{
		function setUp(){
			$this->addRoute("/article-<id>.html","en/articles/detail",array(
				"id" => "/[0-9]+/"
			));
			$this->addRoute("/clanek-<id>.html","cs/articles/detail",array(
				"id" => "/[0-9]+/"
			));
		}
	}

Bystří určitě rozpoznali použití regulárního vyrazů pro detekci čísla v URL. A s regulárními výrazy se statický router už nemůže jevit jako bezzubé ořezávátko.

Představte si teď situaci, že časem aplikaci rozšíříte o finskou jazykovou verzi, ale do routeru podporu finských URL nedoplníte. Co se stane?
Odkazy na články ve finštině budou sestaveny podle obecného pravidla.

	<!-- english variant -->
	<a href="/article-123.html">Some very nice article about ATK14`s routing</a>

	<!-- czech variant -->
	<a href="/clanek-123.html">Velmi zdařilý článek o routování v ATK14</a>

	<!-- finnish  variant -->
	<a href="/fi/articles/detail/?id=123">Jotkut erittäin mukava juttu ATK14 `s reititys</a>

Celkem pravděpodobně dojde k tomu, že vyhledávače zaindexují tento generický tvar "finských" URL. Budou však tato generická URL fungovat poté, co doplníme finský tvar do routeru?
Samozřejmě že budou! A navíc na nich dojde k automatickému přesměrování na nový tvar s HTTP kódem _301 Moved Permanently_, což vyhledávače navede na novou podobu adres zcela bez našeho dalšího přičinění.

Kdo dřív příjde, ten dřív mele
------------------------------

ATK14 při sestavování odkazů postupně prochází všechna pravidla a použije to první, které se pro daný případ hodí. To je bohužel moment, který může přivodit pěkné bolení hlavy. V předchozí kapitole uvádíme příklad následujícího routeru.

	<?php
	// file: config/routers/seo_router.php
	class SeoRouter extends Atk14Router{
		function setUp(){
			$this->addRoute("/company/about-us/","main/about_us");
			$this->addRoute("/company/contact/","main/contact");
			$this->addRoute("/sitemap.xml","sitemap/index",array("format" => "xml"));
			$this->addRoute("/sitemap/","sitemap/index");
		}
	}

Tady je velmi důležite uvědomit si, co by se stalo, kdyby byly poslední 2 pravidla přehozena. Začnou se dít nedobré věci. Následující kód...

	{* a template snippet *}
	{a controller=sitemap action=index format=xml}Sitemap in XML{/a}

... by vygeneroval tento výstup...

	<a href="/sitemap/?format=xml">Sitemap in XML</a>

... a bolení hlavy se pomalu rozjede. Ve skutečnosti by totiž poslední pravidlo nikdy nebylo použito.

Přátelské doporučení
--------------------

Odložte starost o podobu URL na co možná nejpozději. Věnujte se vývoji. Nasaďte co možná nejrychleji aplikaci do produkce. Nasbírejte dostatek podkladových dat do Google Analytics.
A až pak si začněte promýšlet, které adresy stojí za to, aby vypadaly lépe pro uživatele nebo vyhledávače.

Výchozí router
--------------

V každé aplikaci naleznete výchozí router, který definuje obecný model URL. Tento router musí být zařazen v souboru _config/routers/load.php_ na posledním místě.

[Include config/routers/default_router.php]

Obvykle není třeba tento router upravovat. Jsou však situace, kdy možnost změny obecných pravidel uvítáte - např. ve chíli když vytváříte pouze jednojazyčnou aplikaci,
není pořeba kód jazykové verze v URL uvádět vůbec.



