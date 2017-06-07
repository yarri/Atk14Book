Deployment
==========

Součástí frameworku ATK14 je nástroj pro instalaci vašeho projektu do produkce. Pro přenos souborů je použit _git_, pro přenos neverzovaných souborů _rsync_ a na produkční server je nutný _ssh přístup_.
Lze zadat příkazy, které budou spuštěny před nebo po deploymentu na lokalním stroji (váš vývojový notebook) nebo na produkčním serveru. Je tak možné např. zautomatizovat sestavování stylů a skriptů, vytvořit zálohu a pod.

Prohlédněte ukázkový konfigurační soubor. Je v něm popsán deployment do jednoho produkčního prostředí (tzv. stage) s názvem _production_.

    # file: config/deploy.yml
    production:
      user: "deploy"
      server: "venus.universe.org"
      directory: "/home/deploy/apps/www.myapp.net/"
      deploy_repository: "deploy@venus.universe.org:repos/myapp.git"
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

V praxi se ale běžně stává, že existuje více produkčních prostředí &mdash; např. _preview_, _acceptation_... I na to bylo myšleno.

Deployment je spuštěn příkazem ./scripts/deploy

    # deployment do výchozí (první) stage
    $ ./scripts/deploy
  
    # deployment do stage production
    $ ./scripts/deploy production

    # deployment do stage preview
    $ ./scripts/deploy preview
