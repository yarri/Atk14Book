Prvotní instalace aplikace do produkce
======================================

Poté, co jsme tak precizně popsali produkční instalaci do souboru config/deploy.yml, nás čeká instalace aplikace do tohoto prostředí. Jedná se o jednorázovou akci, která má souvislou řadu úkonů, se kterými nám ale ATK14 framework ochotně pomůže.

Příprava produkčnéího serveru
-----------------------------

Pokud stojíme před serverem, na kterém zatím neběží ani jedna ATK14 aplikace, provedeme pár drobných nastavení.

V .bash_profile si nastavíme proměnnou prostředí ATK14_ENV na production.

    # .bash_profile
    ...
    ATK14_ENV=production
    export ATK14_ENV

Pokud je na serveru k dispozici více verzí PHP současně, nalinkujeme si preferovanou verzi PHP do $HOME/bin

    mkdir $HOME/bin
    ln -s /usr/bin/php83 ~/bin/php

a cestu $HOME/bin si přídáme do proměnné prostředí PATH v $HOME/.bash_profile.

    # .bash_profile
    ...
    PATH=$HOME/bin:$PATH
    export PATH

I do crontabu přidáme nastavení proměnných prostředí.

    $ crontab -e

    MAILTO=email@example.com
    ATK14_ENV=production
    PATH=/home/user/bin:/usr/local/bin:/usr/bin:/usr/local/sbin:/usr/sbin
    CRON_TZ=Europe/Prague

Instalace aplikace na server
---------------------------

ATK14 framework obsahuje skript *initialize_deployment_stage*, který podle konfigurace v config/deploy.yml připraví postup pro instalaci aplikace do dané produkce.

Jeho použití je následující:

    $ ./scripts/initialize_deployment_stage production

Klidně to vyzkoušejte. Nestane se nic, pouze se vypíší shellové příkazy, které nainstalují aplikaci do dané produkčního prostředí.

Prozkoumejte je, a pokud se vám budou zamlouvat, spusťtě:

    $ ./scripts/initialize_deployment_stage production | sh

Pokud všechno dopadne dobře, máte vyhráno. Pokud něco selže, pokuste se zjistit, kde nastal problém, proveďte opravu a pokračujte ve spouštění příkazů od příslušného místa.

Jakmile máte hotovo, přihlaste se na danou produkci.

    $ ./scripts/shell production

... a dokonfigurujte aplikaci. Což znemaná především konfigurace připojení do databáze v souboru local_config/database.yml a event. další specifická nastavení v souboru local_config/settings.php.

Konfigurace databáze
--------------------

Na produkci nakonfigurujeme připojení k databázi v souboru local_config/database.yml.

    # file: local_config/database.yml
    production:
      host: 127.0.0.1
      database: "database_name"
      username: "database_user"
      password: "password"

Ověříme funkčnost napojení příkazem:

    $ ./scripts/dbconsole

měli bychom se úspěšně připojit do databáze.

Konfigurace virtuálního serveru Apache
--------------------------------------

Pro vytvoření konfigurace virtuálního serveru Apache spusťte v produkci skript virtual_host_configuration a nechejte se inspirovat:

    $ ./scripts/virtual_host_configuration
