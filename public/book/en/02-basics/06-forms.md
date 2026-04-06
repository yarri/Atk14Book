Forms
=====

The form framework included in ATK14 handles validation of incoming parameters and helps with rendering individual form fields on the page.

A form typically contains one or more fields. A field (_Field_) has a validation method _clean()_ that cleans the submitted value. It also has a _Widget_ that determines how the field is rendered on the page.
The text input field _CharField_ can use a _TextInput_ widget in one case and a _Textarea_ in another.

If the fields and widgets included in ATK14 are not enough, you can write your own or install them as packages via _Composer_ (e.g. [PhoneField](https://packagist.org/packages/atk14/phone-field) or [RecaptchaField](https://packagist.org/packages/atk14/recaptcha-field)).

Take a look at a typical user registration form. The individual form fields are defined in the *set_up()* method. Notice that a form can also have its own _clean()_ validation method, which is typically used to handle relationships between fields.

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

Now look at the complete UsersController. The _validate()_ method returns an array of cleaned data. If even a single value is invalid, it returns NULL.

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

Not rocket science, is it?

Rendering the form is handled by the *{form}* helper and two shared partials. Take a look.

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

Try this user registration live at <http://www.atk14.net/en/users/create_new/>
