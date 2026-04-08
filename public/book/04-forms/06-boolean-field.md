Příznakové políčko: BooleanField
================================

Následující formulář obsahuje 2 boolean políčka.

```php
<?php
// file: app/forms/fields/boolean_field_form.php
class BooleanFieldForm extends ApplicationForm{
  function set_up(){
    $this->set_method("get");

    $this->add_field("bool",new BooleanField(array(
      "required" => false,
    )));

    $this->add_field("another_bool",new BooleanField(array(
      "required" => false,
      "widget" => new Select(array(
        "choices" => array(
          "true" => "Yes",
          "false" => "No",
        ),
      )),
    )));
  }
}
```

Na adrese <http://www.atk14.net/en/fields/boolean_field/> si formulář proklikej. Mimo jiné zjistíš, že výchozí *widget* pro *BooleanField* je *checkbox*.

Volba *required* nemá pro boolean políčka smysl, proto je u obou polí nastavena na false. Pokud budeš po uživateli chtít, aby zaškrtl nějaký checkbox
(a například tím potvrdil souhlas s obchodními podmínkami e-shopu), doplň tuto kontrolu do validační metody *clean()*.

```php
<?php
class OrderForm extends ApplicationForm{

	// ....

	function clean(){
		if(!$this->cleaned_data["confirm"]){
			$this->set_error("confirm","Please, check the checkbox");
		}

		return array(null,$this->cleaned_data);
	}
}
```

Tolik o boolean políčkách.
