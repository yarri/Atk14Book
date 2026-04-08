Widgets
=======

A widget determines how a form field is rendered on the page. Let's take a string field _CharField_ and render it three different ways.

```php
<?php
// file app/forms/main/sample_form.php
class SampleForm extends ApplicationForm{
	function set_up(){

		$this->add_field("title",new CharField(array(
			"label" => "Title",
			"widget" => new TextInput() // this is CharField`s default widget
		)));

		$this->add_field("body",new CharField(array(
			"label" => "Text",
			"widget" => new TextArea(array(
				"attrs" => array("cols" => 40, "rows" => 30)
			))
		)));

		$this->add_field("password",new CharField(array(
			"label" => "New Password",
			"widget" => new PasswordInput()
		)));
	}
}
```

It's fairly easy to predict how these fields will be rendered.

Another example worth mentioning is _ChoiceField_. It can be rendered as a select box or as radio buttons.

```php
<?php
// file app/forms/main/choice_form.php
class ChoiceField extends ApplicationForm{
	function set_up(){

		$this->add_field("favourite_fruit",new ChoiceField(array(
			"label" => "What is your favourite fruit?",
			"choices" => array(
				"apple" => "an ordinary apple",
				"tomato" => "a rotten tomato",
				"kiwi" => "a walking bird"
			),
			"widget" => new Select() // this is a default widget for ChoiceField
		)));

		$this->add_field("sex",new ChoiceField(array(
			"label" => "Sex",
			"choices" => array(
				"M" => "Male",
				"F" => "Female"
			),
			"widget" => new RadioInput()
		)));
	}
}
```

ATK14 includes the following widgets:

 * CheckboxInput
 * CheckboxSelectMultiple
 * EmailInput
 * FileInput
 * HiddenInput
 * MultipleHiddenInput
 * PasswordInput
 * RadioInput
 * RadioSelect
 * Select
 * SelectMultiple
 * Textarea
 * TextInput

If none of these are sufficient, you can write your own widget and place it in the _app/widgets/_ directory. You may find yourself needing a custom widget when implementing [reCAPTCHA](http://www.google.com/recaptcha), for example.
