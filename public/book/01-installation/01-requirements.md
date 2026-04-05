Požadavky
=========

### Operační systém

ATK14 vyžaduje unixový operační systém — Linux, [FreeBSD](http://www.freebsd.org/) a podobné.

Tato kapitola popisuje instalaci na [Ubuntu](http://www.ubuntu.com/) 22.04 nebo novějším.

### Instalace softwarových balíčků

Nezbytně potřebuješ

* _php_ 7.1 nebo novější (optimálně 8.*) s příslušnými rozšířeními
* _git_
* _postgresql_

Volitelně se může hodit

* _gettext_ a _poedit_ pro vícejazyčné aplikace
* _apache web server_, pokud ti zabudovaný vývojový server nestačí
* _rsync_ pro nasazení aplikace na produkční server

```bash
sudo apt install php php-cli php-pgsql php-json php-readline php-mcrypt php-gd php-xml
sudo apt install git
sudo apt install composer
sudo apt install postgresql postgresql-client
sudo apt install apache2 libapache2-mod-php
sudo apt install gettext poedit
sudo apt install rsync

```

### Nastavení Gettext

Zkontroluj, že soubor `/etc/locale.gen` (na některých systémech `/var/lib/locales/supported.d/local`) obsahuje požadované lokalizace, například:

```text
en_US.UTF-8 UTF-8
cs_CZ.UTF-8 UTF-8
```

Pokud chybí, přidej je a spusť:

```bash
sudo locale-gen
```

### Nastavení Apache

Je potřeba povolit modul Rewrite:

```bash
sudo a2enmod rewrite
```

### Nastavení PostgreSQL

Ve většině případů stačí PostgreSQL spustit s výchozí konfigurací a nic dalšího řešit nemusíš. Pokud přesto narazíš na potíže, užitečné tipy najdeš na http://forum.atk14.net/topic/database/konfigurace-postgresql-120/
