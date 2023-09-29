Stručný úvod do deploymentu
===========================

Hlavní myšlenkou tvůrce frameworku ATK14 je:

> Pokud už máš kladivo, použij kladivo.

Hlavní myšlenkou nástroje pro deployment aplikací věstavěného ve frameworku Atk14 je:

> Deployment je realizován gitem &mdash;
> pushnutím aktuálního HEADu do deploy repozitáře, který je umístěn na produkčním serveru.
> A vše ostatní se doskriptuje :)

Není pochyb o tom, že oním kladivem je git!

Nutné předpoklady pro úspěšný deployment jsou:

- aplikace je verzovná v gitu,
- ssh přístup na produkční server a
- na produkčním serveru je nainstalován git.

Konfigurace je uložena v souboru ```config/deploy.yml``` a ve své nejjednodušší podobě může vypadat například takto:

    # file: config/deploy.yml
    production:
      server: "alpha.example.com"
      user: "deploy"
      directory: "/home/deploy/webapps/myapp/"
      deploy_repository: "/home/deploy/repos/myapp.git"

V tomto minimalistickém konfiguračním souboru je toliko zapsáno, že:

1. aplikace je nainstalována na serveru alpha.example.com,
2. na server se přistupuje uživatelem deploy,
3. aplikace je umístěna v adresáři /home/deploy/webapps/myapp/ a
4. aplikace byla při instalaci na produkční server naklonována z repozitáře /home/deploy/repos/myapp.git, přičemž se defaultně očekává, že se v produkci nacházíme ve větvi *master*.

A toto je konfirace pro jediné umístění - *deployment stage*, které je označeno *production*. V praxi bývá obvyklejší, že existuje několik deployment stages (např. preview, acceptation a production).

Samotný deployment, který se spustí příkazem

    ./scripts/deploy

..., provede následující kroky:

1. vytvoří git remote nazvaný *production*, pokud již neexistuje:

        git remote add production deploy@alpha.example.com:/home/deploy/repos/myapp.git

2. pushne do remote production do větve *master* aktuální HEAD z aktuální větve:

        git push production master:master

3. na serveru alpha.example.com budou v adresáři /home/deploy/webapps/myapp/ postupně provedeny tyto příkazy:

        git checkout master && git fetch origin && git reset --hard origin/master
        git submodule init && git submodule update
        ./scripts/migrate && ./scripts/delete_temporary_files dbmole_cache

Pokud cokoli selže, činnost skriptu je ukončena, je zobrazen popis chyby a případně i popis řešení na její odstranění.
