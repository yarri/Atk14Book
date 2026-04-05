Formuláře
=========

Formulářový framework, který je součástí ATK14, zajišťuje validaci příchozích parametrů a pomáhá se zobrazením jednotlivých formulářových polí na stránce.

Formulář obvykle obsahuje 1 nebo více políček. Políčko (_Field_) obsahuje validační metodu _clean()_ pro vyčištění zadané hodnoty. Dále políčko obsahuje _Widget_, který určuje, jak se políčko zobrazí ve stránce.
Políčko pro zadávání textové informace _CharField_ může mít v jednom případě widget _TextInput_ a v dalším zase _Textarea_.

Pokud si nevystačíte z fieldy a widgety, které jsou součástí ATK14, můžete si napsat vlastní nebo si nainstalovat jako balíček pomocí _Composer_ (např. [PhoneField](https://packagist.org/packages/atk14/phone-field) nebo [RecaptchaField](https://packagist.org/packages/atk14/recaptcha-field)).

Teď si prohlédněte takový typický formulář pro registrace uživatele. Jednotlivá políčka formuláře jsou určena v metode *set_up()*. A vidíte, že i formulář může mít svou validační metodu _clean()_, ve které se obvyke řeší vztahy mezi políčky.

```php
<?php
// file: app/forms/users/create_new_form.php
class CreateNewForm extends ApplicationForm{

  function set_up(){
    $this->add_field("login",new LoginField([
      "title" => "Desired username",
    ]));
    $this->add_field("password",new PasswordField([
      "title" => "Desired password",
    ]));
    $this->add_field("password_confirmation",new PasswordField([
      "title" => "Confirm password",
    ]));
    $this->add_field("name",new CharField([
      "title" => "Your Name",
      "min_length" => 2,
      "max_length" => 255
    ]));
    $this->add_field("email",new EmailField([
      "title" => "E-mail address"
    ]));

    $this->set_method("post"); // default is "post"
    $this->set_attr("novalidate","novalidate"); // <form novalidate="novalidate"></form>
  }

  function clean(){
    list($err,$data) = parent::clean();

    if(isset($data["login"]) && User::FindByLogin($data["login"])){
      $this->set_error("login","The given username has been already taken");
    }

    if(isset($data["password"]) && isset($data["password_confirmation"]) && $data["password"]!==$data["password_confirmation"]){
      $this->set_error("password_confirmation","Passwords are not equals");
    }

    // We do not need password_confirmation in the cleaned data
    unset($data["password_confirmation"]);

    return [$err,$data];
  }
}
```

Podívejte se, jak vypadé akce *create_new()* v UsersController. Metoda _validate()_ vrací pole vyčištěných dat. Pokud by byla byť jen jedna hodnota špatně, bude vrácen NULL.

```php
<?php
// file: app/controllers/users_controller.php
class UsersController extends ApplicationController{

  function create_new(){
    $this->page_title = "Sign Up";
    if($this->request->post() && ($d = $this->form->validate($this->params))){
      $user = User::CreateNewRecord($d);
      $this->_store_login($user);
      $this->flash->notice("The registration has been successfuly realized.");
      $this->_redirect_to("main/index");
    }
  }

  function login(){
    if($this->request->post() && ($d = $this->form->validate($this->params))){
      if(!$this->session->cookiesEnabled()){
        $this->form->set_error("Please, enable cookies in your browser in order to login");
        return;
      }
      if(!$user = User::Login($d["login"],$d["password"])){
        $this->form->set_error("Invalid username or password");
        return;
      }
      $this->_store_login($user);
      $this->flash->notice("You have been succesfully logged in");
      $this->_redirect_to("main/index");
    }
  }

  function logout(){
    $this->session->clear("user_id");
    $this->flash->notice("You have been logged out");
    $this->_redirect_to("main/index");
  }

  // stores logged user id into session
  function _store_login($user){
    $this->session->s("user_id",$user->getId());
  }

  function _before_filter(){
    $this->doc_source_files[] = "app/models/user.php";
  }
}
```

Není to velká věda, že?

O zobrazení formuláře se postará helper *{form}* a dvě sdílené šáblonky. Podívejte se.

```smarty
{* file: app/views/users/create_new.tpl *}
<h2>{$page_title}</h2>

{form}
  {render partial="shared/form_error" small_form=1}
  <fieldset>

    {render partial="shared/form_field" fields="login,password,password_confirmation,name,email"}

    <div class="buttons">
    <button type="submit">Sign Up</button>
    </div>

  </fieldset>
{/form}
```

Vyzkoušejte si tuto registraci uživatele v provozu na adrese <http://www.atk14.net/en/users/create_new/>

