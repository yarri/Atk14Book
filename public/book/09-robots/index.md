Roboti - automaticky spouštěné úlohy
====================================

Roboty umísťujeme do adresáře `./robots/`. Ukázkový robot může vypadat například takto:

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

Tento robůtek nedělá nic, pouze zaloguje zprávu do logu `./log/robots.log`.

Spustíme jej tímto příkazem:

	$ ./scripts/robot_runner cool_hand

Příkaz `robot_runner` spuštěný bez parametru vypíše seznam dostupných robotů a nabídne prompt pro zadání názvu robota, který má být spuštěn.

	$ ./scripts/robot_runner

Dostupné zdroje
---------------

V metodě `run()` má robot přístup k těmto členským proměnným:

| Proměnná        | Popis                                          |
|-----------------|------------------------------------------------|
| `$this->logger` | zapisuje do `./log/robots.log`                 |
| `$this->dbmole` | připojení k databázi                           |
| `$this->mailer` | odesílání e-mailů                              |

Realističtější příklad
-----------------------

Typický robot prochází záznamy v databázi, provede nad každým nějakou akci a průběžně commituje změny. Tady je příklad robota, který hlídá dostupnost produktů a posílá notifikace uživatelům:

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

Metoda `_commit()` definovaná v `ApplicationRobot` provede commit a hned otevře novou transakci. Hodí se při zpracování většího počtu záznamů, kdy nechceš mít vše v jedné obří transakci.

Pokud chceš změny naopak zahodit (třeba při chybě), použij `_rollback()`.

Základní třída ApplicationRobot
--------------------------------

V souboru `./robots/application_robot.php` najdeš společného předka pro všechny roboty.

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

Agregační skripty a crontab
----------------------------

V praxi roboty nespouštíš po jednom, ale seskupuješ je do agregačních skriptů podle toho, jak často mají běžet. Tyto skripty umísťuj do adresáře `./local_scripts/`.

Pro roboty spouštěné každých 5 minut:

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

Pro roboty spouštěné jednou denně:

```bash
#!/usr/bin/env bash
# file: local_scripts/robots_daily

cd $(dirname $0)
cd ..

./scripts/robot_runner vacuum_analyze
./scripts/robot_runner fulltext_indexer
./scripts/robot_runner import_delivery_service_branches
```

Pro roboty spouštěné jednou týdně:

```bash
#!/usr/bin/env bash
# file: local_scripts/robots_weekly

cd $(dirname $0)
cd ..

./scripts/robot_runner reindex_database
```

Tyto skripty pak zadáš do crontabu na produkčním serveru:

	$ crontab -e

	MAILTO=admin@example.com
	ATK14_ENV=production
	PATH=/home/deploy/bin:/usr/local/bin:/usr/bin:/usr/local/sbin:/usr/sbin
	CRON_TZ=Europe/Prague

	*/5 * * * *  /var/www/myapp/local_scripts/robots_regular >> /var/www/myapp/log/cron.log 2>&1
	0   3 * * *  /var/www/myapp/local_scripts/robots_daily  >> /var/www/myapp/log/cron.log 2>&1
	0   4 * * 0  /var/www/myapp/local_scripts/robots_weekly >> /var/www/myapp/log/cron.log 2>&1

Výstup robotů se zapisuje do `./log/robots.log`. Chybový výstup z crontabu jde na e-mail definovaný v `MAILTO`.

Buď na roboty hodný, dělají totiž dokola věci, které sám dělat nechceš.
