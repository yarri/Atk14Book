Ochrana před CSRF
=================

Dejme tomu, že kdesi v útrobách našeho webu máme akci na převod peněz na jiný účet. Přihlášený uživatel navštíví URL, vyplní formulář a odešle jej metodou POST. V případě, že žádné políčko neobsahuje chybu, je převod peněz proveden.

Formulář obsahuje dvě políčka: peněžní obnos a číslo bankovního účtu příjemce. Ve formuláři aktivujeme ochranu před CSRF, protože si myslíme, že si to zaslouží.

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

Šablona vypadá velmi typicky.

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

Rovněž kontroler nenabízí žádná překvapení.

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

Suma sumárum zapnutí ochrany před CSRF spočívá v jednom řádku - volání metody enable\_csrf\_protection() na formuláři.

Chráněný formulář obsahuje kontrolní značku, která může vypadat například takto:

	<input type="hidden" value="2d1cd52926b3e0cb61e13858e8dd868622e758ef" name="_token" />

Značka je obtížně odhadnutelná, je různá pro každého návštěvníka a má omezenou časovou platnost (asi 10 minut). V případě, že uživatel nepošle značku žádnou nebo pošle značku již neplatnou, validace formuláře selže s chybovou zprávou *Prosím, odešlete formulář znovu.*

Před CSRF je vhodné chránit formuláře odesílané metodou POST (nebo jinou non-GET metodou), kdy dochází ke změnám: něco se vytváří, něco se maže, něco se mění na něco jiného. A zároveň nám k provedení dané operace stačí jediný HTTP požadavek.

Není nutné chránit takové formuláře, kdy k její realizaci jediný HTTP požadavek nestačí. Dejme tomu, že uživatel provede změny ve formuláři a následně je mu pro kontrolu zobrazen přehled se zvýrazněním toho, co upravil.
Samotná změna však bude provedena až poté, co přehled potvrdí (stiskne tlačítko).

Chráněný formulář z našeho příkladu si na živo osahej na adrese <http://www.atk14.net/en/money_transfers/create_new/>

Cítíš se lépe, když máš CSRF ochranu vyřešenou?
