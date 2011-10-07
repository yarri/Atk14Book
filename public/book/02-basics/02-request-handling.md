Obsluha HTTP požadavku
======================

Mysleme opět na nějakou skutečnou adresu

    http://www.atk14.net/en/books/edit/?id=29

Co se stane, když takovou adresu navštívíme?

ATK14 načte soubor

    ./app/controllers/books_controller.inc

ve kterém očekává třídu BooksController, vytvoří instanci této třídy a spustí její metodu *edit()*.

Ještě před spuštěním metody edit() však prověří, zda existuje soubor

    ./app/forms/books/edit_form.inc

a pokud takový soubor najde, načte jej a vytvoří a zinicializuje instanci. Kontroler formulář najde v *$this->form*.
