Šablony
=======

V této kapitole pochopíte základní principy práce se šablonou. Pusťme se do toho.

Předpokládejme adresu

    http://www.atk14.net/cs/products/
    
která vede na akci *index* v kontroleru *products*.

V příslušné metodě jsou data pro šablonu připravována do pole ```$this->tpl_data```. Všimněte si, že titulek a popis stránky (page_title a page_description) se do pole $this->tpl_data nastavují.

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

Obsah vyrenderované šablony index.tpl bude zobrazen v layout šabloně, která může vypadat například takto.

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

Asi tušíte, že vyrenderovaná šablona index.tpl bude umístěna v layoutu do místa *{placeholder}*. Na místo řádku _{render partial="shared/layoutu/flash_message"}_ bude vložena daná sdílená šablona. O sdílených šablonách se dozvíte později.

Pokud tipujete, že toto jsou smarty šablony, nemýlíte se. Framework ATK14 šablonovací engine *[Smarty](http://www.smarty.net/)* skutečně používá a nebyl vybrán náhodou &mdash; Smarty je spolehlivý a lety prověřený nástroj, který odvádí spoustu dobré práce.
Pokud Smartyho vůbec neznáte, trošku se s ním seznamte v [dokumentaci](http://www.smarty.net/docs/en/), bude se vám to hodit.

Pro začátek to o šablonách stačí.
