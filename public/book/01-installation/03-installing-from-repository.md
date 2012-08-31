Installing from a repository
============================

Consider that you are about to install application running at <http://www.atk14.net/> on your computer into directory ~/projects/atk14/.
This application has a public repository on Github at <https://github.com/yarri/Atk14>

### Installing the source code

	$ cd ~/projects/
	$ mkdir atk14
	$ cd atk14
	$ git clone git://github.com/yarri/Atk14Net.git ./
	$ git submodule init
	$ git submodule update

	$ chmod 777 tmp
	$ chmod 777 log

### Preparing database

	$ ./scripts/create_database
	$ ./scripts/initialize_database
	$ ./scripts/migrate

Optionally you may execute the following command to store the database password

	$ ./scripts/pgpass_record >> ~/.pgpass

Then you can access the database`s console without a password prompt simply by typing

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
	$ ATK14_ENV=TEST ./scripts/initialize_database
	$ ATK14_ENV=TEST ./scripts/migrate
	$ ATK14_ENV=TEST ./scripts/pgpass_record >> ~/.pgpass


