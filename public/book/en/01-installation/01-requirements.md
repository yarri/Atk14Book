Requirements
============

### Operating system

ATK14 requires a Unix-like operating system — Linux, [FreeBSD](http://www.freebsd.org/), or similar.

This chapter describes installation on [Ubuntu](http://www.ubuntu.com/) 22.04 or newer.

### Installing software packages

You will need:

* _php_ 7.1 or newer (ideally 8.*) with the relevant extensions
* _git_
* _postgresql_

Optionally useful:

* _gettext_ and _poedit_ for multilingual applications
* _apache web server_, if the built-in development server is not enough
* _rsync_ for deploying the application to a production server

```bash
sudo apt install php php-cli php-pgsql php-json php-readline php-mcrypt php-gd php-xml
sudo apt install git
sudo apt install composer
sudo apt install postgresql postgresql-client
sudo apt install apache2 libapache2-mod-php
sudo apt install gettext poedit
sudo apt install rsync

```

### Gettext setup

Check that `/etc/locale.gen` (on some systems `/var/lib/locales/supported.d/local`) contains the required locales, for example:

```text
en_US.UTF-8 UTF-8
cs_CZ.UTF-8 UTF-8
```

If they are missing, add them and run:

```bash
sudo locale-gen
```

### Apache setup

You need to enable the Rewrite module:

```bash
sudo a2enmod rewrite
```

### PostgreSQL setup

In most cases it is enough to start PostgreSQL with the default configuration and there is nothing else to set up. If you run into trouble, useful tips can be found at http://forum.atk14.net/topic/database/konfigurace-postgresql-120/
