Deployment ATK14 aplikace
=========================

Framework ATK14 obsahuje nástroj pro deployment aktuální verze aplikace do produkce. Jedná se o přírůstkový deployment (continuous deployment). Tedy v produkci nám už běží aplikace v nějaké verzi a my tam pomocí tohoto nástroje nahrajeme aktuální verzi.

Framework ATK14 dále obsahuje i nástroj pro úvodní přenos aplikace do produkce ve chvíli, kdy na produkci nic není. Ale o tom až později.

Jedna ATK14 aplikace může být nainstalována do více produkčních prostředí (production stages). Jedna produkční instalace může např. sloužit jako preview, na kterém se klientům ukazují nové věci, jiná produkční instalace může být použita jako staging, na kterém se testuje nová verze před instalací na skutečnou produkci, atd.

Na všechny tyto produkční instalace se ATK14 framework dívá jako na produkci. Tedy proměnná prostředí ATK14_ENV musí být na všech produkčních instalacích nastavena na production - ATK14_ENV=production.

Pokud se zde mluví o verzi aplikace, je tím myšlen konkrétní git commit hash, který je v produkci nasazen nebo který chceme nasadit do produkce. A tím se dostaváme k tomu, že důležitým pomocníkem při deploymentu je právě git.

Požadavky na produkční server
-----------------------------

* Apache se zapnutým mod_rewrite
* PHP
* PostgreSQL databáze
* ssh přístup
* git

Produkční repozitář
-------------------

Každá produkční instalace má svůj vlastní git repozitář (v režimu bare), který má pouze jednu větev master, do které se při deploymentu pushuje požadovaný commit hash jako nový HEAD a na ten se následně produkční instalace resetuje (git reset --hard origin/master).

Commit hash, který dostaneme do produkce je ten, na kterém právě stojíme na svém vývojovém notebooku, když spouštíme proces deploymentu. Je jedno, na jaké se necházíme větvi. Můžeme deployovat z větve např. master, primary, develop či production. To už záleží na vývojových zvyklostech.

Během deploymentu se do produkčního repozitáře nepushuje s přepínačem force. Tzn. že commit hash, který je pushován, musí mít návaznost na HEAD větve master v produkčním repozitáři. Pokud se push nepodaří, je deployment proces ukončen a vypíše se návod, jak situaci vyřešit ručně.
 
Konfigurační soubor
-------------------

Konfigurace je uložena v souboru ```config/deploy.yml``` a ve své nejjednodušší podobě může vypadat například takto:

    # file: config/deploy.yml
    production:
      server: "alpha.example.com"
      user: "deploy"
      directory: "/var/www/myapp/"
      deploy_repository: "/home/deploy/repos/myapp.git"

V tomto konfiguračním souboru je toliko zapsáno, že:

1. je zde posána konfigurace pouze pro jednu produkční instalaci označenou *production*,
2. aplikace je nainstalována na serveru alpha.example.com,
3. na server se přistupuje uživatelem deploy,
4. aplikace je umístěna v adresáři /var/www/myapp/ a
5. aplikace se checkoutuje z produkčního repozitáře /home/deploy/repos/myapp.git.

Ve skutečnosti však toho bude konfigurační souboru obsahovat více. Následující příklad se už více podobá tomu, co vidíme v praxi.

    # file: config/deploy.yml
    production:
      url: "https://www.myapp.com/"
      server: "alpha.example.com"
      user: "deploy"
      env: "PATH=/home/deploy/bin:$PATH"
      directory: "/var/www/myapp/"
      deploy_repository: "/home/deploy/repos/myapp.git"
      before_deploy:
      - "@local composer update"
      - "@local npm install"
      - "@local gulp"
      - "@local gulp admin"
      rsync:
      - "public/admin/dist/"
      - "vendor/"
      after_deploy:
      - "./scripts/migrate && ./scripts/delete_temporary_files dbmole_cache"

- Hodnota url je pouze informační. Pro přehlednost a případné odhalování problémů je prostě vhodné uvést, na jaké URL daná produkční instalace běží.
- Pomocí env můžeme nastavovat proměnné prostředí.
- Hodnoty before_deploy a after_deploy obsahují shellové příkazy, které budou provedeny před deploymentem, resp. po něm. Pokud je příkaz prefixován značkou `@local`, bude proveden lokálně (např. na vývojovém notebooku), jinak je příkaz proveden na produkčním serveru.
- Hodnota rsync obsahuje seznam adresářů, jejíchž obsah se má do produkční instalace synchronizovat pomocí rsync. Jedná se o adresáře, které se neverzují se zdrojovým kódem aplikace.

Spuštění deploymentu
--------------------

Před samotným deploymentem je dobré se ujistit, ža commit hash, na kterém stojíme, je ten, který chceme nasadit do produkce.

Pak už stačí zadat:

    [john@asterix ~/projects/myapp]$ ./scripts/deploy production

Proces deploymentu začne provádět postupně příkazy, o kterých informuje na svém výstupu.

Mimo jiné je provedeno následující:

1. Vytvoří se git remote nazvaný *production*, pokud ještě neexistuje:

    git remote add production deploy@alpha.example.com:/home/deploy/repos/myapp.git

2. Pushne se do remote production do větve *master* aktuální HEAD z aktuální větve (dejme tomu develop):

    git push production develop:master

3. Na serveru alpha.example.com budou v adresáři /var/www/myapp/ provedeny tyto příkazy:

    git checkout master && git fetch origin && git reset --hard origin/master
    git submodule init && git submodule update

Případné další příkazy, které budou provedeny, závisí na konfiguraci v config/develop.yml.

Pokud cokoli selže, činnost skriptu je ukončena, je zobrazen popis chyby a případně i popis řešení na její odstranění.

Konfigurace pro více produkčních prostředí
------------------------------------------

    # file: config/deploy.yml
    production:
      url: "https://www.myapp.com/"
      server: "alpha.example.com"
      user: "deploy"
      env: "PATH=/home/deploy/bin:$PATH"
      directory: "/var/www/myapp/"
      deploy_repository: "/home/deploy/repos/myapp.git"
      before_deploy:
      - "@local composer update"
      - "@local npm install"
      - "@local gulp"
      - "@local gulp admin"
      rsync:
      - "public/admin/dist/"
      - "vendor/"
      after_deploy:
      - "./scripts/migrate && ./scripts/delete_temporary_files dbmole_cache"

    staging:
      url: "https://staging.myapp.com/"
      directory: "/var/www/myapp_staging"
      deploy_repository: "/home/deploy/repos/myapp_staging.git"

Deployment do produkce:

    [john@asterix ~/projects/myapp]$ ./scripts/deploy production

Deployment na staging:

    [john@asterix ~/projects/myapp]$ ./scripts/deploy staging
