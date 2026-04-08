Templates
=========

This chapter explains the basic principles of working with templates. Let's get started.

Assume the following URL:

```text
http://www.atk14.net/cs/products/
```

which leads to the *index* action in the *products* controller.

In the corresponding method, data for the template is prepared in the `$this->tpl_data` array. Note that the page title and description (`page_title` and `page_description`) are not set in `$this->tpl_data` — they have their own controller attributes.

```php
<?php
// file: app/controllers/products_controller.php
class ProductsController extends ApplicationController{
  function index(){
    $this->page_title = "List of Products";
    $this->page_description = "Brief list of our products, introducing product categories, shopping guides and many more...";

    $this->tpl_data["total_count"] = 123014;
    $this->tpl_data["opening"] = [
      "from" => "08:00",
      "to" => "18:00",
    ];
    $this->tpl_data["newest_products"] = Product::FindAll([
      "order_by" => "created_at DESC",
      "limit" => 10,
    ]);
  }
}
```

In the template the data is accessible like this.

```smarty
{* file: app/views/products/index.tpl *}
<h1>{$page_title}</h1>

<p>
  There are exactly {$total_count} products in our catalog.
  We wish you strong nerves when you browse.
</p>

<p>
  We are open from {$opening.from} to {$opening.to}.
  That's why we're a little bit sorry when you come too early or too late.
</p>

<h3>Newest Products</h3>

<p>Here are newest products from our catalog. Check them out.</p>

{render partial="newest_product_item" from=$newest_products item=product}
```

This template uses a partial template `newest_product_item`, over which the `$newest_products` array is iterated. For clarity, partial templates are placed in files whose names begin with an underscore.

```smarty
{* file: app/views/products/_newest_product_item.tpl *}
<h4>{a action="detail" id=$product->getId()}{$product->getName()}{/a}</h4>

<p>{$product->getDescription()}</p>
```

You'll learn more about partial templates later.

The rendered content of the `index.tpl` template will be displayed inside a layout template, which might look like this.

```smarty
{* file: app/layouts/default.tpl *}
<html>
  <head>
    <title>{$page_title} | Flying Circus Company</title>
    <meta name="description" content="{$page_description}"></meta>
  </head>
  <body>
    {render partial="shared/layout/flash_message"}
    {placeholder}
  </body>
</html>
```

You've probably guessed that the rendered `index.tpl` content will be placed into the layout at the `{placeholder}` position. The `{render partial="shared/layout/flash_message"}` line inserts the given shared partial template, which handles displaying flash messages. You'll learn what flash messages are useful for later.

If you're guessing that these are Smarty templates, you're right. The ATK14 framework does indeed use the *[Smarty](http://www.smarty.net/)* templating engine, and it wasn't chosen by accident — Smarty is a reliable, battle-tested tool that does a lot of good work.
If you're not familiar with Smarty at all, it's worth taking a quick look at the [documentation](http://www.smarty.net/docs/en/).

That's enough about templates for now.
