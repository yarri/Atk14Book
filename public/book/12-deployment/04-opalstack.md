Instalace aplikace na Opalstack
===============================

Oblíbený [webhoster Opalstack](https://www.opalstack.com/) nabízí na svých hostingových serverech rozumné prostředí s PHP, Apachem, Postgresql a ssh přístupem.
Nainstalovat zde ATK14 aplikaci a provozovat ji není vůbec žádný velký problém.

Představte si, že Jan "Kinky" Kučera přistupuje na Opalstack jako uživatel snapper a má za úkol rozjet aplikaci www.flicker.net - revoluční službu
pro sdílení nepříjemných zážitků.

Pojďme se podívat, co všechno musí Kinky udělat, aby svou ATK14 aplikaci nainstaloval na Opalstack.

Úvodní příprava prostředí na serveru
------------------------------------

Tento krok je nutné provést pouze jednou. Při instalaci druhé a další ATK14 aplikace jej už neprovádíme.

Kinky vytvoři na přiděleném opalstack serveru adresář $HOME/bin, kam nalinkuje binárku php v preferované verzi.

    [kinky@notebook ~]$ ssh snapper@snapper.opalstacked.com
    [snapper@opal6 ~]$ mkdir bin
    [snapper@opal6 ~]$ ln -s /usr/bin/php83 ~/bin/php

Do ~/.bash_profile si novou cestu přidá do PATH a nastaví proměnnou prostředí ATK14_ENV.

    # .bash_profile

    # Get the aliases and functions
    if [ -f ~/.bashrc ]; then
            . ~/.bashrc
    fi

    # User specific environment and startup programs

    PATH=$HOME/bin:$PATH
    export PATH

    ATK14_ENV=production
    export ATK14_ENV

Rovněž na začátek crontabu doplní nastaveni PATH a ATK14_ENV.

    [snapper@opal6 ~]$ crontab -e

    MAILTO=jan.kucera@example.com
    ATK14_ENV=production
    PATH=/home/snapper/bin:/usr/local/bin:/usr/bin:/usr/local/sbin:/usr/sbin
    CRON_TZ=Europe/Prague

Vytvoření aplikace, site a databáze
-----------------------------------

V control panelu na Oplastacku Kinky vytvoří aplikaci *filcker* typu *PHP-FPM Apache* s preferovanou verzí PHP, tedy 8.3.

Aplikace bude umístěna do adresáře /home/snapper/apps/filcker. Kinky si zkontroluje existenci tohoto adresáře a vyprázdní ho, pokud obsahuje nějaký vzorový index soubor.

Dále v control panelu přidá nové domény flicker.net a www.flicker.net.

V dialogu *Add site* založí aplikaci názvem (Site name) *flicker_production*. K site připojí domény flicker.net a www.flicker.net a aplikaci flicker.

Nyní vytvoří PostgreSQL uživatele flicker_production a databázi flicker_production. Přístup do této databáze bude umožněn právě tomu novému uživateli.

Heslo databázového uživatele flicker_production Opalstack se zpožděním zveřejní v nástroji *Notice Log*. Heslo si Kinky poznamená a z Notice Logu jej smaže.

Recept pro deployment do produkce
---------------------------------

Kinky zapíše recept pro deployment do produkce do souboru config/deploy.yml.

    [kinky@notebook ~/projects/filcker]$ vim config/deploy.yml

    # file: config/deploy.yml
    production:
      url: "https://www.flicker.net/"
      user: "snapper"
      env: "PATH=/home/{{user}}/bin:$PATH"
      server: "{{user}}.opalstacked.com"
      directory: "/home/{{user}}/apps/flicker/"
      deploy_repository: "{{user}}@{{server}}:repos/flicker.git"
      before_deploy:
      - "@local composer update"
      - "@local npm install"
      - "@local gulp"
      - "@local gulp admin"
      rsync: 
      - "public/dist/"
      - "public/admin/dist/"
      - "vendor/"
      after_deploy:
      - "./scripts/migrate && ./scripts/delete_temporary_files dbmole_cache"

Přenos aplikace do produkce
---------------------------

ATK14 framework obsahuje nástroj scripts/initialize_deployment_stage, který na základě daného receptu v config/deploy.yml sestaví shellové příkazy pro přenos aplikace na produkční server.

Kinky tedy spustí:

    [kinky@notebook ~/projects/filcker]$ ./scripts/initialize_deployment_stage production

Pečlivě prozkoumá, jaké příkazy jsou vypsány. Vypadá to dobře a tak nebojácně zadáva:

    [kinky@notebook ~/projects/filcker]$ ./scripts/initialize_deployment_stage production | sh

Uf. Tentokrát dopadlo všechno dobře, proto se Kinky zase uvolňuje a jeho krevní tlak se pozvolna vrácí zpět do normálu.

Kdyby něco dobře nedopadlo, pokusil by se Kinky zjistit, na jakém příkazu proces selhal, provedl by opravný zásah a pokračoval od problematického příkazu dále.

Konfigurace a naplnění produkční databáze
-----------------------------------------

Kinky se přihlásí do produkční instalace, kde založí lokální soubor s konfigurací napojení do databáze. Do souboru vloží heslo, které si poznačil dříve.

Všimněte si, že vůbec není nutné zadávat konfiguraci pro testovací a vývojovou databázi. Do těch se v produkci napojovat nebudeme.

    [kinky@notebook ~/projects/filcker]$ ./scripts/shell production
    [snapper@opal6 ~/apps/flicker$ vim local_config/database.yml

    # file: local_config/database.yml
    production:
      host: 127.0.0.1
      database: "filcker_production"
      username: "filcker_production"
      password: "DatabasePassword123"

Kinky teď může otestovat, že se do produkční databáze připojí:

    [snapper@opal6 ~/apps/flicker]$ ./scripts/dbconsole

Pokud vše klapne, spustí migrace:
    
    [snapper@opal6 ~/apps/flicker]$ ./scripts/migrate

A je hotovo.

Deployment
----------

Následný deployment je pak snadný jako facka. Kinky a jeho kolegové v klidu vyvíjejí na svých noteboocích a jakmile se rozhodnou svou práci zvěřejnit v produkci, spustí:

    [kinky@notebook ~/projects/filcker]$ ./scripts/deployment production

Tohleto se Kinkymu vážně podařilo! :)
