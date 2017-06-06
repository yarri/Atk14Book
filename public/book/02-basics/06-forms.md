Formuláře
=========

Formulářový framework, který je součástí ATK14, zajišťuje validaci příchozích parametrů a pomáhá se zobrazením jednotlivých formulářových polí na stránce.

Formulář obvykle obsahuje 1 nebo více políček. Políčko (_Field_) obsahuje validační metodu _clean()_ pro vyčištění zadané hodnoty. Dále políčko obsahuje _Widget_, který určuje, jak se políčko zobrazí ve stránce.
Políčko pro zadávání textové informace _CharField_ může mít v jednom případě widget _TextInput_ a v dalším zase _Textarea_.

Pokud si nevystačíte z fieldy a widgety, které jsou součástí ATK14, můžete si napsat vlastní nebo si nainstalovat jako balíček pomocí _Composer_ (např. [PhoneField](https://packagist.org/packages/atk14/phone-field) nebo [RecaptchaField](https://packagist.org/packages/atk14/recaptcha-field)).

Teď si prohlédněte takový typický formulář pro registrace uživatele. Jednotlivá políčka formuláře jsou určena v metode *set_up()*. A vidíte, že i formulář může mít svou validační metodu _clean()_, ve které se obvyke řeší vztahy mezi políčky.

[Include app/forms/users/create_new_form.php]

Podívejte se, jak vypadé akce *create_new()* v UsersController. Metoda _validate()_ vrací pole vyčištěných dat. Pokud by byla byť jen jedna hodnota špatně, bude vrácen NULL.

[Include app/controllers/users_controller.php]

Není to velká věda, že?

O zobrazení formuláře se postará helper *{form}* a dvě sdílené šáblonky. Podívejte se.

[Include app/views/users/create_new.tpl]

Vyzkoušejte si tuto registraci uživatele v provozu na adrese <http://www.atk14.net/en/users/create_new/>

