Vytvoření pluginu
===============================

Pro vytvoření pluginu stačí udělat čtyři věci:

1. Zvolit jméno pluginu (v našich případech např. gin), nech+
2. Vytvořit adresář <docroot>/plugins/<jméno pluginu>
3. Vytvořit soubor s pluginem <docroot>/plugins/<jméno pluginu>/<jméno pluginu>_plugin.php

Vytvoření adresáře repozitáře
-------------------------------
Pokud chcete mít plugin v samostatném repozitáři, místo kroku 2 vytvořte někde
(např. na githubu) repozitář a přidejte ho do aplikace následujícím příkazem

    git submodule add <adresa repozitáře na serveru> plugins/<jméno_pluginu>

  
Vytvoření souboru pluginu
-------------------------------
V souboru pluginu <docroot>/plugins/<jméno pluginu>/<jméno pluginu>_plugin.php
musí být třída <jméno pluginu>Plugin poděděná z Atk14Plugin

    class <jméno pluginu>Plugin extends Atk14Plugin
      {
      }

Úprava .htaccess
-------------------------------
Pokud je to první plugin v aplikaci, je možné, že budete mít v .htaccess (popř. v konfigurátoru
apache) nastaveno špatně rewrite engine. Pokud tam je podmínka pro public adresáře pouze ve tvaru 

    RewriteCond %{REQUEST_URI} !^\/public\/
  
přidejte tam podmínku
  
    RewriteCond %{REQUEST_URI} !^\/plugins/\w+/public\/

popř. - pokud v aplikaci neexistuje kontrolér ani plugin s názvem public, lze místo přidání podmínky výše podmínku nahradit podmínkou   

    RewriteCond %{REQUEST_URI} !\/public\/
  
    
Další práce s pluginem
-------------------------------
Nyní je hotová kostra pluginu a lze začít psát samotný plugin. Pokud pouze chcete oddělit
z aplikace nějakou část do nově vzniklého pluginu, je to velmi jednoduché: přesuňte soubory
náležící pluginu z podadresářů adresáře <docroot>/app a <docroot>/public do patřičných adresářů 
v podadresáři pluginu:
tzn např. u pluginu gin patří kontrolér gin\_controller.php do adresáře 
    <docroot>/plugins/gin/controllers/gin\_controller.php
  
 
