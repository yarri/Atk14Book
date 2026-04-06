Basic email example
===================

At <http://www.atk14.net/en/reminders/create_new/> you'll find a hypothetical example of sending emails. A strict librarian sends overdue return reminders to absent-minded readers. Let's walk through the example.

There is a simple form for entering the reader's email address and selecting a book.

```php
<?php
// file: app/forms/reminders/create_new_form.php
class CreateNewForm extends ApplicationForm{

  function set_up(){
    $this->add_field("email_to",new EmailField());
    $this->add_field("book",new BookField());
  }
}
```

In the controller, at the right place, we call `$this->mailer->send_reminder()` and pass it the appropriate parameters.

```php
<?php
// file: app/controllers/reminders_controller.php
class RemindersController extends ApplicationController {

  function create_new(){
    $this->page_title = "Book returning reminder";

    if($this->request->post() && ($d = $this->form->validate($this->params))){
      $email_src = $this->mailer->send_reminder($d["book"],$d["email_to"]);

      // the following lines display source code of the email just sent...
      $this->render_template = false;
      $this->response->setContentType("text/plain");
      $this->response->write(print_r($email_src,true));

      // $this->flash->success("The reminder has been sent");
      // $this->_redirect_to("reminders/create_new");
    }
  }

  function _before_filter(){
    $this->doc_source_files[] = "app/controllers/application_mailer.php";
    $this->doc_source_files[] = "app/views/mailer/send_reminder.tpl";
  }
}
```

In the mailer, the `send_reminder()` action is properly defined.

```php
<?php
// file: app/controllers/application_mailer.php
/**
 * This is the application mailer class.
 *
 * From a controller you can call mailer`s action this way
 * 
 * 	$this->mailer->send_reminder($book,$email_to);
 *
 */ 
class ApplicationMailer extends Atk14Mailer {

  function send_reminder($book,$email_to){
    $this->from = "Pvt. Vasquez <vasquez@public-library.com>";
    $this->to = $email_to;
    $this->subject = "Book return reminder";

    $this->tpl_data["book"] = $book;

    // the email`s body will be rendered from template views/mailer/send_reminder.tpl

    // some more variables to set
    // $this->cc = "";
    // $this->bcc = "";
    // $this->content_type = "text/plain";
    // $this->content_charset = "UTF-8";

    // sending attachments
    // $this->add_attachment($image_content,"image.jpg","image/jpg");
  }

  /**
   * A place for common configuration.
   */
  function _before_filter(){
    $this->from = "info@atk14.net";
  }
}
```

The email body is rendered from a template.

```smarty
{* file: app/views/mailer/send_reminder.tpl *}
Dear member,

return our book "{$book->getTitle()}", you have borrowed.

Your librarian Pvt. Vasquez
```

As you can see, sending messages in ATK14 is no big deal.
