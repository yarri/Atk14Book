Tvar adresy URL
===============

Každá adresa v ATK14 aplikaci (vyjma adres směřujících do `/public/`) obsahuje povinně tyto náležitosti:

 * kód jazykové verze
 * název kontroleru
 * název akce

Dále adresa může obsahovat:

 * parametry
 * název namespace

Vezměme si konkrétní adresu:

```text
http://www.atk14.net/en/books/edit/?id=29
```

 * **en** je kód jazykové verze
 * **books** je název kontroleru
 * **edit** je název akce
 * **id=29** je parametr

Pokud je název akce *index*, v URL se neuvádí.

```text
http://www.atk14.net/en/books/
http://www.atk14.net/en/books/?search=boat&offset=10
```

Akce *index* v kontroleru *main* ve výchozím jazyce je považována za *frontpage*.

```text
http://www.atk14.net/
```

Frontpage v jiné jazykové verzi než výchozí je servírována takto:

```text
http://www.atk14.net/fr/
```

Namespace
---------

Pomocí namespace lze vytvořit několik nezávislých aplikací, které společně využívají modely a sdílené šablony.
Typickým příkladem je administrační rozhraní.

Zákazníkům elektronického obchodu je servírována základní aplikace bez pojmenovaného namespace.

```text
http://www.gibona.net/
http://www.gibona.net/en/products/detail/?id=29
```

Administrátor má v namespace *admin* k dispozici odlišný pohled na produkt.

```text
http://www.gibona.net/admin/
http://www.gibona.net/admin/en/products/detail/?id=29
```

Jeden ATK14 projekt může obsahovat několik takových namespaců (podaplikací).

Adresář public
--------------

Adresář *public* je určen pro statický obsah jako jsou styly CSS, javascripty, obrázky atd. Adresy směřující do `/public/` nejsou obsluhovány frameworkem ATK14.

```text
http://www.atk14.net/public/stylesheets/styles.css
http://www.atk14.net/public/images/atk14.gif
```
