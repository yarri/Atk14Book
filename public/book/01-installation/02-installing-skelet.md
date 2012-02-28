Instalace kostry
================

Now you are ready to install ATK14 skelet for your new web application. I presume that Apache has the Document Root at /var/www/. So /var/www/myapp/ should be the fine folder for your app.

	$ cd /var/www/
	$ sudo wget -O atk14_init.sh https://raw.github.com/yarri/Atk14/master/installation/atk14_init.sh
	$ export MY_ID=`id -u`
	$ sudo mkdir myapp
	$ sudo chown $MY_ID myapp
	$ cd myapp
	$ bash ../atk14_init.sh

Run browser and point to http://myapp.localhost/. If it's working than congrats!

For a next application you should omit the Requirements chapter.
