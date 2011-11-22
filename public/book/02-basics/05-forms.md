Formuláře
=========

Asi jste zvyklí na návrhový vzor MVC. Je hezký, že? Nám se ale zdálo, že pro webové aplikace by jej bylo dobré doplnit o validaci parametrů.
Teď nevíme, jak tomu budeme říkat. Nejspíš *MVVC*, nebo možná víc sexy *M2VC* (Model View Validation Controller).

Formulářový framework, který je součástí ATK14, zajišťuje validaci příchozích parametrů a pomáhá se zobrazením jednotlivých formulářových polí na stránce.

Pro registrace uživatele může být klidně použit takovýto formulář.

Include app/forms/users/create_new_form.php

Jednotlivá políčka formuláře jsou určena v metode *set_up()*. Po odeslání formuláře jsou hodnoty každého políčka automaticky validovány podle svých pravidel.
Mimo tyto validace je možné validovat formulář jako celek. K tomu slouží metoda *clean()*.

Podívejte se, jak vypadé akce *create_new()* v UsersController.

Include app/controllers/users_controller.php

Není to velká věda, že?

O zobrazení formuláře se postará helper *{form}* a dvě sdílené šáblonky. Podívejte se.

Include app/views/users/create_new.tpl

Vyzkoušejte si tuto registraci uživatele v provozu na adrese <http://www.atk14.net/en/users/create_new/>

