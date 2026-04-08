Deployment
==========

ATK14 includes a tool for deploying your project to production. Files are transferred via _git_, non-versioned files via _rsync_, and _ssh access_ to the production server is required.
You can specify commands to be run before or after deployment on your local machine (your development laptop) or on the production server — for example to automate building styles and scripts, create a backup, and so on.

Take a look at the sample configuration file.

```yaml
# file: config/deploy.yml
production:
  user: "deploy"
  server: "venus.universe-hosting.org"
  directory: "/home/deploy/apps/www.myapp.net/"
  deploy_repository: "deploy@venus.universe-hosting.org:repos/myapp.git"
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
```

This describes a deployment to a single production environment (stage) named _production_.
In practice it is common to have multiple production environments — e.g. _preview_, _acceptation_... That is supported too.

Deployment is triggered with `./scripts/deploy`

```shell
# deploy to the default (first) stage
$ ./scripts/deploy
  
# deployment do stage production
$ ./scripts/deploy production

# deployment do stage preview
$ ./scripts/deploy preview
```

Deployment is covered in detail in its own chapter.
