Kontroléry
=====================================
V současné implementaci může mít plugin pouze jeden kontrolér, se jménem
shodným s názvem pluginu. (Implementaci s možností více kontrolérů je možná,
ovšem zatím nám nepřipadá užitečná). Tento kontrolér nechť je v souboru

    <docroot>/plugins/<plugin_name>/controllers/<plugin_name>_controller.php
  
Jmený prostor pro kontroléry je sdílený v rámci celé aplikace, proto pokud v aplikaci
existuje kontrolér se stejným jménem, je použit tento a ne kontrolér z pluginu.
Toho lze využít ke snadné modifikaci pluginu, jak je blíže popsáno v [kapitole 
o adresářové struktuře pluginu].


Úprava a dědění kontroléru
-----------------
Nechť máte plugin gin s kontrolérem gin\_controller a chcete jeho chování upravit. 
To provedete tak, že vytvoříte-li v aplikaci soubor _&lt;docroot&gt;app/controllers/gin\_controller.php_ 
s novou definicí kontroléru. Tento nový kontrolér bude při požadavku na kontrolér gin automaticky nalezen
a použit.

Je dobrým zvykem takovýto "předefinovaný" kontrolér nekopírovat, ale podědit z orginálního 
kontroléru, a pouze mu do- a předefinovat metody, které se mají oproti originálnímu kontroleru 
chovat jinak. Díky tomu se při dalším vývoji pluginu (ať už přidávání nových funkcí či bugfixingu) 
nemusí tyto změny backportovat do zkopírovaného kontroléru v aplikaci (pro upgrade pluginu pak stačí
jednoduché git submodule update).

Jelikož oba kontroléry musí mít stejné jméno, je k podědění kontrolérů nutný drobný trik: musíte se 
vyhnout kolizi jmen tím, že původní kontrolér nahrajte v odlišném namespace 

    namespace gin_plugin {
      require_once plugins/gin/plugin_controller.php
    }
    
    class gin extends gin_plugin/gin
    {
    ....
    }

