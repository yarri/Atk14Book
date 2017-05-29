Tvar adresy URL
===============

Každá adresa v ATK14 aplikaci (vyjma adres směřujících do /public/) obsahuje povinně tyto náležitosti:

 * kód jazykové verze
 * název kontroleru
 * název akce

Dále adresa může obsahovat
 
 * parametry
 * název namespace

Teď mysleme na nějakou skutečnou adresu

    http://www.atk14.net/en/books/edit/?id=29

 * **en** je kód jazykové verze
 * **books** je název kontroleru
 * **edit** je název akce
 * **id=29** je parametr

Pokud je název akce *index*, v URL se neuvádí.

    http://www.atk14.net/en/books/
    http://www.atk14.net/en/books/?search=boat&offset=10


Akce *index* v kontroleru *main* ve výchozím jazyce je považováná za *frontpage*.

    http://www.atk14.net/

Frontpage v jiné jazykové verzi než výchozí je servirována takto:

    http://www.atk14.net/fr/

Namespace
---------

Pomocí namespace je možné vytvořit několik nezávislých aplikací, které spolěčně využívají modely a sdílené šablony.
Takovým typickým příkladem je administrační rozhraní.

Zákazníkům elektronického obchodu je servírována základní aplikace bez pojmenovaného namespace.

    http://www.gibona.net/
    http://www.gibona.net/en/products/detail/?id=29

Administrátor má k dispozici v namespace *admin* odlišný pohled na produkt.

    http://www.gibona.net/admin/
    http://www.gibona.net/admin/en/products/detail/?id=29

Jeden ATK14 projekt může obsahovat několik takových namespaců (podaplikací).

O namespace bude pojednáno později.

Adresář public
--------------

Adresář *public* je určen pro statický obsah jako jsou styly CSS, javascripty, obrázky atd. Adresy směřující do public nejsou obsluhovány frameworkem ATK14.

    http://www.atk14.net/public/stylesheets/styles.css
    http://www.atk14.net/public/images/atk14.gif

