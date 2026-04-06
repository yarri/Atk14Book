Robots — automatically scheduled tasks
=======================================

Robots are placed in the `./robots/` directory. A sample robot might look like this:

```php
<?php
// file: robots/cool_hand_robot.php
/**
 * An example robot which logs a message and then does nothing
 */
class CoolHandRobot extends ApplicationRobot{

  function run(){
    $this->logger->info("The Cool Hand robot is eating eggs");
  }
}
```

This little robot does nothing — it just logs a message to `./log/robots.log`.

Run it with this command:

	$ ./scripts/robot_runner cool_hand

Running `robot_runner` without a parameter prints a list of available robots and prompts you to enter the name of the robot to run.

	$ ./scripts/robot_runner

Available resources
-------------------

Inside the `run()` method the robot has access to these member variables:

| Variable        | Description                                    |
|-----------------|------------------------------------------------|
| `$this->logger` | writes to `./log/robots.log`                   |
| `$this->dbmole` | database connection                            |
| `$this->mailer` | sending emails                                 |

A more realistic example
-------------------------

A typical robot iterates over database records, performs some action on each one, and commits changes along the way. Here is an example of a robot that watches product availability and sends notifications to users:

```php
<?php
// file: robots/watchdog_notifier_robot.php
class WatchdogNotifierRobot extends ApplicationRobot {

  function run(){
    foreach(WatchedProduct::GetWatchedProductsToNotify() as $watched_product){
      $user = $watched_product->getUser();
      $product = $watched_product->getProduct();

      $this->logger->info("sending notification to User#{$user->getId()} about {$product}");

      $this->mailer->send_watchdog_notification($watched_product);
      $watched_product->markAsNotified();

      // commit po každém záznamu — neztrácíme práci při pádu uprostřed
      $this->_commit();
    }
  }
}
```

The `_commit()` method defined in `ApplicationRobot` commits the current transaction and immediately opens a new one. It is useful when processing a large number of records where you don't want everything in one giant transaction.

To discard changes instead (e.g. on error), use `_rollback()`.

The ApplicationRobot base class
---------------------------------

The `./robots/application_robot.php` file contains the common ancestor for all robots.

```php
<?php
// file: robots/application_robot.php
class ApplicationRobot extends Atk14Robot{
  function beforeRun(){
    $this->dbmole->begin();
  }

  function afterRun(){
    $this->dbmole->commit();
  }

  function _commit(){
    $this->dbmole->commit();
    $this->dbmole->begin();
  }

  function _rollback(){
    $this->dbmole->rollback();
    $this->dbmole->begin();
  }
}
```

Aggregate scripts and crontab
------------------------------

In practice you don't run robots one by one — you group them into aggregate scripts according to how often they should run. Place these scripts in the `./local_scripts/` directory.

For scripts run every 5 minutes:

```bash
#!/usr/bin/env bash
# file: local_scripts/robots_regular

cd $(dirname $0)
cd ..

./scripts/robot_runner watchdog_notifier
./scripts/robot_runner payment_status_checker
./scripts/robot_runner automatic_order_status_updater
./scripts/robot_runner invoice_file_notifier
```

For scripts run once a day:

```bash
#!/usr/bin/env bash
# file: local_scripts/robots_daily

cd $(dirname $0)
cd ..

./scripts/robot_runner vacuum_analyze
./scripts/robot_runner fulltext_indexer
./scripts/robot_runner import_delivery_service_branches
```

For scripts run once a week:

```bash
#!/usr/bin/env bash
# file: local_scripts/robots_weekly

cd $(dirname $0)
cd ..

./scripts/robot_runner reindex_database
```

You then add these scripts to the crontab on the production server:

	$ crontab -e

	MAILTO=admin@example.com
	ATK14_ENV=production
	PATH=/home/deploy/bin:/usr/local/bin:/usr/bin:/usr/local/sbin:/usr/sbin
	CRON_TZ=Europe/Prague

	*/5 * * * *  /var/www/myapp/local_scripts/robots_regular >> /var/www/myapp/log/cron.log 2>&1
	0   3 * * *  /var/www/myapp/local_scripts/robots_daily  >> /var/www/myapp/log/cron.log 2>&1
	0   4 * * 0  /var/www/myapp/local_scripts/robots_weekly >> /var/www/myapp/log/cron.log 2>&1

Robot output is written to `./log/robots.log`. Error output from crontab goes to the email defined in `MAILTO`.

Be kind to your robots — they do over and over the things you don't want to do yourself.
