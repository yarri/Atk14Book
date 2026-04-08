Checkbox field: BooleanField
============================

The following form contains 2 boolean fields.

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

Click through the form at <http://www.atk14.net/en/fields/boolean_field/>. You'll notice, among other things, that the default *widget* for *BooleanField* is a *checkbox*.

The *required* option makes no sense for boolean fields, so it is set to false on both fields. If you want to require the user to check a checkbox
(for example, to confirm that they agree to the e-shop's terms and conditions), add that check to the *clean()* validation method.

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

That covers boolean fields.
