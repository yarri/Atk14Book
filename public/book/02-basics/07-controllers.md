Kontrolery
==========

Nyní si budeme trošku všímat kontroleru. Je to místo, kde všechna ta ozubená kolečka do sebe zapadají a celé se to pohne, pokud se něco nepodělá :)

Každý kontroler dostává od svého předka do vínku tyto akce:

	error404()	pro HTTP status 404 Not Found
	error403()	pro HTTP status 403 Forbidden
	error500()	pro HTTP status 500 Internal Server Error

Pokud například chceme umožnit přístup k akcím daného kontroleru jenom z jediné IP adresy, provedeme to takto:

	<?php
	class SecretsController extends ApplicationController{

		// tady bude kod nekolika velmi tajnych akci...

		function _before_filter(){
			if($this->request->getRemoteAddr()!="10.20.30.40"){
				$this->_execute_action("error403");
			}
		}
	}

Tento příklad na vás bez přípravy vybalil několik věcí. V *$this->request* je k dispozici objekt zastřešující HTTP požadavek. Metoda *\_before_filter()*
je spuštěna před požadovanou akcí a je v ní možné tok procesu vyřizování požadavku odklonit - v našem případě dochází ke spuštění úplně jiné akce
(*error403*). Může zde však dojít i k přesměrování.

V každé akci je v proměnné *$this->form* vždy k dispozici formulář. Kontroler *BooksController* má v akci *edit()* formulář *EditForm* ze souboru
*app/forms/books/edit_form.php*. Pokud tento soubor nebude existovat, bude v *$this->form* k dispozici alespoň prázdný formulář
(instance *ApplicationForm*). Časem sami zjistíte, jak se může hodit i formulář bez jediného políčka.

V proměnné *$this->params* jsou k dispozici všechny parametry HTTP požadavku z GET a/nebo POST. Jedná se o instanci třídy *Dictionary*, která nabízí metody pro příjmenou práci. Ve zkratce snad jenom tolik:

	<?php
	// ...
	$this->params->defined("id"); // true, pokud se v parametrech nachazi id
	$this->params->getValue("id"); // vrati hodnotu parametru id tak, jak se v pozadavku nachazi nebo vrati null, pokud zde parametr id neni
	$this->params->getInt("int"); // vrati hodnotu parametru id pretypovanou na integer nebo vrati null, pokud zde parametr id neni

to be continued...
