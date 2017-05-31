Helpery
=======

Helpery jsou funkce používané v šablonách, které pomáhají při zobrazovaní hodnot, v přístupu k datům a při zjednodušování šablon.

Jelikož ATK14 používá šablonovací systém [Smarty](http://www.smarty.net/), máte k dispozici všechny helpery ze Smarty.
Samotné ATK14 pak obsahuje svou sadu helperů (viz adresář atk14/src/atk14/helpers/).
No a konečně do své aplikace si můžete psát své specializované helpery - na to je adresář app/helpers/

----------------------------------------------------------------------------------------------------------------------------------

ATK14 přináší sadu vlastních funkcí a pomocníků (helperů), které rozšiřují možnosti Smarty.
Za velkou pozornost stojí dvojce funkcí *{placeholder}* a *{content}*.

Přadstavte si takovýto layout.

    {* file: app/layouts/default.tpl *}
    
    <html>
      <head>
        <title>{$page_title}</title>
      </head>
      <body>

        <div id="navigation">
          <h3>Rozcestník</h3>
          <ul id="navigation">
            <li><a href="/">Hlavní stránka</a></li>
            {placeholder for=other_navigation_items}
          </ul>
        </div>

        {render partial="shared/layout/flash_message"}
        {placeholder}
      </body>

    </html>

Teď uvažujme o šabloně detailu produktu.

    {* soubor app/views/products/detail.tpl *}

    <h1>{$product->getTitle()}</h1>
    {$product->getDescription()}

    {content for=other_navigation_items}
      <li><a href="{$product->getManualUrl()}">Stáhněte si manuál k produktu</a></li>
    {/content}

Co bude zobrazeno? Informace o produtu budou zobrazeny v *{placeholder}* a do navigace, tedy mimo hlavní obsah, bude přidán další odkaz pro stažení manuálu právě prohlíženého produktu.

Ještě důležitější je však helper *{render}*. Ten mimo jiné nahrazuje Smartyho způsob nahravání podšablon pomocí *{include}*. V žádné pořádné ATK14 aplikaci vlastně nenajdete ani jedno
{include} (uvědomte si však, že to je podmínka nutná, nikoli dostačující).

Všimněte si, že už v layoutu již {render} použit je: {render partial=shared/layout/flash_message}. V tomto případě bude do daného místa vložen obsah šablony app/views/shared/\_flash\_message.tpl.
Platí, že parciální šablony začínají podtržítkem a mají příponu tpl. Ani podtržítko, ani příponu však v parametru partial neuvádíme.

Helper {render} však nahrazuje i Smartyho {foreach}. To si ukážeme na zobrazení přehledu produktů.

    {* soubor app/views/products/index.tpl *}

    <h1>Přehled produktů</h1>
  
    <table>
      <thead>
        <tr>
          <th>Název</th>
          <th>Cena</th>
        </tr>
      </thead>
      <tbody>
        {render partial=product_item from=$products item=product}
      </tbody>
    </table>

Každý řádek tabulky pak vykreslíme pomocí nasledující parciální šablony.

    {* soubor app/views/products/_product_item.tpl *}
    
    <tr>
      <td>{$product->getTitle()}</td>
      <td>{$product->getPrice()}</td>
    </tr>

Pokud se vám zdá, že používání parciálních šablon místo {foreach} postrádá smysl, asi o tom docela dost přemýšlíte. Vězte však, že parciální šablonky jsou fajn a podporují princip DRY.

Víc se teď nemá cenu o šablonách rozepisovat, ať vás nebolí hlava.
