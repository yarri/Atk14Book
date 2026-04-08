Installing an application on Opalstack
=======================================

The popular [webhoster Opalstack](https://www.opalstack.com/) offers a decent environment on its hosting servers with PHP, Apache, PostgreSQL, and ssh access.
Installing and running an ATK14 application there is no trouble at all.

Imagine that Jan "Kinky" Kučera accesses Opalstack as the user `snapper` and has been tasked with launching the application www.filcker.net — a revolutionary service for sharing unpleasant experiences.

Let's see what Kinky needs to do to install his ATK14 application on Opalstack.

Initial server environment setup
---------------------------------

This step only needs to be done once. When installing a second or further ATK14 application, it can be skipped.

Kinky creates a `$HOME/bin` directory on his allocated Opalstack server and symlinks the preferred PHP binary there.

```shell
[kinky@notebook ~]$ ssh snapper@snapper.opalstacked.com
[snapper@opal6 ~]$ mkdir bin
[snapper@opal6 ~]$ ln -s /usr/bin/php83 ~/bin/php
```

He adds the new path to PATH in `~/.bash_profile` and sets the ATK14_ENV environment variable.

```bash
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
```

He also adds the PATH and ATK14_ENV settings at the top of the crontab.

```shell
[snapper@opal6 ~]$ crontab -e

MAILTO=jan.kucera@example.com
ATK14_ENV=production
PATH=/home/snapper/bin:/usr/local/bin:/usr/bin:/usr/local/sbin:/usr/sbin
CRON_TZ=Europe/Prague
```

Creating the application, site, and database
---------------------------------------------

In the Opalstack control panel, Kinky creates an application named *filcker* of type *PHP-FPM Apache* with the preferred PHP version, 8.3.

The application will be placed in the directory /home/snapper/apps/filcker. Kinky checks that the directory exists and empties it if it contains any sample index file.

He then adds the domains filcker.net and www.filcker.net in the control panel.

In the *Add site* dialog he creates a site named *filcker_production*, attaches the domains filcker.net and www.filcker.net, and connects the filcker application.

He then creates a PostgreSQL user `filcker_production` and a database `filcker_production`. Access to this database will be granted to that new user.

The password for the `filcker_production` database user will be published by Opalstack with a short delay in the *Notice Log* tool. Kinky notes it down and deletes it from the Notice Log.

Deployment recipe for production
----------------------------------

Kinky writes the deployment recipe into `config/deploy.yml`.

```shell
[kinky@notebook ~/projects/filcker]$ vim config/deploy.yml
```

```yaml
# file: config/deploy.yml
production:
  url: "https://www.filcker.net/"
  user: "snapper"
  env: "PATH=/home/{{user}}/bin:$PATH"
  server: "{{user}}.opalstacked.com"
  directory: "/home/{{user}}/apps/filcker/"
  deploy_repository: "{{user}}@{{server}}:repos/filcker.git"
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
```

Transferring the application to production
-------------------------------------------

The ATK14 framework includes the `scripts/initialize_deployment_stage` tool, which uses the recipe in `config/deploy.yml` to generate the shell commands for transferring the application to the production server.

Kinky runs:

```shell
[kinky@notebook ~/projects/filcker]$ ./scripts/initialize_deployment_stage production
```

He carefully reviews the printed commands. They look good, so he boldly enters:

```shell
[kinky@notebook ~/projects/filcker]$ ./scripts/initialize_deployment_stage production | sh
```

Phew. Everything went well this time, so Kinky relaxes again and his blood pressure slowly returns to normal.

If something had gone wrong, Kinky would have tried to figure out which command failed, made a corrective fix, and continued from that problematic command onwards.

Configuring and populating the production database
---------------------------------------------------

Kinky logs into the production installation, where he creates a local file with the database connection configuration. He inserts the password he noted down earlier.

Note that there is no need to configure the test and development databases at all — those won't be connected to in production.

```shell
[kinky@notebook ~/projects/filcker]$ ./scripts/shell production
[snapper@opal6 ~/apps/filcker$ vim local_config/database.yml
```

```yaml
# file: local_config/database.yml
production:
  host: 127.0.0.1
  database: "filcker_production"
  username: "filcker_production"
  password: "DatabasePassword123"
```

Kinky can now test that the production database connection works:

```shell
[snapper@opal6 ~/apps/filcker]$ ./scripts/dbconsole
```

If everything works, he runs the migrations:

```shell
[snapper@opal6 ~/apps/filcker]$ ./scripts/migrate
```

And that's it.

Deployment
----------

Subsequent deployments are then as easy as pie. Kinky and his colleagues develop calmly on their laptops, and whenever they decide to publish their work to production, they simply run:

```shell
[kinky@notebook ~/projects/filcker]$ ./scripts/deployment production
```

Kinky really nailed this one! :)
