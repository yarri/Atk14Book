Formulářová Políčka
===================

ATK14 přichází se základní sadou formulářových políček. Vy pak máte v rukou možnost tato základní políčka rozšiřovat a vytvářet tak zcela nová
důmyslná políčka.

Políčko pro zadávání lichých čísel
----------------------------------

ATK14 obsahuje políčko pro zadávání celých čísel &mdash; _IntegerField_. Řekněme, že chceme vytvořit takové políčko, do kterého bude možné zadat pouze liché číslo.

```php
<?php
// file: app/fields/odd_number_field.php
class OddNumberField extends IntegerField{
  function __construct($options = array()){
    parent::__construct($options);
    $this->update_messages(array(
      "not_odd_number" => "Please, enter an odd number"
    ));
  }

  function clean($value){
    list($err,$value) = parent::clean($value);
    if($err || is_null($value)){ return array($err,$value); }

    if(abs($value)%2!=1){
      $err = $this->messages["not_odd_number"];
    }

    return array($err,$value);
  }
}
```

Všimněte si metody *clean()*. Ta je důležitá. V ní dochází k validaci vstupní hodnoty. Výstupem pak je popis chyby a samotná "vyčištěná" hodnota. V případě, že byla hodnota zvalidována bez chyby, musí být popis chyby (*$err*) nastaven na *null*.

Formulář s políčkem *OddNumberField* si vyzkoušíte na adrese <http://www.atk14.net/en/fields/odd_number_field/>

Políčko pro zadávání hesla
--------------------------

Políčko _PasswordField_ patří rovněž do základní sady formulářových políček. Takto vypadá.

```php
<?php
// file: atk14/src/forms/fields/password_field.php
/**
 * Input field for entering a password
 *
 * Be aware of the fact that the PasswordField is considering an initial value.
 * Sometimes you may not want to send a password to HTML. See the following example.
 *
 *		// $form has a PasswordField named password: $form->add_field("password", new PasswordField());
 *		$user = User::FindById(123);
 *		$initial = $user->toArray();
 *		unset($initial["password"]);
 *		$form->set_initial($initial);
 */
class PasswordField extends CharField{
  function __construct($options = array()){
    $options = array_merge(array(
      "widget" => new PasswordInput(array(
        "attrs" => array("class" => "form-control")
      )),
      "null_empty_output" => true,

      // set this option to false if you want PasswordField which accepts passwords with leading or trailing white chars
      // "trim_value" => false,
    ),$options);

    parent::__construct($options);
  }
}
```

Je zde vidět, že metoda *clean()* nebyla překryta. Nebylo to nutné. Hlavní rozdíl mezi *CharField* a *PasswordField* je v jiném widgetu, který definuje to,
jak bude políčko vykresleno ve formuláři. O widgetech se mluví v další kapitole.

Políčko vracející objekt
------------------------

Může se hodit takové políčko, které vrací něco na první pohled bláznivého - třeba objekt nějaké třídy.

to be continued...




