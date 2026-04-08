ATK14 deployment basics
=======================

The ATK14 framework includes a tool for deploying the current version of an application to production. It uses incremental (continuous) deployment — the application is already running in production at some version, and this tool is used to push the latest version there.

ATK14 also includes a tool for the initial transfer of an application to production when there is nothing there yet. But more on that later.

A single ATK14 application can be installed into multiple production environments (production stages). One production installation might serve as a preview where clients see new features, another might be used as a staging environment for testing a new version before deploying to the actual production, etc.

ATK14 treats all of these production installations as production. This means the ATK14_ENV environment variable must be set to `production` on all of them — `ATK14_ENV=production`.

When "version" is mentioned here, it refers to a specific git commit hash that is deployed or about to be deployed to production. Which is why git is an important companion during deployment.

Production server requirements
-------------------------------

* Apache with mod_rewrite enabled
* PHP
* PostgreSQL database
* ssh access
* git

Production repository
---------------------

Each production installation has its own git repository (in bare mode) with a single master branch. During deployment, the desired commit hash is pushed to this branch as the new HEAD, and the production installation is then reset to it (`git reset --hard origin/master`).

The commit hash that ends up in production is the one you are currently on in your development laptop when you start the deployment process. It doesn't matter which branch you are on. You can deploy from a branch named master, primary, develop, or production — that depends on your team's workflow.

During deployment, no force flag is used when pushing to the production repository. This means the pushed commit hash must have a proper history connection to the HEAD of the master branch in the production repository. If the push fails, the deployment process is stopped and instructions are displayed on how to resolve the situation manually.

Configuration file
------------------

Configuration is stored in `config/deploy.yml` and in its simplest form might look like this:

```yaml
# file: config/deploy.yml
production:
  server: "alpha.example.com"
  user: "deploy"
  directory: "/var/www/myapp/"
  deploy_repository: "/home/deploy/repos/myapp.git"
```

This configuration file states that:

1. there is a configuration for a single production installation named *production*,
2. the application is installed on the server alpha.example.com,
3. the server is accessed using the deploy user,
4. the application is located in the /var/www/myapp/ directory, and
5. the application is checked out from the production repository /home/deploy/repos/myapp.git.

In practice the configuration file will contain more. The following example is closer to what you'll see in real projects.

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
```

- The `url` value is informational only. It is simply useful for clarity and diagnosing potential issues.
- `env` allows you to set environment variables.
- `before_deploy` and `after_deploy` contain shell commands to be executed before and after deployment respectively. If a command is prefixed with `@local`, it is executed locally (e.g. on your development laptop); otherwise it is executed on the production server.
- `rsync` contains a list of directories whose contents should be synchronised to the production installation via rsync. These are directories that are not versioned with the application source code.

Running a deployment
--------------------

Before running the deployment it is good to verify that the commit hash you are currently on is the one you want to deploy to production.

Then simply run:

```shell
[john@asterix ~/projects/myapp]$ ./scripts/deploy production
```

The deployment process will start executing commands one by one, reporting them in its output.

Among other things, the following is performed:

1. A git remote named *production* is created if it doesn't exist yet:

```shell
git remote add production deploy@alpha.example.com:/home/deploy/repos/myapp.git
```

2. The current HEAD from the current branch (let's say develop) is pushed to the *master* branch of the remote *production*:

```shell
git push production develop:master
```

3. On the server alpha.example.com, in the directory /var/www/myapp/, the following commands are run:

```shell
git checkout master && git fetch origin && git reset --hard origin/master
git submodule init && git submodule update
```

Any additional commands that are executed depend on the configuration in `config/deploy.yml`.

If anything fails, the script stops, displays an error description, and optionally provides instructions for resolving it.

Configuration for multiple production environments
---------------------------------------------------

The configuration file can contain descriptions for multiple production installations.

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
  server: "alpha.example.com"
  user: "deploy"
  env: "PATH=/home/deploy/bin:$PATH"
  directory: "/var/www/myapp_staging"
  deploy_repository: "/home/deploy/repos/myapp_staging.git"
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

Deploy to production:

```shell
[john@asterix ~/projects/myapp]$ ./scripts/deploy production
```

Deploy to staging:

```shell
[john@asterix ~/projects/myapp]$ ./scripts/deploy staging
```

Looking at this file with instructions for deploying to two production installations, you might notice that it contains a lot of duplicate lines. That's exactly why the next chapter covers how to simplify the contents of `config/deploy.yml`.
