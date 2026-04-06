Configuration tweaking
======================

Inheriting from a recipe
-------------------------

Unless specified otherwise, the rule is that all subsequent installation recipes inherit unspecified values from the first recipe.

This allows you to simplify the configuration from the end of the previous chapter by specifying only the values that differ from the production recipe in the staging recipe.

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

To check what the staging recipe actually looks like, run:

  ./scripts/deploy --dump staging

or in short:

  ./scripts/deploy -d staging

you can also print the configuration for all installations:

  ./scripts/deploy --dump

If you want a recipe to inherit from a recipe other than the first, specify this in the `extends` value.

Imagine you have an application that you are installing into a preview on a different server, in a different location, under a different user, and you want web access to be restricted via `.htaccess` by appending settings you have prepared in a file `.htaccess.preview_addon`. At the same time, colleagues from the Hungarian office have expressed interest in their own preview. The configuration file might look like this:

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

Verify what the configuration actually looks like:

    ./scripts/deploy --dump preview_hungary

Value referencing and custom values
-------------------------------------

You can insert one value into another by placing its name in double curly braces. For example, the following works perfectly:

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

You can also define your own custom values in the configuration — values that have no meaning for deployment itself but can be inserted into other relevant values, allowing further simplification.

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
