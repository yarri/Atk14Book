Requirements
============

### Operating System

A UNIX-like operating system is required - Linux, [FreeBSD](http://www.freebsd.org/)...

This chapter describes ATK14 installation on [UBUNTU](http://www.ubuntu.com/) 15.10 or higher.

### Installing software packages

You will definitely need

* _php_ 5.3 or newer (optimally 7.0) with some addons
* _git_
* _postgresql_

Optionally you may want to install

* _gettext_ and _poedit_ for multilanguage applications
* _apache web server_ if you find out that the built-in development web server is not good for you
* _rsync_ is useful for deploying an application into the production


```bash
sudo apt-get install php php-cli php-pear php-pgsql php-json php-readline php-mcrypt php-gd
sudo apt-get install git
sudo apt-get install postgresql postgresql-client
sudo apt-get install apache2
sudo apt-get install gettext poedit
sudo apt-get install rsync
```

Install Composer. Visit <https://getcomposer.org/doc/00-intro.md#installation-linux-unix-osx> for installation instructions.

For testing you need to have PHPUnit installed globally using Composer.
```
composer global require "phpunit/phpunit=4.8.*"
```

### Configuring Postgresql in development

Postgresql access control file pg\_hba.conf should look like this. The file may be found at /etc/postgresql/9.3/main/pg\_hba.conf

```text
# TYPE  DATABASE    USER       CIDR-ADDRESS  METHOD
local   all         postgres                 ident
host    all         postgres   127.0.0.1/32  ident
host    all         postgres   ::1/128       ident
local   sameuser    all                      md5
host    sameuser    all        127.0.0.1/32  md5
host    sameuser    all        ::1/128       md5
```

These lines say that administer (postgres) can connect to any database but only when he is logged as postgres in the system, other user can connect only to a database with the same name and must provide a correct password.

Now restart the server.

```bash
sudo service postgresql restart
# or
sudo /etc/init.d/postgresql restart
```

### Configuring Gettext

Be sure that /var/lib/locales/supported.d/local contains these lines:

```text
en_US.UTF-8 UTF-8
cs_CZ.UTF-8 UTF-8
```

If it doesn't add these locales and then run:

```bash
sudo locale-gen
```

### Configuring Apache webserver

Mod Rewrite needs to be enabled.

```bash
sudo a2enmod rewrite
```



