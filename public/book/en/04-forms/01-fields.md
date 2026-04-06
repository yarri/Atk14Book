Form fields
===========

ATK14 comes with a basic set of form fields. You can then extend these basic fields to create entirely new, sophisticated ones.

A field for entering odd numbers
---------------------------------

ATK14 includes a field for entering integers — _IntegerField_. Let's say we want to create a field that only accepts odd numbers.

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

Notice the *clean()* method — it is the key part. This is where the input value is validated. The output is an error description and the "cleaned" value itself. If the value passed validation without errors, the error description (*$err*) must be set to *null*.

You can try the form with the *OddNumberField* live at <http://www.atk14.net/en/fields/odd_number_field/>

A field for entering a password
--------------------------------

The _PasswordField_ is also part of the basic set of form fields. Here is what it looks like.

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

Notice that the *clean()* method was not overridden — it wasn't necessary. The main difference between *CharField* and *PasswordField* is a different widget, which defines how the field will be rendered in the form. Widgets are discussed in the next chapter.

A field returning an object
----------------------------

It can be useful to have a field that returns something that might seem unusual at first glance — for example, an instance of some class.

to be continued...
