Příznakové políčko: BoleanField
===============================

Následující formulář obsahuje 2 boolean políčka.

[Include app/forms/fields/boolean_field_form.php]

Na adrese <http://www.atk14.net/en/fields/boolean_field/> si formulář proklikejte. Mimo jiné zjistíte, že výchozí *widget* pro *BooleanField* je *checkbox*.

Volba *required* nemá pro boolean políčka smysl, proto je u obou polí nastavena na false. Pokud budete po uživateli chtít, aby zaškrnul nějaký checkbox
(a například tím potvrdil souhlas s obchodníma podmínkama e-shopu), doplňte tuto kontrolu do validační metody *clean()*.

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

Tolik o boolean políčkách.
