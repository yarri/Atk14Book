Installing the skelet
=====================

Now you are ready to install ATK14 skelet for your new ATK14 web application.
It is presumed that you put all your projects into the $HOME/projects/ directory and the new app will be named myapp.

	$ cd $HOME/projects/
	$ wget -O atk14_init.sh https://raw.github.com/yarri/Atk14/master/installation/atk14_init.sh
	$ chmod +x ./atk14_init.sh
	$ mkdir myapp
	$ cd myapp
	$ ../atk14_init.sh

You should follow all the instructions given by the script atk14_init.sh

Run browser and point to http://myapp.localhost/. If it's working than congrats!

For a next application you should omit the Requirements chapter.

Put the freshly installed skelet on Git
---------------------------------------

	$ cd $HOME/projects/myapp/
	$ rm -rf ./atk14
	$ git init
	$ git submodule add git://github.com/yarri/Atk14.git ./atk14
	$ echo '!tmp/README' >> .gitignore
	$ echo 'tmp/*' >> .gitignore
	$ echo '!log/README' >> .gitignore
	$ echo 'log/*' >> .gitignore
	$ git -A
	$ git commit -m 'initial commit'

Setting up a remote repository
-----------------------------

	$ git clone --bare $HOME/projects/myapp /tmp/myapp.git
	$ tar -zcf /tmp/myapp.git.gz /tmp/myapp.git

... to be continued
