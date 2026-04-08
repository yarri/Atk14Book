Prvotní instalace aplikace do produkce
======================================

Poté, co jsi tak precizně popsal produkční instalaci do souboru config/deploy.yml, tě čeká instalace aplikace do tohoto prostředí. Jedná se o jednorázovou akci, která má souvislou řadu úkonů, se kterými ti ale ATK14 framework ochotně pomůže.

Příprava produkčního serveru
-----------------------------

Pokud stojíš před serverem, na kterém zatím neběží ani jedna ATK14 aplikace, proveď pár drobných nastavení.

V .bash_profile si nastav proměnnou prostředí ATK14_ENV na production.

```bash
# .bash_profile
...
ATK14_ENV=production
export ATK14_ENV
```

Pokud je na serveru k dispozici více verzí PHP současně, nalinkuj si preferovanou verzi PHP do $HOME/bin

```shell
mkdir $HOME/bin
ln -s /usr/bin/php83 ~/bin/php
```

a cestu $HOME/bin si přidej do proměnné prostředí PATH v $HOME/.bash_profile.

```bash
# .bash_profile
...
PATH=$HOME/bin:$PATH
export PATH
```

I do crontabu přidáme nastavení proměnných prostředí.

```shell
$ crontab -e

MAILTO=email@example.com
ATK14_ENV=production
PATH=/home/user/bin:/usr/local/bin:/usr/bin:/usr/local/sbin:/usr/sbin
CRON_TZ=Europe/Prague
```

Instalace aplikace na server
---------------------------

ATK14 framework obsahuje skript *initialize_deployment_stage*, který podle konfigurace v config/deploy.yml připraví postup pro instalaci aplikace do dané produkce.

Jeho použití je následující:

```shell
$ ./scripts/initialize_deployment_stage production
```

Klidně to vyzkoušej. Nestane se nic, pouze se vypíší shellové příkazy, které nainstalují aplikaci do daného produkčního prostředí.

Prozkoumej je, a pokud se ti budou zamlouvat, spusť:

```shell
$ ./scripts/initialize_deployment_stage production | sh
```

Pokud všechno dopadne dobře, máš vyhráno. Pokud něco selže, pokus se zjistit, kde nastal problém, proveď opravu a pokračuj ve spouštění příkazů od příslušného místa.

Jakmile máš hotovo, přihlas se na danou produkci.

```shell
$ ./scripts/shell production
```

... a dokonfiguruj aplikaci. Což znamená především konfiguraci připojení do databáze v souboru local_config/database.yml a event. další specifická nastavení v souboru local_config/settings.php.

Konfigurace databáze
--------------------

Na produkci nakonfiguruj připojení k databázi v souboru local_config/database.yml.

```yaml
# file: local_config/database.yml
production:
  host: 127.0.0.1
  database: "database_name"
  username: "database_user"
  password: "password"
```

Ověř funkčnost napojení příkazem:

```shell
$ ./scripts/dbconsole
```

měl bys se úspěšně připojit do databáze.

Konfigurace virtuálního serveru Apache
--------------------------------------

Pro vytvoření konfigurace virtuálního serveru Apache spusť v produkci skript virtual_host_configuration a nech se inspirovat:

```shell
$ ./scripts/virtual_host_configuration
```
