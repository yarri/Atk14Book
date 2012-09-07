Pluginy
===============================

Pluginy umožňují oddělit ty části aplikace, které se používají ve více projektech, 
do samostatného adresáře, který může být i samostatným git repozitářem (submodulem). 

V pluginu mohou být stejně jako v aplikaci kontrolér, pohledy, modely, helpery atd...,
ve velmi podobné (ne-li stejné) adresářové struktuře, jakou má i samotná aplikace.
Vytvoření samostatného pluginu z existující aplikace je tedy velmi snadné a přímočaré. 

Součásti pluginu (kontrolér, šablony, helpery ...) lze v aplikaci používat tak, 
jako by byly obsaženy v samotné aplikaci (detaily budou dále v dokumentaci), při vytvoření
pluginu tedy zpravidla není třeba nijak upravovat původní aplikaci (pouze z ní odstranit
soubory přesunuté do pluginu) a naopak psaní pluginu se neliší od psaní samotné aplikace
v Atk14.

V případě potřeby lze i upravit chování pluginu v konkrétní aplikaci, aniž by se musel měnit samotný 
plugin: úprava se provede v souboru zkopírovaném z adresáře pluginu do adresáře aplikace. Takto lze 
např. změnit grafický výstup pluginu nahrazením jeho šablon etc...
