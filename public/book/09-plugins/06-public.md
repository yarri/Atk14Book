Adresář public
=======================
V adresáři
    &lt;docroot&gt;/plugins/&lt;plugin_name&gt;/public
mohou být umístěny soubory, ke kterým má být z prohlížeče přímý přístup (javascript, css).

Pokud plugin vyžaduje některou javaskriptovou knihovnu, doporučuje se užít metody pluginu
    
    function registerScript(scriptFileName)
    
tato metoda se podívá do &lt;docroot&gt;/public/javascripts, zdali tam není soubor daného jména
a je-li tam, zaregistruje pomocí třídy Atk14AppHeaders tento skript, v opačném případě
zaregsitruje soubor z adresáře &lt;docroot&gt;/plugins/&lt;plugin_name&gt;/public. Tím lze zabránit, aby
jedna knihovna nebyla načítána vícekrát z různých umístění, popř. aby byla takováto knihovna 
inicializovaná vícekrát atd.... 

Pokud tuto metodu použijete, nezapomeňte do layoutu aplikace do HEAD sekce vložit 
helper
    {appheaders}
který zajistí vložení takto zaregistrovaných skriptů. 
