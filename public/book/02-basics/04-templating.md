Šablony
=======

V této kapitole pochopíš základní principy práce se šablonou. Pusťme se do toho.

Předpokládejme adresu:

    http://www.atk14.net/cs/products/

která vede na akci *index* v kontroleru *products*.

V příslušné metodě jsou data pro šablonu připravována do pole `$this->tpl_data`. Všimni si, že titulek a popis stránky (`page_title` a `page_description`) se do `$this->tpl_data` nenastavují — mají vlastní atributy kontroleru.

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

V šabloně jsou data dostupná takto.

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

V této šabloně je použita parciální (dílčí) šablona `newest_product_item`, nad kterou je iterováno pole `$newest_products`. Pro přehlednost jsou parciální šablony umísťovány do souboru začínajícího znakem podtržítko.

    {* file: app/views/products/_newest_product_item.tpl *}
    <h4>{a action="detail" id=$product->getId()}{$product->getName()}{/a}</h4>

    <p>{$product->getDescription()}</p>

Více o parciálních šablonách se dozvíš později.

Obsah vyrenderované šablony `index.tpl` bude zobrazen v layoutové šabloně, která může vypadat například takto.

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

Asi tušíš, že vyrenderovaná šablona `index.tpl` bude umístěna v layoutu do místa `{placeholder}`. Na místo řádku `{render partial="shared/layout/flash_message"}` bude vložena daná sdílená parciální šablona, která zajistí zobrazení flash zprávy. Později se dozvíš, k čemu se flash zprávy hodí.

Pokud tipuješ, že toto jsou Smarty šablony, nemýlíš se. Framework ATK14 šablonovací engine *[Smarty](http://www.smarty.net/)* skutečně používá a nebyl vybrán náhodou — Smarty je spolehlivý a lety prověřený nástroj, který odvádí spoustu dobré práce.
Pokud Smarty vůbec neznáš, trošku se s ním seznam v [dokumentaci](http://www.smarty.net/docs/en/), bude se ti to hodit.

Pro začátek to o šablonách stačí.
