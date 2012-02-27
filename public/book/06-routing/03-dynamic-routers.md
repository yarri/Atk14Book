Dynamické routery
=================

Dynamický router je dědic třídy _Atk14Router_, který obsahuje metody _recognize()_ a _build()_.
Soubor ze zdrojovým kódem routeru musí být umístěn v adresáři _config/routers/_ a do aplikace musí být nahrán v souboru _config/routers/load.php_.

Dynamický router je mnohem rafinovanější než router statický. Zatímco statický router v URL pouze přehazuje písmenka, v moci dynamického routeru je např. i
dopnit do URL titulek článku, po čemž baží SEO kouzelníci.

Vězte, že pokud jsme se u statického routeru spokojili s tímto, ...

	<!-- english variant -->
	<a href="/article-123.html">Some very nice article about ATK14`s routing</a>

	<!-- czech variant -->
	<a href="/clanek-123.html">Velmi zdařilý článek o routování v ATK14</a>

	<!-- finnish  variant -->
	<a href="/artikkeli-123.html">Jotkut erittäin mukava juttu ATK14 `s reititys</a>

... s dynamickým routerem jsme schopni dosáhnout něčeho takového...

	<!-- english variant -->
	<a href="/article/some-very-nice-article-about-atk14s-routing.html">Some very nice article about ATK14`s routing</a>

	<!-- czech variant -->
	<a href="/clanek/velmi-zdarily-clanek-o-routovani-v-atk14.html">Velmi zdařilý článek o routování v ATK14</a>

	<!-- finnish variant -->
	<a href="/artikkeli/jotkut-erittain-mukava-juttu-atk14s-reititys.html">Jotkut erittäin mukava juttu ATK14 `s reititys</a>

... a opět se vše obejde bez potřeby úpravy již hotového kódu.

Pokud se zamyslíte nad uvedeným příkladem, určitě si uvědomíte, že tento může být v praxi úspěšně použitelný pouze v případě,
že nadpisy všech článku budou v daném jazyce zcela unikátní. A dále bude platit, že pokud v průběhu času změníte nadpis již
publikovaného článku, nepovede již URL s původním nadpisem na žádný článek.

Podívejte se na zdrojový kód vzorového routeru z <http://www.atk14.net/en/books/>, který pro jistotu v URL nechává i _id_ knihy. A dokáže tak vyvolat automatické
přesměrování v případě, že se název knihy změnil.

Include config/routers/books_router.php

Metoda _recognize()_ rozpoznává příchozí URI. Jestliže router usoudí, že dané URI obsluhuje, nastaví všechny potřebné vlastnosti (controller, action, lang a params).
V opačném případě nenastavuje nic a ATK14 pak předá URI k rozpoznání dalšímu routeru v pořadí.

Metoda _build()_ slouží k sestavení URI podle parametrů. Router zase sám musí správně detekovat, že podle veškerých parametrů dokáže URI sestavit.
Pokud _build()_ nic nevrátí, bude ATK14 volat metodu _build()_ na dalším routeru.

V principu jsou dynamické routeru jednoduché automaty, se kterýma je legrace.
