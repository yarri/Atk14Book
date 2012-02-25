Filosofie přístupu k podobě URL
===============================

ATK14 umožňuje definovat vzhled adres URL vedoucích na konkrétní akce tak, aby byly sympatické, líbívé a splňovaly bláznivé požadavky různých SEO a SEF teorií.

Nicméně v počátcích vývoje aplikace se programátor o tvar URL nestará a pokorně se spokojí s výchozím vzhledem. Raději se věnuje smysluplnému uspořádání kontrolerů a jejich akcí a
v šablonách veškeré odkazy generuje zásadně pomocí helperů.

	{* file: app/views/shared/_navigation.tpl *}

	<h4>Navigation</h4>
	<ul>
		<li>{a controller=main action=index}Homepage{/a}</li>
		<li>{a controller=main action=about_us}About Us{/a}</li>
		<li>{a controller=main action=contact}Contact{/a}</li>
		<li>{a controller=sitemap action=index}Sitemap{/a}</li>
		<li>{a controller=sitemap action=index format=xml}Sitemap in XML{/a}</li>
	</ul>

Taková šablonka vygeneruje na základě výchozích pravidel pro sestavovaní URL následující výstup.

	<h4>Navigation</h4>
	<ul>
		<li><a href="/">Homepage</a></li>
		<li><a href="/en/main/about_us/">About Us</a></li>
		<li><a href="/en/main/contact/">Contact</a></li>
		<li><a href="/en/sitemap/">Sitemap</a></li>
		<li><a href="/en/sitemap/?format=xml">Sitemap in XML</a></li>
	</ul>

Aplikace si několik dnů spokojeně pobrukuje v beta režimu a pak se objeví požadavek na změnu některých adres. Kýžený stav je následující.

	<h4>Navigation</h4>
	<ul>
		<li><a href="/">Homepage</a></li>
		<li><a href="/company/about-us/">About Us</a></li>
		<li><a href="/company/contact/">Contact</a></li>
		<li><a href="/sitemap/">Sitemap</a></li>
		<li><a href="/sitemap.xml">Sitemap in XML</a></li>
	</ul>


Taková změna pochopitelně v ATK14 možná je - jinak by zde nebyla vůbec zmiňována :) A velmi radostná zpráva je, že se do hotového kódu už nezasahuje.

V našem příkladě si vystačíme s jedním statickým routerem.

	<?php
	// file: config/routers/seo_router.php
	class SeoRouter extends Atk14Router{
		function setUp(){
			$this->addRouter("/company/about-us/","main/about_us");
			$this->addRouter("/company/contact/","main/contact");
			$this->addRouter("/sitemap/","sitemap/index");
			$this->addRouter("/sitemap.xml","sitemap/index",array("format" => "xml"));
		}
	}

Více je statickým routerům věnováno v následující kapitole.

Když už si někdo takto pěkně upraví některá URL, ATK14 zajistí automatické přesměrování na novou adresu ve chvíli, kdy někdo navštíví URL v původním tvaru (např. z cache vyhledávače).
Toto automatické přesměrování si můžete vyzkoušet například návštěvou této stránky <http://www.atk14.net/en/books/detail/?id=34&format=xml>. Pěkné, že? Pro úplnost prozradíme, že se o tuto adresu stará
dynamický router, který do adresy doplňuje název knihy.
