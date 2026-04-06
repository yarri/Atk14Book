Static routers
==============

A static router is a subclass of _Atk14Router_ that contains a _setUp()_ method in which it calls _addRoute()_ at least once.
The router source file must be placed in the _config/routers/_ directory and loaded into the application in _config/routers/load.php_.

A static router can only make formal changes to the appearance of a URL. It cannot add anything to a URL that it doesn't already have available at the time of building.

Consider the fairly realistic case of an article detail URL.

	{* a partial template *}
	{a controller=articles action=detail id=$article}{$article->getTitle()|h}{/a}

The above code can generate the following HTML.
	
	<a href="/en/articles/detail/?id=123">Some very nice article about ATK14`s routing</a>

If you want to change the article detail URL to look like this, ...

	<!-- english variant -->
	<a href="/article-123.html">Some very nice article about ATK14`s routing</a>

	<!-- czech variant -->
	<a href="/clanek-123.html">Velmi zdařilý článek o routování v ATK14</a>

... use a static router.

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

Sharp eyes will have spotted the use of regular expressions to detect the number in the URL. And with regular expressions, a static router is far from toothless.

Now imagine that you later add a Finnish language version to the application but forget to add Finnish URL support to the router. What happens?
Links to articles in Finnish will be built using the generic rule.

	<!-- english variant -->
	<a href="/article-123.html">Some very nice article about ATK14`s routing</a>

	<!-- czech variant -->
	<a href="/clanek-123.html">Velmi zdařilý článek o routování v ATK14</a>

	<!-- finnish  variant -->
	<a href="/fi/articles/detail/?id=123">Jotkut erittäin mukava juttu ATK14 `s reititys</a>

It is quite likely that search engines will index this generic form of "Finnish" URLs. But will these generic URLs still work after you add the Finnish form to the router?
Of course they will! And on top of that, visiting them will trigger an automatic redirect to the new form with the HTTP code _301 Moved Permanently_, guiding search engines to the new URLs completely without any further effort on your part.

First come, first served
------------------------

When building links, ATK14 iterates through all the rules and uses the first one that fits the given case. Unfortunately this is a moment that can cause quite a headache. The previous chapter includes the following router as an example.

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

It is very important to realise here what would happen if the last 2 rules were swapped. Bad things would start happening. The following code...

	{* a template snippet *}
	{a controller=sitemap action=index format=xml}Sitemap in XML{/a}

... would generate this output...

	<a href="/sitemap/?format=xml">Sitemap in XML</a>

... and the headache slowly begins. In reality the last rule would never be used.

Friendly advice
---------------

Put off worrying about URL appearance for as long as possible. Focus on development. Deploy the application to production as soon as possible. Collect enough data in Google Analytics.
Only then start thinking about which addresses are worth making prettier for users or search engines.

Default router
--------------

Every application has a default router that defines the generic URL pattern. This router must be placed last in _config/routers/load.php_.

```php
<?php
// file: config/routers/default_router.php
/**
 * Generic routes
 */
class DefaultRouter extends Atk14Router {

  function setUp(){

    $this->addRoute("/",array(
      "lang" => $this->default_lang,
      "path" => "main/index",
      "title" => ATK14_APPLICATION_NAME,
      "description" => "The ATK14 Project website; ATK14 is a PHP framework for fearless guys",
    ));

    $this->addRoute("/<lang>/",array(
      "path" => "main/index"
    ));

    $this->addRoute("/<lang>/<controller>/",array(
      "action" => "index"
    ));

    $this->addRoute("/<lang>/<controller>/<action>/");
  }
}
```

Usually there is no need to modify this router. However, there are situations where you'll appreciate the ability to change the generic rules — for example when building a single-language application where the language code doesn't need to appear in the URL at all.
