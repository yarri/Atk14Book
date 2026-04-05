Testování
=========

Testování je v ATK14 prvotřídní záležitost. Pro spouštění testů se používá nástroj [Tester](https://github.com/atk14/Tester), který je wrapperem nad PHPUnit. Díky němu píšeš testy jednou a fungují napříč různými verzemi PHP (5.6, 7.x, 8.x) i PHPUnit (4.8 až 11.0) bez jakýchkoliv úprav.

Instalace
---------

Tester nainstaluj do projektu jako vývojovou závislost:

	$ composer require --dev atk14/tester

Po instalaci je k dispozici příkaz `run_unit_tests`.

Struktura adresáře test/
------------------------

Testy umísťuj do adresáře `test/`. Testy jsou rozděleny do podadresářů podle charakteru testovaných objektů. Každý podadresář má vlastní `initialize.php` a `tc_base.php`.

	test/
	├── fixtures/               # testovací data ve formátu YAML
	│   ├── users.yml
	│   └── articles.yml
	├── models/                 # testy modelů
	│   ├── initialize.php
	│   ├── tc_base.php
	│   └── tc_user.php
	├── controllers/            # testy kontrolerů
	│   ├── initialize.php
	│   ├── tc_base.php
	│   └── tc_logins.php
	├── fields/                 # testy formulářových políček
	│   ├── initialize.php
	│   ├── tc_base.php
	│   └── tc_slug_field.php
	└── routers/                # testy routerů
	    ├── initialize.php
	    ├── tc_base.php
	    └── tc_pages_router.php

Soubor `initialize.php` je načten automaticky před každým test case souborem. Obvykle v něm inicializuješ ATK14:

	<?php
	// file: test/models/initialize.php
	define("TEST", true);
	define("MY_BLOWFISH_ROUNDS", 6); // výchozí hodnota 12 by testy zbytečně zpomalila
	require(__DIR__ . "/../../atk14/load.php");

Spouštění testů
---------------

Testy spouštíš přímo v příslušném podadresáři:

	# všechny testy modelů
	$ cd test/models/ && run_unit_tests

	# konkrétní test case
	$ run_unit_tests tc_user

	# více test case najednou
	$ run_unit_tests tc_user tc_article

Pro spuštění všech testů najednou slouží skript `./scripts/run_all_tests`, který prochází všechny podadresáře s `initialize.php` a spustí v každém `run_unit_tests`. Hodí se zejména v CI.

	$ ./scripts/run_all_tests

Bázové třídy pro různé typy testů
----------------------------------

Třída `TcBase` v každém podadresáři rozšiřuje jinou třídu `TcAtk14*` podle toho, co se v daném podadresáři testuje:

| Podadresář     | TcBase extends        | Co přidává navíc                          |
|----------------|-----------------------|-------------------------------------------|
| `models/`      | `TcAtk14Model`        | přístup k `$this->dbmole`, fixtures       |
| `controllers/` | `TcAtk14Controller`   | HTTP klient `$this->client`               |
| `fields/`      | `TcAtk14Field`        | metody `assertValid()`, `assertInvalid()` |
| `routers/`     | `TcAtk14Router`       | metody `assertBuildable()`, `assertRecognizable()` |

Fixtures — testovací data
--------------------------

Fixtures jsou YAML soubory v adresáři `test/fixtures/`. Definuješ v nich pojmenované záznamy, které se před každým testem vloží do databáze.

	# file: test/fixtures/users.yml

	rambo:
	  login: "rambo"
	  firstname: "John"
	  lastname: "Rambo"
	  password: "secret"
	  email: "john@rambo.com"

	inactive_user:
	  login: "inactive.user"
	  firstname: "Ivor"
	  lastname: "Inactivator"
	  email: "ivor@inactivator.com"
	  active: false

Název souboru (`users.yml`) určuje model (`User`), jehož metodou `CreateNewRecord()` se záznamy vytvoří. Přihlašovací hesla se při vkládání automaticky zahashují.

Fixture načteš v test case souboru pomocí anotace `@fixture`. Záznamy pak máš k dispozici jako pole `$this->users`:

	<?php
	/**
	 * @fixture users
	 */
	class TcUser extends TcBase {

		function test(){
			$rambo = $this->users["rambo"];
			$this->assertEquals("John Rambo", $rambo->getName());
			$this->assertTrue($rambo->isActive());
		}
	}

Potřebuješ-li více fixtures najednou, přidej více anotací:

	<?php
	/**
	 * @fixture users
	 * @fixture articles
	 */
	class TcArticle extends TcBase {
		// ...
	}

Databázová izolace
------------------

Aby testy vzájemně neovlivňovaly data v databázi, každý test probíhá uvnitř transakce, která se na konci vrátí zpět. Zajišťuje to `TcBase` v každém podadresáři:

	<?php
	// file: test/models/tc_base.php
	class TcBase extends TcAtk14Model {

		function _setUp(){
			$this->dbmole->begin();      // začátek transakce
			$this->setUpFixtures();      // vložení fixture dat
		}

		function _tearDown(){
			$this->dbmole->rollback();   // vrácení všech změn
		}
	}

Díky tomu každá testovací metoda dostane čistou kopii dat definovaných ve fixtures a případné změny (INSERT, UPDATE, DELETE) se po testu automaticky vrátí.

Testování modelů
----------------

	<?php
	/**
	 * @fixture users
	 */
	class TcUser extends TcBase {

		function testHashingPassword(){
			$rambo = $this->users["rambo"];

			// heslo je uloženo jako hash, ne jako plain text
			$this->assertTrue($rambo->getPassword() != "secret");

			// přihlášení se správným heslem
			$user = User::Login("rambo", "secret");
			$this->assertNotNull($user);

			// přihlášení se špatným heslem
			$user = User::Login("rambo", "wrong");
			$this->assertNull($user);
		}

		function test_destroy(){
			$rambo = $this->users["rambo"];
			$rambo_id = $rambo->getId();

			$rambo->destroy(); // soft delete

			$rambo = User::GetInstanceById($rambo_id);
			$this->assertTrue($rambo->isDeleted());
			$this->assertFalse($rambo->isActive());
		}
	}

Testování kontrolerů
--------------------

Bázová třída pro testy kontrolerů poskytuje HTTP klienta simulujícího skutečné požadavky. CSRF ochranu v testech obcházíme nastavením testovacího tokenu:

	<?php
	// file: test/controllers/tc_base.php
	class TcBase extends TcAtk14Controller {

		function _setUp(){
			$this->dbmole->begin();
			$this->setUpFixtures();
			$GLOBALS["HTTP_REQUEST"]->setPostVar("_csrf_token_", "testing_csrf_token");
		}

		function _tearDown(){
			$this->dbmole->rollback();
		}
	}

Samotný test pak simuluje GET a POST požadavky a ověřuje odpovědi:

	<?php
	/**
	 * @fixture users
	 */
	class TcLogins extends TcBase {

		function test(){
			$client = $this->client;

			// uživatel ještě není přihlášen
			$client->get("main/index");
			$this->assertEquals(200, $client->getStatusCode());
			$this->assertStringNotContains("rambo", $client->getContent());

			// pokus o přihlášení se špatným heslem
			$ctrl = $client->post("logins/create_new", array(
				"login"    => "rambo",
				"password" => "wrong",
			));
			$this->assertEquals(200, $client->getStatusCode());
			$this->assertTrue($ctrl->form->has_errors());

			// přihlášení se správným heslem
			$ctrl = $client->post("logins/create_new", array(
				"login"    => "rambo",
				"password" => "secret",
			));
			$this->assertEquals(303, $client->getStatusCode()); // přesměrování
			$this->assertFalse($ctrl->form->has_errors());

			// uživatel je nyní přihlášen
			$client->get("main/index");
			$this->assertStringContains("rambo", $client->getContent());

			// odhlášení
			$client->post("logins/destroy");
			$this->assertEquals(303, $client->getStatusCode());
		}
	}

Nebezpečné testy
----------------

Některé testy nechceš spouštět automaticky — například ty, které odesílají skutečné e-maily nebo mění produkční data. Pojmenuj je s vykřičníkem na začátku:

	!tc_send_real_email.php

Takové soubory `run_unit_tests` přeskočí. Spustíš je pouze explicitně:

	$ run_unit_tests \!tc_send_real_email.php
