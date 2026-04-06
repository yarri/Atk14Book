Dynamic routers
===============

A dynamic router is a subclass of _Atk14Router_ that contains the _recognize()_ and _build()_ methods.
The router source file must be placed in the _config/routers/_ directory and loaded into the application in _config/routers/load.php_.

A dynamic router is far more sophisticated than a static one. While a static router merely rearranges characters in a URL, a dynamic router can, for example, include the article title in the URL — something SEO wizards dream about.

Know that if we were satisfied with this from the static router, ...

	<!-- english variant -->
	<a href="/article-123.html">Some very nice article about ATK14`s routing</a>

	<!-- czech variant -->
	<a href="/clanek-123.html">Velmi zdařilý článek o routování v ATK14</a>

	<!-- finnish  variant -->
	<a href="/artikkeli-123.html">Jotkut erittäin mukava juttu ATK14 `s reititys</a>

... with a dynamic router we are able to achieve something like this...

	<!-- english variant -->
	<a href="/article/some-very-nice-article-about-atk14s-routing.html">Some very nice article about ATK14`s routing</a>

	<!-- czech variant -->
	<a href="/clanek/velmi-zdarily-clanek-o-routovani-v-atk14.html">Velmi zdařilý článek o routování v ATK14</a>

	<!-- finnish variant -->
	<a href="/artikkeli/jotkut-erittain-mukava-juttu-atk14s-reititys.html">Jotkut erittäin mukava juttu ATK14 `s reititys</a>

... and again none of the existing code needs to be touched.

If you think about the example above, you'll realise that it only works reliably in practice if the titles of all articles are completely unique within a given language. Also, if you change the title of an already published article, the URL with the original title will no longer lead anywhere.

Take a look at the source of the sample router from <http://www.atk14.net/en/books/>, which keeps the book _id_ in the URL as well, for safety. This allows it to trigger an automatic redirect when the book's title has changed.

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

The _recognize()_ method recognises an incoming URI. If the router determines that it handles the given URI, it sets all the necessary properties (controller, action, lang, and params).
Otherwise it sets nothing and ATK14 passes the URI to the next router in line. The _recognize()_ method receives a _$uri_ parameter. The value passed in this parameter is a trimmed version of the actual URI. Consider this address, ...

	http://myapp.localhost/admin/en/articles/edit/?id=123

... where _admin_ is the _namespace_. The value passed as $uri will then be:

	/en/articles/edit/

The _id_ parameter is found in the `$this->params` member property and the namespace name in `$this->namespace`.

The _build()_ method is used to build a URI from parameters. The router must correctly detect on its own whether it can build the URI from the given parameters.
If _build()_ returns nothing, ATK14 will call the _build()_ method on the next router in line. The value returned by _build()_ is expected to be the same trimmed URI format as is passed to the _recognize()_ method.

In principle, dynamic routers are simple automata — and you'll have a lot of fun with them.
