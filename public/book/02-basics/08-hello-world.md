Hello World
===========

Zde si ukážeme, jak vytvořit ukázkovou aplikaci *Hello World*.

Varianta se šablonkou
---------------------

Do kontroleru *main* přidáme akci *hello_world*, ...

	<?php
	// file: app/controllers/main_controller.php
	class MainController extends ApplicationController{
		function hello_world(){ } // akce je prazdna
	}

na tom správném místě vytvoříme šablonu, ...

	{* file: app/views/main/hello_world.tpl *}

	Hello World!

a pozdrav nás čeká na adrese http://myapp.localhost/en/main/hello_world/

Varianta bez šablonky
---------------------

	<?php
	// file: app/controllers/main_controller.php
	class MainController extends ApplicationController{
		function hello_world(){
			$this->render_template = false;
			$this->response->setContentType("text/plain");
			$this->response->write("Hello World!");
		}
	}

Pozdrav najdete na stejné adrese.
