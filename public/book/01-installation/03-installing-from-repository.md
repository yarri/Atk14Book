Installing from a repository
============================

Consider that you are about to install application running at <http://www.atk14.net/> on your computer.
This application has a public repositoray on Github at <https://github.com/yarri/Atk14Net>.

### Getting the source code

	$ cd /var/www/
	$ sudo mkdir atk14net
	$ cd atk14net
	$ git clone git://github.com/yarri/Atk14Net.git ./
	$ git submodule init
	$ git submodule update

	$ chmod 777 tmp
	$ chmod 777 log

### Preparing database

	$ ./scripts/create_database
	$ ./scripts/initialize_database
	$ ./scripts/migrate

Optionally you may execute the following command

	$ ./scripts/pgpass_record >> ~/.pgpass

And then you can access the database`s console with no password typing

	$ ./scripts/dbconsole

### Apache configuration

Run this command and follow the listed instructions.
	
	$ ./scripts/virtual_host_configuration

Now visit http://atk14net.localhost/ in you browser. Is it working? Nice! Happy coding.
