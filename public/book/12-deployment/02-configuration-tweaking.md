Vylaďování konfigurace
======================

Dědění předpisu
---------------

Pokud není specifikováno jinak, platí pravidlo, že všechny další instalační předpisy dědí neuvedené hodnoty od předpisu prvního.

Tím pádem lze konfiguraci z konce předchozí kapitoly zjednodušit tak, že v předpisu pro staging uvedeme pouze hodnoty, které se liší od předpisu pro production.

```yaml
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
```

Abys zkontroloval, jak ve skutečnosti předpis pro staging vypadá, spusť:

```shell
./scripts/deploy --dump staging
```

nebo zkráceně:

```shell
./scripts/deploy -d staging
```

případně si můžeš vypsat konfiguraci pro všechny instalace:

```shell
./scripts/deploy --dump
```

Pokud chceš, aby nějaký předpis dědil od jiného předpisu než prvního, uveď to v hodnotě extends.

Představ si, že máš aplikaci, kterou instaluješ do preview na jiný server, na jiné místo, pod jiného uživatele a chceš, aby byl přístup na web omezen pomocí .htaccess doplněním nastavení, které sis připravil do souboru .htaccess.preview_addon. A zároveň kolegové z maďarské pobočky projevili zájem o své vlastní preview. Konfigurační soubor může vypadat např. takto:

```yaml
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

preview:
  url: "https://preview.myapp.com/"
  server: "preview.example.com"
  user: preview
  env: "PATH=/home/preview/bin:$PATH"
  directory: "/home/preview/apps/myapp_preview/"
  deploy_repository: "/home/preview/repos/myapp_preview.git"
  after_deploy:
  - "cat .htaccess.preview_addon >> .htaccess"
  - "./scripts/migrate && ./scripts/delete_temporary_files dbmole_cache"

preview_hungary:
  extends: preview
  url: "https://hungary-preview.myapp.com/"
  directory: "/home/preview/apps/myapp_hungary_preview/"
  deploy_repository: "/home/preview/repos/myapp_hungary_preview.git"
```

Zase si ověř, jak konfigurace vlastně ve skutečnosti vypadá:

```shell
./scripts/deploy --dump preview_hungary
```

Referencování hodnot a vlastní hodnoty
--------------------------------------

Nějakou hodnotu můžeme vložit do jiné hodnoty zadáním jejího názvu do dvojitých složených závorek. Takže např. následující zápis zafunguje dobře:

```yaml
# file: config/deploy.yml
production:
  url: "https://www.myapp.com/"
  server: "alpha.example.com"
  user: "deploy"
  env: "PATH=/home/{{user}}/bin:$PATH"
  directory: "/var/www/myapp/"
  deploy_repository: "/home/{{user}}/repos/myapp.git"
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
```

Dále můžeme do konfigurace zapsat své vlastní nové hodnoty, které nemají pro deployment žádný význam, ale které mohou být vloženy do jiných relevantních hodnot, a tím můžeme dosáhnout dalšího zjednodušení konfigurace.

```yaml
# file: config/deploy.yml
production:
  site_name: "myapp" # site_name is our extra value
  url: "https://www.myapp.com/"
  server: "alpha.example.com"
  user: "deploy"
  home: "/home/{{user}}" # home is also our extra value
  env: "PATH={{home}}/bin:$PATH"
  directory: "/var/www/{{site_name}}/"
  deploy_repository: "{{home}}/repos/{{site_name}}.git"
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

preview:
  site_name: "myapp_preview"
  url: "https://preview.myapp.com/"
  server: "preview.example.com"
  user: preview
  after_deploy:
  - "cat .htaccess.preview_addon >> .htaccess"
  - "./scripts/migrate && ./scripts/delete_temporary_files dbmole_cache"

preview_hungary:
  extends: preview
  site_name: "myapp_hungary_preview"
  url: "https://hungary-preview.myapp.com/"
```
