Helpers
=======

Helpers are functions used in templates that assist with displaying values, accessing data, and simplifying templates.

Since ATK14 uses the [Smarty](http://www.smarty.net/) templating system, you have access to all Smarty helpers.
ATK14 itself also provides its own set of helpers (see the `atk14/src/atk14/helpers/` directory).
And finally, you can write your own specialised helpers for your application — that's what the `app/helpers/` directory is for.

----------------------------------------------------------------------------------------------------------------------------------

ATK14 brings its own set of functions and helpers that extend Smarty's capabilities.
A pair worth special attention is *{placeholder}* and *{content}*.

Imagine a layout like this.

```smarty
{* file: app/layouts/default.tpl *}

<html>
  <head>
    <title>{$page_title}</title>
  </head>
  <body>

    <div id="navigation">
      <h3>Navigation</h3>
      <ul id="navigation">
        <li><a href="/">Home</a></li>
        {placeholder for=other_navigation_items}
      </ul>
    </div>

    {render partial="shared/layout/flash_message"}
    {placeholder}
  </body>

</html>
```

Now consider a product detail template.

```smarty
{* file: app/views/products/detail.tpl *}

<h1>{$product->getTitle()}</h1>
{$product->getDescription()}

{content for=other_navigation_items}
  <li><a href="{$product->getManualUrl()}">Download the product manual</a></li>
{/content}
```

What will be rendered? The product information will appear in *{placeholder}* and an additional link to download the manual for the currently viewed product will be injected into the navigation — outside the main content area.

Even more important is the *{render}* helper. Among other things, it replaces Smarty's way of including sub-templates via *{include}*. In any well-written ATK14 application you won't find a single
{include} (note, however, that this is a necessary condition, not a sufficient one).

Notice that *{render}* is already used in the layout: `{render partial=shared/layout/flash_message}`. In this case the content of the template `app/views/shared/_flash_message.tpl` will be inserted at that position.
Partial templates start with an underscore and have the `.tpl` extension — neither the underscore nor the extension is specified in the `partial` parameter.

The *{render}* helper also replaces Smarty's *{foreach}*. Let's see this in action on a product listing.

```smarty
{* file: app/views/products/index.tpl *}

<h1>Product listing</h1>

<table>
  <thead>
    <tr>
      <th>Name</th>
      <th>Price</th>
    </tr>
  </thead>
  <tbody>
    {render partial=product_item from=$products item=product}
  </tbody>
</table>
```

Each table row is then rendered by the following partial template.

```smarty
{* file: app/views/products/_product_item.tpl *}

<tr>
  <td>{$product->getTitle()}</td>
  <td>{$product->getPrice()}</td>
</tr>
```

If using partial templates instead of *{foreach}* seems pointless to you, you're probably thinking about it quite a lot. Know, however, that partial templates are great and support the DRY principle.

There is no point in elaborating further on templates right now — we don't want your head to hurt.
