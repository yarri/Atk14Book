Roboti - automaticky spouštěné úlohy
====================================

Roboty umísťujeme do adresáře ./robots/. Ukázkový robot může vypadat například takto:

```php
<?php
// file: robots/cool_hand_robot.php
/**
 * An example robot which logs a message and then does nothing
 */
class CoolHandRobot extends ApplicationRobot{

  function run(){
    // In here there is access to:
    //
    //  $this->logger
    //  $this->dbmole
    //  $this->mailer
    //  ...

    $this->logger->info("The Cool Hand robot is eating eggs");
  }
}
```

Tento robůtek nedělá nic, pouze zaloguje zprávu do logu ./log/robots.log.

Spustíme jej tímto příkazem.

	$ ./scripts/robot_runner cool_hand

Příkaz robot_runner spuštěný bez parametru vypíše seznam dostupných robotů a nabídne prompt pro zadání názvu robota, který má být spuštěn.

	$ ./scripts/robot_runner

V souboru ./robots/application_robot.php najdeš společného předka pro všechny roboty.

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
}
```

Buď na roboty hodný, dělají totiž dokola věci, které sám dělat nechceš.



