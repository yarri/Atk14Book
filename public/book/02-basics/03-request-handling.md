Obsluha HTTP požadavku
======================

Mysleme opět na nějakou skutečnou adresu

    http://www.atk14.net/en/books/edit/?id=29

Co se stane, když takovou adresu navštívíme?

ATK14 vytvoří a připraví kontroler - instanci třídy *BooksController* ze souboru

    app/controllers/books_controller.php

Kontroleru je nastaven příslušný formulář - instance třídy *EditForm* ze souboru

    app/forms/books/edit_form.php

Následně je v kontroleru spuštěna metoda *edit()* a vyrenderována šablona

    app/views/books/edit.tpl

Obsah šablony je vykreslen v layoutu

    app/layouts/_default.tpl

Výstup je odeslán spokojenému uživateli.

Pro začátek není nutné vědět více.

Na ATK14 je fajn, že stačí dodržet názvovou konvenci a toto všechno začne fungovat automaticky. Ctí se zde zásada *konvence před konfigurací*.
