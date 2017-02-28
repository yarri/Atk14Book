ATK14 Book
==========

Here is a book about a brilliant PHP framework ATK14!

Visit http://book.atk14.net/ to see it alive.

Installation
------------

```bash
git checkout https://github.com/yarri/Atk14Book.git
cd Atk14Book
git submodule init
git submodule update
composer install
./scripts/create_database
./scripts/migrate
```
If you are experiencing a trouble make sure that all requirements are met: <http://book.atk14.net/czech/installation%3Arequirements/>

Installing optional 3rd party libraries
---------------------------------------

```bash
composer update
```

If you don't have the Composer installed, visit http://www.getcomposer.org/

Front-end Assets Installation
-----------------------------
#### Install dependencies.
With [Node.js](http://nodejs.org) and npm installed, run the following one liner from the root of your Skelet application:
```bash
$ npm install -g gulp && npm install -g bower && npm install && bower install
```

This will install all the tools you will need to serve and build your front-end assets.

### Run initial build
Run initial Gulp build process for presentation and admininstration.
```bash
$ gulp && gulp admin
```

### Serve / watch
```bash
$ gulp serve
```

This outputs an IP address you can use to locally test and another that can be used on devices connected to your network.

Starting development webserver
------------------------------

```bash
./scripts/server
```

And now you may find running Atk14Book on http://localhost:8000.
