Práce se šablonou
=================

V kontroleru přiravíme pro šablonu data následovně.

    <?php
    // soubor ./app/controllers/products_controller.inc
    ProductsController extends ApplicationController{
      function index(){
        $this->tpl_data["total_count"] = 123014;
        $this->tpl_data["opening"] = array(
          "from" => "8:00",
          "till" => "18:00",
        )
      }
    }

V šabloně jsou data dostupná takto.

    {* soubor ./app/views/products/index.tpl *}
    
    <h1>Vítejte v našem katalogu</h1>
    
    <p>
      V našem katalogu je přesně {$total_count} produktů.
      Přejeme vám pevné nervy při jeho procházení.
    </p>

    <p>
      Máme otevřeno od {$opening.from} do {$opening.till}.
      Proto je nám líto, když přijdete jindy.
    </p>

Pokud tipujete, že toto je [Smarty](http://www.smarty.net/) šablona, máte pravdu. ATK14 používá neupravenou knihovnu Smarty ve verzi 2.6.
