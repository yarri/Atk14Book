Rendering forms
===============

* ### form ([block](https://github.com/atk14/Atk14/blob/master/src/atk14/helpers/block.form.php))

	Renders a form. Unless specified otherwise, uses the default form available in the template as the _$form_ variable.

		{form}
			<fieldset>
				{render partial="shared/form_field" field="name"}
				<div class="buttons">
				<button type="submit">Send</button>
				</div>
			</fieldset>
		{/form}

* ### form_remote ([block](https://github.com/atk14/Atk14/blob/master/src/atk14/helpers/block.form_remote.php))

	Renders a form that will be handled asynchronously via _atk14.js_. Used the same way as {form}.

* ### form_tag ([function](https://github.com/atk14/Atk14/blob/master/src/atk14/helpers/function.form_tag.php))

* ### form\_remote\_tag ([function](https://github.com/atk14/Atk14/blob/master/src/atk14/helpers/function.form_remote_tag.php))

* ### field_id ([modifier](https://github.com/atk14/Atk14/blob/master/src/atk14/helpers/modifier.field_id.php))

* ### field ([modifier](https://github.com/atk14/Atk14/blob/master/src/atk14/helpers/modifier.field.php))

* ### field_value ([modifier](https://github.com/atk14/Atk14/blob/master/src/atk14/helpers/modifier.field_value.php))

* ### label ([modifier](https://github.com/atk14/Atk14/blob/master/src/atk14/helpers/modifier.label.php))

* ### form_field ([modifier](https://github.com/atk14/Atk14/blob/master/src/atk14/helpers/modifier.form_field.php))

* ### form_label ([modifier](https://github.com/atk14/Atk14/blob/master/src/atk14/helpers/modifier.form_label.php))
