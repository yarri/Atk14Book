Widgets
=======

Widget [vidžet] určuje to, jak je formulářové políčko na stránce vykresleno. Uvažujme teď nějakou řetězcové políčko _CharField_, které vykreslíke 3x jinak.

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

Je, myslím, dobře předvídatelné, jak budou tato pole vykreslena.

Další příklad vhodný ke zmínění je _ChoiceField_. Ten lze vykresli pomocí select boxu nebo radio buttonků.

	<?
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

V ATK14 najdete tyto widgety:

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

Pokud vám to nebude stačit, napište si nový widget a umístěte ho adresáře _app/widgets/_. Na potřebu napsat si svůj vlastní widget možná narazíte při implementaci [reCAPTCHA](http://www.google.com/recaptcha)
