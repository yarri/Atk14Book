HTTP request handling
=====================

Let's take a concrete URL:

    http://www.atk14.net/en/books/edit/?id=29

What happens when you visit it?

The ATK14 framework creates and prepares a controller — an instance of the *BooksController* class from the file:

    app/controllers/books_controller.php

The controller is assigned the appropriate form — an instance of the *EditForm* class from the file:

    app/forms/books/edit_form.php

Next, the *edit()* method is called on the controller and the template is rendered:

    app/views/books/edit.tpl

The template content is rendered inside the layout:

    app/layouts/default.tpl

The output is sent to the happy user.

The great thing about ATK14 is that you just follow the naming convention and everything starts working automatically — without a single line of configuration. This is known as the *convention over configuration* principle.
