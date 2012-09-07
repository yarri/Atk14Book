Práce s pluginy "z vnějšku"
========================================

Instanci pluginu z aplikace můžete získat pomocí metody kontroléru 
    $controller->getPlugin($pluginName)
    
Chcete-li z jednoho pluginu přistoupit k jinému pluginu, je to opět snadné
    $plugin->manager[$pluginName];
    

