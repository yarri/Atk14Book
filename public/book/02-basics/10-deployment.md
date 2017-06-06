Deployment
==========

Součástí frameworku ATK14 je nástroj pro instalaci změn ve vašem projektu do produkce.

Instalační skript si pořebné hodnoty přečte z konfiguračního souboru.

    # file: config/deploy.yml
    preview:
      server: "alpha.clevernet.org"
      user: "deploy"
      directory: "/home/deploy/apps/preview.myapp.net/"
      deploy_repository: "deploy@alpha.clevernet.org:repos/myapp.net/preview.git"
      before_deploy:
      - "@local composer update"
      - "@local bower update"
      - "@local gulp"
      rsync: 
      - "./vendor/"
      - "./public/dist/"
      after_deploy:
      - "./scripts/migrate"
      - "./scripts/delete_temporary_files dbmole_cache"

    production:
      directory: "/home/deploy/apps/www.myapp.net/"
      deploy_repository: "deploy@alpha.clevernet.org:repos/myapp.net/production.git"

Ukázkový konfigurační soubor obsahuje předpisy pro instalace do dvou produkčních míst (stages). Velmi často se totiž stává, že nové změny v aplikaci chcete zákazníkovi ukázat dříve, než ji uvidí všichni ostatní.
V naší konfiguraci je proto stage preview a production. První stage souží jako vzor pro všechny ostatní. Takže ve stage production je možné uvádět pouze ty hodnoty, které se liší od preview.

Co se tedy stane, když spustíte na svém vývojovém notebooku příkaz ```./scripts/deploy preview```?

- Z konfiguračního souboru config/deploy.yml jsou přečteny potřebné údaje pro deployment do stage preview
- Jsou spuštěny before_deploy příkazy

  Zde jsou celkem tři příkazy a všechny jsou prefixovány @local, tzn. spouštějí se na lokalním počítači (vašem notebooku)

  - composer update
  - bower update
  - gulp

  <br>  

- Pokud chybí, je založen git remote s názvem _preview_ a s URL ```deploy@alpha.clevernet.org:repos/myapp.net/preview.git```
- Aktuální changeset je pushnut do remote preview do větve master
- Ve vzdáleném adresáři /home/deploy/apps/preview.myapp.net/ je pomocí ```git reset --hard``` nastaven aktuální HEAD z origin/master
- Pomocí rsync je přenesen obsah vybraných adresářů z vašeho notebooku na server

  Tyto odrasáře obsahují soubory, které se neverzují gitem, ale jejich obsah je k fungování aplikace vyžadován.
  
  - adresář ./vendor/ obsahuje PHP knihovny nainstalované přes composer
  - v adresáři ./public/dist/ se nacházejí CSS a javascriptové soubory vzniklé nějakým automatickým sestavovacím procesem (např. Gulp)

  <br>  

- Jsou provedeny after_deploy příkazy

  V příkladu jsou celkem dva a žádný z nich není prefixován @local, proto jsou oba provedeny na vzdáleném serveru

  - ./scripts/migrate
  - ./scripts/delete_temporary_files dbmole_cache


