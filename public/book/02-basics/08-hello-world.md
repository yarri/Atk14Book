Hello World
===========

Na tomto místě si ukážeme, jak vytvořit ukázkovou aplikaci *Hello World*.

Do kontroleru *main* přidáme akci *hello_world*,

	<?php
	// file: app/controllers/main_controller.php
	class MainController extends ApplicationController{
		function hello_world(){ } // akce je prazdna
	}

vytvoříme šablonu na tom správném místě

	{* file: app/views/main/hello_world.tpl *}

	Hello World!

a pozdrav nás čeká na adrese http://myapp.localhost/en/main/hello_world/
