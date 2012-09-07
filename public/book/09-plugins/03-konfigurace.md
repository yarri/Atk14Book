Konfigurace pluginu
===========================

Pokud plugin potřebuje konfigurační soubory, lze je uložit do adresáře 
  
    <docroot>/plugins/<plugin\_name>/config/ 
  
Metoda pluginu 

    $plugin->getConfigFile($fileName='', $include\_plugin\_name=true)

vytvoří jméno konfiguračního souboru dle daných pravidel
 
 - pokud je $include\_plugin\_name false, $fileName se nechá jak je 
 - pokud je $include\_plugin\_name true a jméno souboru začíná lomítkem, jméno souboru bude &lt;název\_pluginu&gt;/$fileName
 - pokud je $include\_plugin\_name true a jméno souboru nezačíná lomítkem, považuje se za příponu, tedy jméno souboru bude mít tvar jméno souboru bude &lt;název\_pluginu&gt;.$fileName
 - pokud je $include\_plugin\_name true a jméno souboru prázdné, jméno souboru bude &lt;název\_pluginu&gt;

Takto pojmenovaný soubor pak se nejprve hledá v &lt;docroot&gt;/config/ a následně v 
&lt;docroot&gt;/plugins/&lt;plugin_name&gt;/config/ a vrátí filename prvního, které nalezne. 
Proto je možné v pluginu dodávat standardní konfiguraci a změnu konfigurace může 
vývojář provést snadno bez zásahu do samotného pluginu: zkopíruje jeho konfigurační
souvbor do &lt;docroot&gt;/config/ a tam ho upraví.


