Kontroléry
=====================================
V současné implementaci může mít plugin pouze jeden kontrolér, se jménem
shodným s názvem pluginu. (Implementaci s možností více kontrolérů je možná,
ovšem zatím nám nepřipadá užitečná). Tento kontrolér nechť je v souboru

    <docroot>/plugins/<plugin_name>/controllers/<plugin_name>\_controller.php
  
Jmený prostor pro kontroléry je sdílený v rámci celé aplikace, proto pokud v aplikaci
existuje kontrolér se stejným jménem, je použit tento a ne kontrolér z pluginu.
Toho lze využít ke snadné modifikaci pluginu, jak je blíže popsáno v [kapitole 
o adresářové struktuře pluginu].


Úprava a dědění kontroléru
-----------------
Představte si, že máte plugin gin s kontrolérem gin_controller a chcete jeho chování upravit. 

Vytvoříte-li však v aplikaci soubor _&lt;docroot&gt;app/controllers/gin\_controller.php_ s novou definicí kontroléru, nahradí
kontrolér gin\_controller z pluginu. Je dobrým zvykem takovýto "předefinovaný" kontrolér nekopírovat, ale podědit z orginálního 
kontroléru, a pouze mu do- a předefinovat metody, které se mají oproti originálnímu kontroleru chovat jinak. Díky tomu se při 
např. při bugfixu v kontroléru nemusí měnit i kód aplikace, stačí pouze update na submoduly.

Jelikož oba kontroléry musí mít stejné jméno, je k tomu potřeba drobný trik: musíte se vyhnout kolizi 
jmen tím, že původní kontrolér nahrajte v odlišném namespace 

    namespace gin_plugin {
      require_once plugins/gin/plugin_controller.php
    }
    
    class gin extends gin_plugin/gin
    {
    ....
    }

