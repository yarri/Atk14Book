URL design philosophy
=====================

ATK14 lets you define the appearance of URLs pointing to specific actions so that they look friendly, attractive, and satisfy the requirements of various SEO and SEF theories.

In the early stages of development, however, you don't worry about URL appearance at all — you humbly accept the default format, focus on a sensible organisation of controllers and actions, and always generate links in templates using helpers.

	{* file: app/views/shared/_navigation.tpl *}

	<h4>Navigation</h4>
	<ul>
		<li>{a controller=main action=index}Homepage{/a}</li>
		<li>{a controller=main action=about_us}About Us{/a}</li>
		<li>{a controller=main action=contact}Contact{/a}</li>
		<li>{a controller=sitemap action=index}Sitemap{/a}</li>
		<li>{a controller=sitemap action=index format=xml}Sitemap in XML{/a}</li>
	</ul>

Based on the default URL building rules, that template generates the following output.

	<h4>Navigation</h4>
	<ul>
		<li><a href="/">Homepage</a></li>
		<li><a href="/en/main/about_us/">About Us</a></li>
		<li><a href="/en/main/contact/">Contact</a></li>
		<li><a href="/en/sitemap/">Sitemap</a></li>
		<li><a href="/en/sitemap/?format=xml">Sitemap in XML</a></li>
	</ul>

The application hums along happily in beta for a few days, and then a request arrives to change some of the URLs. The desired result is:

	<h4>Navigation</h4>
	<ul>
		<li><a href="/">Homepage</a></li>
		<li><a href="/company/about-us/">About Us</a></li>
		<li><a href="/company/contact/">Contact</a></li>
		<li><a href="/sitemap/">Sitemap</a></li>
		<li><a href="/sitemap.xml">Sitemap in XML</a></li>
	</ul>


This kind of change is of course possible in ATK14 — otherwise it wouldn't be mentioned at all :) And the great news is that none of the existing controller, template, or model code needs to be touched.

In our example, a single static router is all we need.

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

Static routers are covered in more depth in the next chapter.

Once you've tidied up your URLs this nicely, ATK14 will automatically redirect to the new address whenever someone visits an old-format URL (e.g. from a search engine cache).
You can try this automatic redirect by visiting <http://www.atk14.net/en/books/detail/?id=34&format=xml>. Nice, isn't it? For completeness: that URL is handled by a dynamic router that appends the book title to the URL.
