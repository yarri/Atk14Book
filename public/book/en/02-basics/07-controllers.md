Controllers
===========

Let's take a closer look at controllers. This is where all the cogs mesh together and the whole thing moves — provided nothing goes wrong :)

Every controller inherits the following actions from its parent:

	error404()	for HTTP status 404 Not Found
	error403()	for HTTP status 403 Forbidden
	error500()	for HTTP status 500 Internal Server Error

For example, if you want to restrict access to the actions of a given controller to a single IP address, you do it like this:

	<?php
	class SecretsController extends ApplicationController{

		// tady bude kod nekolika velmi tajnych akci...

		function _before_filter(){
			if($this->request->getRemoteAddr()!="10.20.30.40"){
				$this->_execute_action("error403");
			}
		}
	}

This example introduced several things at once. `$this->request` provides an object wrapping the HTTP request. The *\_before_filter()* method
is executed before the requested action and can redirect the request flow — in our case it triggers a completely different action
(*error403*). It can also perform a redirect.

In every action, `$this->form` always holds a form object. The *BooksController* has an *EditForm* from the file
*app/forms/books/edit_form.php* available in its *edit()* action. If this file does not exist, `$this->form` will at least contain an empty form
(an instance of *ApplicationForm*). You'll discover over time how useful a form with no fields can be.

`$this->params` holds all HTTP request parameters from GET and/or POST. It is an instance of the *Dictionary* class, which offers convenient methods for working with the data. Briefly:

	<?php
	// ...
	$this->params->defined("id"); // true, pokud se v parametrech nachazi id
	$this->params->getValue("id"); // vrati hodnotu parametru id tak, jak se v pozadavku nachazi nebo vrati null, pokud zde parametr id neni
	$this->params->getInt("id"); // vrati hodnotu parametru id pretypovanou na integer nebo vrati null, pokud zde parametr id neni

to be continued...
