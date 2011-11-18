Ochrana před CSRF
=================

Dejme tomu, že kdesi v útrobách našeho webu máme akci na převod peněz na jiný účet. Přihlášený uživatel navštíví URL, vyplní formulář a odešle jej metodou POST. V případě, že žádné políčko neobsahuje chybu, je převod peněz proveden.

Formulář obsahuje dvě políčka: peněžní obnos a číslo bankovního účtu příjemce. Ve formuláři aktivujeme ochranu před CSRF, protože si myslíme, že si to zaslouží.

Include app/forms/money_transfers/create_new_form.php

Šablona vypadá velmi typicky.

Include app/views/money_transfers/create_new.tpl

Rovněz kontroler nenabízí žádná překvapení.

Include app/controllers/money_transfers_controller.php

Suma sumárum zapnutí ochrany před CSRF spočívá v jednom řádku - volání metody enable\_csrf\_protection() na formuláři.

Chráněný formulář obsahuje nesnadno odhadnutelnou bezpečnostní značku. Může vypada například takto.
 
	<input type="hidden" value="2d1cd52926b3e0cb61e13858e8dd868622e758ef" name="_token" />

Tato značka je obtížně odhadnutelná, je různá pro každého návštěvníka a má omezenou časovou platnost (asi 10 minut). V případě, že uživatel nepošle značku žádnou nebo pošle značku již neplatnou, validace formuláře selže s chybovou zprávou *Prosím, odešlete formulář znovu.*

Proti CSRF je vhodné chránit formuláře odesílané metodou POST (nebo jinou non-GET metodou), kdy dochází ke změnám: něco se vytváří, něco se maže, něco se mění na něco jiného. A zároveň k realizaci takové změny nám stačí jediný HTTP požadavek.
Takže například v případě, kdy po odeslání změnového formuláře ješte před samotnou změnou zobrazujeme uživateli přehled změněných údajů, není třeba takový formulář chránit.

Cítíte se lépe, když máte CSRF ochranu vyřešenou?

Příklad si na živo vyzkoušíte na adrese <http://www.atk14.net/en/money_transfers/create_new/>
