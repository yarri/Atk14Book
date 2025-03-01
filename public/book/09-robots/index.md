Roboti - automaticky spouštěné úlohy
====================================

Roboty umísťujeme do adresáře ./robots/. Ukázkový robot může vypadat například takto:

[include file=robots/cool_hand_robot.php]

Tento robůtek nedělá nic, pouze zaloguje zprávu do logu ./log/robots.log.

Spustíme jej tímto příkazem.

	$ ./scripts/robot_runner cool_hand

Příkaz robot_runner spuštěný bez parametru vypíše seznam dostupných robotů a nabídne prompt pro zadání názvu robota, který má být spuštěn.

	$ ./scripts/robot_runner

V souboru ./robots/application_robot.php najdete společného předka pro všechny roboty.

[include file=robots/application_robot.php]

Buďte na roboty hodní, dělají totiž dokola věci, které sami dělat nechcete.



