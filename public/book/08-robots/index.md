Roboti - automaticky spouštěné úlohy
====================================

Roboty umísťujeme do adresáře ./robots/. Ukázkový robot může vypadat například takto:

Include robots/cool_hand_robot.php

Tento robůtek nedělá nic, pouze zaloguje zprávu do logu ./log/robots.log.

Spustíme jej tímto příkazem.

	$ ./scripts/robot_runner cool_hand

V souboru ./robots/application_robot.php najdete společného předka pro všechny roboty.

Include robots/application_robot.php

Buďte na roboty hodní, dělají totiž dokola věci, které sami dělat nechcete.



