Requirements
============

### Operating System

A UNIX-like operating system is required - Linux, [FreeBSD](http://www.freebsd.org/)...

This chapter describes ATK14 installation on [UBUNTU](http://www.ubuntu.com/) 22.04 or higher.

### Installing software packages

You will definitely need

* _php_ 7.1 or newer (optimally 8.1) with some addons
* _git_
* _postgresql_

Optionally you may want to install

* _gettext_ and _poedit_ for multilanguage applications
* _apache web server_ if you find out that the built-in development web server is not good for you
* _rsync_ is useful for deploying an application into the production


```bash
sudo apt-get install php php-cli php-pgsql php-json php-readline php-mcrypt php-gd php-xml
sudo apt-get install git
sudo apt-get install composer
sudo apt-get install postgresql postgresql-client
sudo apt-get install apache2 libapache2-mod-php
sudo apt-get install gettext poedit
sudo apt-get install rsync
```

For testing you need to have PHPUnit installed globally using Composer.
```
composer global require --ignore-platform-reqs "phpunit/phpunit ~7.5"
```

### Configuring Gettext

Be sure that /etc/locale.gen (or /var/lib/locales/supported.d/local on some systems) contains your desired locales. For instance:

```text
en_US.UTF-8 UTF-8
cs_CZ.UTF-8 UTF-8
```

If it doesn't, add missing locales and then run:

```bash
sudo locale-gen
```

### Configuring Apache webserver

Mod Rewrite needs to be enabled.

```bash
sudo a2enmod rewrite
```

### Configuring Postgresql

Usually it's ok to leave postgresql running with the default configuration. If you have troubles with Postgresql, you may find useful tips on http://forum.atk14.net/topic/database/konfigurace-postgresql-120/
