Hello World
===========

Here we show how to create a simple *Hello World* application.

With a template
---------------

Add a *hello_world* action to the *main* controller, ...

```php
<?php
// file: app/controllers/main_controller.php
class MainController extends ApplicationController{
  function hello_world(){ } // akce je prazdna
}
```

create the template in the right place...

```smarty
{* file: app/views/main/hello_world.tpl *}
Hello World!
```

and the greeting will be waiting for you at http://myapp.localhost/en/main/hello_world/

Without a template
------------------

```php
<?php
// file: app/controllers/main_controller.php
class MainController extends ApplicationController{
  function hello_world(){
    $this->render_template = false;
    $this->response->write("Hello World!");
  }
}
```

You'll find the greeting at the same URL.
