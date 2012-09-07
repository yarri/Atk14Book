Helpery a šablony
================================

Atk14 automaticky najde helpery umístěné
    <docroot>/plugins/<plugin\_name>/helpers
  
V akcích kontroleru pluginu  se lze odkazovat na šablony umístěné ve 
    <docroot>/plugins/<plugin\_name>/views
    
pokud je třeba, aby pohledy byly dostupné i pro ostatní kontroléry, je třeba v metodě
pluginu beforeRender() volat funkci $this->useTemplates():
    
    function beforeRender(){
      $this->useTemplates();
    }

popř. plugin podědit z předpřipraveného typu Atk14TemplatedPlugin, který to udělá za Vás.
  
Stejně jako u kontrolérů i zde jde v aplikaci šablony či helpery předefinovat vytvořením
stejnojmenných helperů či šablon v patřičných podadresářích adresáře <docroot>/app/

