CSRF protection
===============

Suppose that somewhere in the depths of our website we have an action for transferring money to another account. A logged-in user visits the URL, fills in the form, and submits it via POST. If no field contains an error, the transfer is processed.

The form contains two fields: an amount and a destination bank account number. We enable CSRF protection on the form because we think it deserves it.

```php
<?php
// file: app/forms/money_transfers/create_new_form.php
class CreateNewForm extends ApplicationForm{
  function set_up(){
    $this->add_field("amount",new FloatField(array(
      "label" => "Amount of Money",
      "min_value" => 0.1
    )));
    $this->add_field("bank_account",new CharField(array(
      "label" => "Target bank account"
    )));

    $this->enable_csrf_protection(); // this call enables a CSRF protection
  }
}
```

The template looks very typical.

```smarty
{* file: app/views/money_transfers/create_new.tpl *}
<h1>{$page_title}</h1>

<p class="lead">The form on this page has a protection against <abbr title="Cross-site request forgery">CSFR</abbr>. You can check out, that the form contains hidden field named <code>_token</code>. Try to modify it's value or just let it expire (about 10 mins).</p>

{render partial="shared/form_error" small_form=1}

{form}
  {render partial="shared/form_field" fields="amount,bank_account"}
  <div class="form-group">
    <button type="submit" class="btn btn-default">Send my money away</button>
  </div>
{/form}
```

The controller holds no surprises either.

```php
<?php
// file: app/controllers/money_transfers_controller.php
class MoneyTransfersController extends ApplicationController{

  function create_new(){
    $this->page_title = "New money transfer";

    if($this->request->post() && ($d = $this->form->validate($this->params))){
      // consider that we have a logged user in $this->logged_user variable....
      // $this->_send_money($d["amount"],$this->logged_user->getBankAccount(),$d["bank_account"]);

      $this->flash->success("Congratulation! Your money has been sent!");
      $this->_redirect_to("money_transfers/create_new");
    }
  }

  function _send_money($money_amount,$source_bank_account,$destination_bank_account){
    // TODO: here should be a great bank transfer code
  }
}
```

In short, enabling CSRF protection comes down to a single line — calling `enable_csrf_protection()` on the form.

The protected form contains a security token that might look like this:

```html
<input type="hidden" value="2d1cd52926b3e0cb61e13858e8dd868622e758ef" name="_token" />
```

The token is hard to guess, is different for every visitor, and has a limited validity period (about 10 minutes). If the user submits no token or an expired one, form validation fails with the error message *Please submit the form again.*

CSRF protection is recommended for forms submitted via POST (or another non-GET method) that cause state changes: creating something, deleting something, or modifying something. And where a single HTTP request is sufficient to complete the operation.

It is not necessary to protect forms where a single request is not enough to complete the operation — for example, when the user makes changes in a form and is then shown a review screen highlighting what they changed, with the actual change only being applied after they confirm it.

You can try the protected form from this example live at <http://www.atk14.net/en/money_transfers/create_new/>

Feels good to have CSRF protection sorted, doesn't it?
