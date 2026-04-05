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

```php
<?php
// file: config/routers/books_router.php
/**
 * Produces routes like:
 * 	/book/16-british-humanities-index
 * 	/book/16-british-humanities-index.xml
 * 
 * czech variants:
 * 	/kniha/16-british-humanities-index
 * 	/kniha/16-british-humanities-index.xml
 */
class BooksRouter extends Atk14Router{

  function recognize($uri){
    if(preg_match('/^\/(book|kniha)\/([0-9]+)-([^.]*)(|\.([a-z]+))$/',$uri,$matches)){
      if(!$book = Book::GetInstanceById($matches[2])){
        $this->_not_found();
        return;
      }

      $this->controller = "books";
      $this->action = "detail";

      $this->lang = $matches[1]=="book" ? "en" : "cs";
      $this->params->add("id",$book);
      $slug = $matches[3];
      if(isset($matches[5])){
        $this->params->add("format",$matches[5]);
      }

      // raise a redirection when the book`s title has been changed
      if($slug!=$book->getSlug()){
        $this->_redirect_to($this->_build_book_link($book));
      }
    }
  }

  function build(){
    if($this->controller!="books"){ return; }

    if($this->action=="detail" && ($book = Book::GetInstanceById($this->params->getInt("id")))){
      return $this->_build_book_link($book);
    }
  }

  function _build_book_link($book){
    $label = $this->lang=="cs" ? "kniha" : "book";
    if($format = $this->params->g("format")){ $format = ".$format"; }

    $this->params->del("format");
    $this->params->del("id");

    return sprintf('/%s/%s-%s%s',$label,$book->getId(),$book->getSlug(),$format);
  }
}
```

Metoda _recognize()_ rozpoznává příchozí URI. Jestliže router usoudí, že dané URI obsluhuje, nastaví všechny potřebné vlastnosti (controller, action, lang a params).
V opačném případě nenastavuje nic a ATK14 pak předá URI k rozpoznání dalšímu routeru v pořadí. Metoda _recognize()_ přijímá parametr _$uri_. Hodnota předaná v tomto parametru vznikla zeštíhledním skutečného URI. Uvažujte tuto adresu, ...

	http://myapp.localhost/admin/en/articles/edit/?id=123

... kde _admin_ je _namespace_. Pak hodnota předaná parametrem $uri bude

	/en/articles/edit/

Parametr _id_ naleznete v členské vlastnosti _$this->params_ a název _namespace_ v _$this->namespace_.

Metoda _build()_ slouží k sestavení URI podle parametrů. Router zase sám musí správně detekovat, že podle veškerých parametrů dokáže URI sestavit.
Pokud _build()_ nic nevrátí, bude ATK14 volat metodu _build()_ na dalším routeru v pořadí. Očekává se, že hodnota vrácena metodou _build()_ bude stejně zeštíhlené URI, jaké je předáno do metody _recognize()_.

V principu jsou dynamické routery jednoduché automaty, se kterýma si zažijete spoustu legrace.
