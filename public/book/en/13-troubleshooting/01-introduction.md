Procedures and tips for finding the bug
=======================================

The recommendations below are listed in order of importance.

### Run migrations

It's possible that the error you're dealing with is related to not having all database schema migrations applied. Check:

```shell
$ ./scripts/migrate
```

### Check submodules

Are you versioning with git? And is ATK14 included in your project as a submodule? Make sure you have the current version.

```shell
$ git submodule update
```

### Install Tracy

[_Tracy_](https://tracy.nette.org/) is an excellent debugging tool. Install it. [Here's a guide.](http://forum.atk14.net/cs/topics/detail/?id=100)

### Compile all templates

The _compile\_all\_templates_ script will attempt to compile all templates. This makes it easy to find a template with a syntax error.

```shell
$ ./scripts/compile_all_templates
```

### php.ini settings

In a development environment it is important to have error display enabled. Open the php.ini file:

```shell
$ sudo mcedit /etc/php5/apache2/php.ini
```

And verify that it has:

```ini
display_errors = On
```

Remember that if you change settings in php.ini, you need to restart Apache.

```shell
$ sudo service apache2 restart
```

### Inspect the log

Examine the Apache error log. This command shows the last 100 lines in the error log:

```shell
$ tail -100 /var/log/apache2/error.log
```

Or watch new lines appear in real time:

```shell
$ tail -f /var/log/apache2/error.log
```

### Clear temporary files

If the error still can't be found, delete all temporary files.

```shell
$ ./scripts/delete_temporary_files
```

### Check the installation

ATK14 includes the _check\_installation_ script for verifying the installation. Run it and if anything looks wrong, fix it.

```shell
$ ./scripts/check_installation
```

### Wipe and restore the database

The following procedure completely wipes and then restores the database.

This can come in handy when all other approaches have failed.

```shell
$ ATK14_ENV=development ./scripts/destroy_database_objects
$ ATK14_ENV=development ./scripts/migrate
```

Note, however, that you will lose all data from the database that was not created during migrations.

### Missing mod_rewrite

If the Apache error log shows something like:

```text
Invalid command 'RewriteEngine', perhaps misspelled or defined by a module not included in the server configuration
```

enable mod_rewrite with:

```shell
sudo a2enmod rewrite
```

and restart Apache:

```shell
service apache2 restart
```
