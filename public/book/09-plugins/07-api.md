Plugin API
======================================
U objektu pluginu existují tyto zajímavé metody

###init()
Inicializace pluginu. Je voláno při startu aplikace 

###beforeRender()
Voláno před generováním šablon: zde jde např. přidat do smarty další proměnné

###getSmarty()
Vrátí smarty objekt. Použitelné v beforeRender() a později, dříve ještě objekt smarty neexistuje

###getController()
Vrátí aktivní kontroller

###getSession()
Vrátí objekt session

###getMyPath(), getMyRelPath()
Vrátí cestu k podadresáři pluginu, relativní ke kořeni respektive k &lt;docroot&gt;

###useTemplates()
Zavolejte, pokud chcete zpřístupnit šablony pluginu ostatním kontrolérům
