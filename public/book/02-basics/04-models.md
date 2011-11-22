Modely
======

ATK14 obsahuje jednoduchý ORM framework TableRecord.

Uvažujme tabulku, do které chceme ukládat uživatele.

Include db/migrations/0001_users.sql

Třída modelu pak může vypadat následovně.

Include app/models/user.php

Teď je nutné popsat několik kouzel, které jsou do tohoto celého namíchány, jakkoli kouzla rádi nemáme.

Vztah modelu k tabulce je dán názvem třídy. Třída *User* se spojí s tabulkou *users*, třída *Person* se spojí s tabulkou *people*,
třída *RedWine* se spojí s tabulkou *red_wines* a pod.

Instanci vytvoříme následovně.

	<?php
	// nacteni existujicih zaznamu
	$user = User::GetInstanceById(1); // bude null, pokud zaznam s id 1 neexistuje
	$users = User::GetInstanceById(array(1,33)); // vrati pole dvou objektu tridy User

	// vytvoreni noveho zaznamu
	$user = User::CreateNewRecord(array(
		"login" => "john_doe",
		"password" => "Secreeet129",
		"name" => "John Doe",
		"email" => "john.doe@gmail.com"
	));

Hodnoty jednolivých polí načteme nebo změníme takto.

	<?php
	// nacteni hodnot
	$user->getValue("login");
	$user->g("login"); // toto zhusta pouzivany alias pro getValue
	$user->getLogin(); // pozor na kouzlo! metodu getLogin() ve tride nemame, presto funguje

	// nastaveni hodnoty
	$user->setValue("name","John MC Doe");
	$user->s("name","John MC Doe"); // dalsi zhusta pouzivany alias pro setValue() ale i pro setValues()

	// hromadne nastaveni hodnot
	$user->setValues(array(
		"name" => "John MC Doe",
		"email" => "john.mc.doe@gmail.com"
	));
	$user->s(array(
		"name" => "John MC Doe",
		"email" => "john.mc.doe@gmail.com"
	));

Je důležité si uvědomit, že při volání metod *CreateNewRecord()*, *setValue()*, *setValues()* dochází k ukládaní resp. ke změnám dat v tabulce.
Myslete na to ve chvíli, kdy měníte několik polí. Změnte je najednou voláním *setValues()*. Je to rychlejší než několik volání *setValue()*.

Záznam smažeme takto

	<?php
	$user->destroy();

	// Metoda destroy() nic nevrací. Toho muzeme vyuzit.
	$user = $user->destroy(); // a v $user uz neni nic...

Pro úplnost přikládáme zdrojový kód bázove třídy ApplicationModel.

Include app/models/application_model.php

Zde není žádná velká věda. Jsou tu konverzní funkce, které v TableRecord chybí a z nějakého důvodu se nám hodí.
