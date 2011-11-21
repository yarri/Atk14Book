Validace parametrů pomocí formuláře
===================================

Asi jste zvyklí na návrhový vzor MVC. Je hezký, že? Nám se ale zdálo, že pro webové aplikace by jej bylo dobré doplnit o validaci parametrů. Teď nevíme, jak tomu budeme říkat. Nejspíš *MVVC*, nebo možná víc sexy *M2VC* (Model View Validation Controller).

Formulářový framework, který je součástí ATK14, zajišťuje validaci příchozích parametrů a pomáhá se zobrazením jednotlivých formulářových polí na stránce.

Když začnete psát například akci pro registraci uživatele, je dobré myslet na formulář, který návštěvník uvidí na stránce. Je výhodné začít formulářem.

Include app/forms/users/crate_new_form.php
