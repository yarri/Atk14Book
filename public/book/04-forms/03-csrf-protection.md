Ochrana před CSRF
-----------------

Soubor app/forms/money\_sender/send\_money\_form.php

	<?php
	class SendMoneyForm extends ApplicationForm{
		function set_up(){
			$this->add_field("amount",new FloatField(array(
				"label" => "Amount of Money",
				"min_value" => 0.1
			)));
			$this->add_field("bank_account",new CharField(array(
				"label" => "Bank account"
			)));

			$this->enable_csrf_protection();
		}
	}

Soubor app/views/money\_sender/send\_money.tpl

	{form}
		<fieldset>
		{render partial=shared/form_field fields=amount,bank_account}
		<div class="buttons">
			<button type="submit">Send my money away</button>
		</div>
		</fieldset>
	{/form}


Soubor app/controllers/money\_sender\_controller.php

	<?php
	class MoneySenderController extends ApplicationController{
		function send_money(){
			if($this->request->post() && ($d = $this->form->validate($this->params))){
				$this->_send_money($d["amount"],$d["bank_account"]);
				$this->flash->success("Your money has been sent. Nice!");
				$this->_redirect_to("controller/main");
			}
		}
	}

Nevím, jestli to hned vidíte, ale tady je navíc pouze jedno volání ve formuláři $this->enable\_csrf\_protection().
