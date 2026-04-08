Initial installation to production
====================================

Now that you've precisely described the production installation in `config/deploy.yml`, the time has come to install the application into that environment. This is a one-time action with a sequence of steps that the ATK14 framework will gladly help you with.

Production server preparation
------------------------------

If you're setting up a server that doesn't yet run any ATK14 application, make a few small adjustments.

Set the ATK14_ENV environment variable to `production` in `.bash_profile`.

```bash
# .bash_profile
...
ATK14_ENV=production
export ATK14_ENV
```

If the server has multiple PHP versions available at the same time, symlink your preferred PHP version into $HOME/bin:

```shell
mkdir $HOME/bin
ln -s /usr/bin/php83 ~/bin/php
```

and add the $HOME/bin path to the PATH environment variable in $HOME/.bash_profile.

```bash
# .bash_profile
...
PATH=$HOME/bin:$PATH
export PATH
```

Also add the environment variable settings to crontab.

```shell
$ crontab -e

MAILTO=email@example.com
ATK14_ENV=production
PATH=/home/user/bin:/usr/local/bin:/usr/bin:/usr/local/sbin:/usr/sbin
CRON_TZ=Europe/Prague
```

Installing the application on the server
-----------------------------------------

The ATK14 framework includes the *initialize_deployment_stage* script, which reads the configuration in `config/deploy.yml` and generates the shell commands needed to install the application into the given production environment.

Run it like this:

```shell
$ ./scripts/initialize_deployment_stage production
```

Feel free to try it. Nothing will happen — it just prints the shell commands that would install the application into the given production environment.

Review them, and if they look good, run:

```shell
$ ./scripts/initialize_deployment_stage production | sh
```

If everything goes well, you're done. If something fails, try to identify where the problem occurred, fix it, and continue running the commands from that point on.

Once you're done, log into the production installation.

```shell
$ ./scripts/shell production
```

... and finish configuring the application. This mainly means setting up the database connection in `local_config/database.yml` and optionally other specific settings in `local_config/settings.php`.

Database configuration
----------------------

Configure the database connection on production in `local_config/database.yml`.

```yaml
# file: local_config/database.yml
production:
  host: 127.0.0.1
  database: "database_name"
  username: "database_user"
  password: "password"
```

Verify the connection with:

```shell
$ ./scripts/dbconsole
```

You should connect to the database successfully.

Apache virtual host configuration
-----------------------------------

To create the Apache virtual host configuration, run the `virtual_host_configuration` script on production and use the output as a guide:

```shell
$ ./scripts/virtual_host_configuration
```
