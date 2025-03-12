Vylaďování konfigurace
======================

Referencování hodnot a vlastní hodnoty
--------------------------------------

    # file: config/deploy.yml
    production:
      url: "https://www.myapp.com/"
      site_name: myapp
      server: "alpha.example.com"
      user: "deploy"
      env: "PATH=/home/{{user}}/bin:$PATH"
      directory: "/var/www/{{site_name}}/"
      deploy_repository: "/home/{{user}}/repos/{{site_name}}.git"
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
      site_name: "{{myapp_staging}}"
