Základní tvar adresy
====================

Mysleme na skutečnou adresu

    http://www.atk14.net/en/books/edit/?id=29

 * **en**: kód jazykové verze
 * **books**: název kontroleru
 * **edit**: akce kontroleru
 * **id=29**: paremetr

Pokud je akce *index*, v URL se neuvádí.

    http://www.atk14.net/en/books/
    http://www.atk14.net/en/books/?search=boat&from=10


Kombinace kontroleru *main*, akce *index* a vychozí jazykové verze je považováná za frontpage celé aplikace.

    http://www.atk14.net/

Frontpage v jiné jazykové verzi než výchozí je servirována takto:

    http://www.atk14.net/en/
