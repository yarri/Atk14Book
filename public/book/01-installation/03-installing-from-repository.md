Installing from a repository
============================

Consider that you are about to install application running at <http://www.atk14.net/> on your computer into directory ~/projects/atk14/.
This application has a public repository on Github at <https://github.com/yarri/Atk14Net>

### Installing the source code

  $ cd ~/projects/
  $ mkdir atk14
  $ cd atk14
  $ git clone https://github.com/yarri/Atk14Net.git ./
  $ git submodule init
  $ git submodule update
  $ composer update

  $ chmod 777 tmp
  $ chmod 777 log

### Preparing database

  $ ./scripts/create_database
  $ ./scripts/migrate

You can now enter the database console by typing

  $ ./scripts/dbconsole

### Apache configuration

Run this command and follow the listed instructions
  
  $ ./scripts/virtual_host_configuration

Now visit http://atk14.localhost/ in you browser.

### ... when something went wrong

In case of troubles or after a new installation you should run the following command

  $ ./scripts/check_installation

Outpout may give you a clue to solve a problem.

### Preparing testing database

In order to run tests you have to create and initialize a testing database

  $ ATK14_ENV=TEST ./scripts/create_database
  $ ATK14_ENV=TEST ./scripts/migrate

To enter the test database console, type

  $ ATK14_ENV=TEST ./scripts/dbconsole

### Testing

To run all the application tests, run the following command

  $ ./scripts/run_all_tests

