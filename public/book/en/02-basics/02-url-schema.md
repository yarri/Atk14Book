URL schema
==========

Every URL in an ATK14 application (except URLs pointing to `/public/`) must contain:

 * a language code
 * a controller name
 * an action name

Optionally a URL may also contain:

 * parameters
 * a namespace name

Let's look at a concrete URL:

    http://www.atk14.net/en/books/edit/?id=29

 * **en** is the language code
 * **books** is the controller name
 * **edit** is the action name
 * **id=29** is a parameter

If the action name is *index*, it is omitted from the URL.

    http://www.atk14.net/en/books/
    http://www.atk14.net/en/books/?search=boat&offset=10

The *index* action of the *main* controller in the default language is treated as the *frontpage*.

    http://www.atk14.net/

The frontpage in a language other than the default is served like this:

    http://www.atk14.net/fr/

Namespace
---------

Namespaces allow you to create several independent applications that share models and shared templates.
A typical example is an administration interface.

Customers of an e-shop are served by the main application with no named namespace.

    http://www.gibona.net/
    http://www.gibona.net/en/products/detail/?id=29

The administrator has a different view of a product in the *admin* namespace.

    http://www.gibona.net/admin/
    http://www.gibona.net/admin/en/products/detail/?id=29

A single ATK14 project can contain several such namespaces (sub-applications).

The public directory
--------------------

The *public* directory is intended for static content such as CSS stylesheets, JavaScript files, images, etc. URLs pointing to `/public/` are not handled by the ATK14 framework.

    http://www.atk14.net/public/stylesheets/styles.css
    http://www.atk14.net/public/images/atk14.gif
