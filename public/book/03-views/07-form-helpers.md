Vykreslování formulářů
======================

* ### form ([block](https://github.com/yarri/Atk14/blob/master/src/atk14/helpers/block.form.php))

	Vykreslí formulář. Není-li uvedeno jinak, použije výchozí formulář, který je v šabloně v proměnné _$form_

		{form}
			<fieldset>
				{render partial="shared/form_field" field="name"}
				<div class="buttons">
				<buttont type="submit">Send</buttont>
				</div>
			</fieldset>
		{/form}

* ### form_remote ([block](https://github.com/yarri/Atk14/blob/master/src/atk14/helpers/block.form_remote.php))

	Vykreslí formulář, který bude obsloužen asynchronně pomocí _atk14.js_. Používá se stejně jako {form}.

* ### form_tag ([function](https://github.com/yarri/Atk14/blob/master/src/atk14/helpers/function.form_tag.php))

* ### form\_remote\_tag ([function](https://github.com/yarri/Atk14/blob/master/src/atk14/helpers/function.form_remote_tag.php))

* ### field_id ([modifier](https://github.com/yarri/Atk14/blob/master/src/atk14/helpers/modifier.field_id.php))

* ### field ([modifier](https://github.com/yarri/Atk14/blob/master/src/atk14/helpers/modifier.field.php))

* ### field_value ([modifier](https://github.com/yarri/Atk14/blob/master/src/atk14/helpers/modifier.field_value.php))

* ### label ([modifier](https://github.com/yarri/Atk14/blob/master/src/atk14/helpers/modifier.label.php))

* ### form_field ([modifier](https://github.com/yarri/Atk14/blob/master/src/atk14/helpers/modifier.form_field.php))

* ### form_label ([modifier](https://github.com/yarri/Atk14/blob/master/src/atk14/helpers/modifier.form_label.php))
