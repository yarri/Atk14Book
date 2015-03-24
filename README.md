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
composer.phar install
./scripts/create_database
./scripts/migrate
```

### Installing optional 3rd party libraries

```bash
composer update
```

If you don't have the Composer installed, visit http://www.getcomposer.org/

### Front-end Assets Installation

#### Install dependencies.
```bash
# Node Version manager
wget -q -O - https://raw.github.com/creationix/nvm/master/install.sh | sh
echo -e "\n. ~/.nvm/nvm.sh" >> ~/.bashrc && . ~/.nvm/nvm.sh
# Node.js
nvm install 0.10
# Bower
npm install -g bower
# Grunt
npm install -g grunt-cli
```
#### Install skelet front-end dependencies via Bower.
```bash
bower install
```
#### Install build dependencies.
```bash
npm install
```
#### Build.
```bash
grunt dist
```

Starting development webserver
------------------------------

```bash
./scripts/server
```

And now you may find running Atk14Book on http://localhost:8000.
