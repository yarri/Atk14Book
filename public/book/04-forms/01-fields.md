Formulářová Políčka
===================

ATK14 přichází se základní sadou formulářových políček. Vy pak máte v rukou možnost tato základní políčka rozšiřovat a vytvářet tak zcela nová
důmyslná políčka.

Políčko pro zadávání lichých čísel
----------------------------------

ATK14 obsahuje políčko pro zadávání celých čísel &mdash; _IntegerField_. Řekněme, že chceme vytvořit takové políčko, do kterého bude možné zadat pouze liché číslo.

[Include app/fields/odd_number_field.php]

Všimněte si metody *clean()*. Ta je důležitá. V ní dochází k validaci vstupní hodnoty. Výstupem pak je popis chyby a samotná "vyčištěná" hodnota. V případě, že byla hodnota zvalidována bez chyby, musí být popis chyby (*$err*) nastaven na *null*.

Formulář s políčkem *OddNumberField* si vyzkoušíte na adrese <http://www.atk14.net/en/fields/odd_number_field/>

Políčko pro zadávání hesla
--------------------------

Políčko _PasswordField_ patří rovněž do základní sady formulářových políček. Takto vypadá.

[Include atk14/src/forms/fields/password_field.php]

Je zde vidět, že metoda *clean()* nebyla překryta. Nebylo to nutné. Hlavní rozdíl mezi *CharField* a *PasswordField* je v jiném widgetu, který definuje to,
jak bude políčko vykresleno ve formuláři. O widgetech se mluví v další kapitole.

Políčko vracející objekt
------------------------

Může se hodit takové políčko, které vrací něco na první pohled bláznivého - třeba objekt nějaké třídy.

to be continued...




