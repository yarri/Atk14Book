Adresářová struktura pluginu
===============================================

Plugin může mít tyto podadresáře

* config : konfigurace
* controllers : kontroléry
* views : pohledy
* models : modely
* helpers : helpery
* public: obsah přístupný pro webserver přímo (css, javascript atd...)

obsah adresářů je rozebírán v patřičných kapitolách. Obecné pravidlo platí, že 
soubory jsou na stejném místě jako soubory v adresářích 
_&lt;docroot&gt;/app_, _&lt;docroot&gt;/config_ popř. _&lt;docroot&gt;/public_.

Úprava pluginu pro danou aplikaci
-----------------

Platí pravidlo, že všechny soubory pluginu mimo ty umístěné v podadresáři public
jdou "předefinovat" umístěním souboru se stejným jménem na patřičné místo do adresáře 
_&lt;docroot&gt;/app_ popř. _&lt;docroot&gt;/config_.
Takto lze snadno modifikovat chování pluginu pro danou aplikaci (např. vytvořením pro aplikaci 
specifického konfiguračního souboru či šablon).

**Varování:** Vztah aplikace a pluginu je však pouze jednosměrný: zatímco aplikace může nahradit ("přebít") jakýkoli
soubor pluginu svým vlastním, plugin "podstrčit" aplikaci svůj vlastní soubor na místo standardního 
nemůže. Pomocí pluginů tedy lze pouze přidávat nové "vlastnosti" aplikace, nikoli měnit stávající chování.

Proto tedy např. existence souboru database.yml v adresáři &lt;docroot&gt;/plugins/&lt;plugin&gt;/config nebude 
mít na chování aplikace žádný vliv: dokonce i v rámci akcí kontroleru pluginu se použije připojení
k databázi nadefinované v aplikaci (&lt;docroot&gt;/config/databse.yml). 
(To však samozřejmě nebrání pluginu si vytvořit takové připojení "manuálně".)

Bližší příklad, jak upravit chování pluginu, naleznete v kapitole o [kontrolérech](/czech/plugins%3Akontrolery) 

