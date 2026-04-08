Testing
=======

Testing is a first-class concern in ATK14. Tests are run with the [Tester](https://github.com/atk14/Tester) tool, which is a wrapper around PHPUnit. It lets you write tests once and have them run across different versions of PHP (5.6, 7.x, 8.x) and PHPUnit (4.8 through 11.0) without any modifications.

Installation
------------

Install Tester into your project as a development dependency:

```shell
$ composer require --dev atk14/tester
```

After installation the `run_unit_tests` command is available.

Structure of the test/ directory
---------------------------------

Place tests in the `test/` directory. Tests are split into subdirectories based on the type of objects being tested. Each subdirectory has its own `initialize.php` and `tc_base.php`.

```text
test/
├── fixtures/               # test data in YAML format
│   ├── users.yml
│   └── articles.yml
├── models/                 # model tests
│   ├── initialize.php
│   ├── tc_base.php
│   └── tc_user.php
├── controllers/            # controller tests
│   ├── initialize.php
│   ├── tc_base.php
│   └── tc_logins.php
├── fields/                 # form field tests
│   ├── initialize.php
│   ├── tc_base.php
│   └── tc_slug_field.php
└── routers/                # router tests
    ├── initialize.php
    ├── tc_base.php
    └── tc_pages_router.php
```

The `initialize.php` file is loaded automatically before each test case file. It typically initialises ATK14:

```php
<?php
// file: test/models/initialize.php
define("TEST", true);
define("MY_BLOWFISH_ROUNDS", 6); // the default value of 12 would unnecessarily slow down tests
require(__DIR__ . "/../../atk14/load.php");
```

Running tests
-------------

Run tests directly inside the relevant subdirectory:

```shell
# all model tests
$ cd test/models/ && run_unit_tests

# a specific test case
$ run_unit_tests tc_user

# several test cases at once
$ run_unit_tests tc_user tc_article
```

To run all tests at once use the `./scripts/run_all_tests` script, which iterates over all subdirectories containing `initialize.php` and runs `run_unit_tests` in each. Particularly useful in CI.

```shell
$ ./scripts/run_all_tests
```

Base classes for different types of tests
------------------------------------------

The `TcBase` class in each subdirectory extends a different `TcAtk14*` class depending on what is being tested there:

| Subdirectory   | TcBase extends        | What it adds                              |
|----------------|-----------------------|-------------------------------------------|
| `models/`      | `TcAtk14Model`        | access to `$this->dbmole`, fixtures       |
| `controllers/` | `TcAtk14Controller`   | HTTP client `$this->client`               |
| `fields/`      | `TcAtk14Field`        | methods `assertValid()`, `assertInvalid()` |
| `routers/`     | `TcAtk14Router`       | methods `assertBuildable()`, `assertRecognizable()` |

Fixtures — test data
---------------------

Fixtures are YAML files in the `test/fixtures/` directory. You define named records in them, which are inserted into the database before each test.

```yaml
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
```

The filename (`users.yml`) determines the model (`User`) whose `CreateNewRecord()` method is used to create the records. Passwords are automatically hashed during insertion.

You load a fixture in a test case file using the `@fixture` annotation. The records are then available as the `$this->users` array:

```php
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
```

If you need several fixtures at once, add multiple annotations:

```php
<?php
/**
 * @fixture users
 * @fixture articles
 */
class TcArticle extends TcBase {
	// ...
}
```

Database isolation
------------------

To prevent tests from affecting each other's database data, each test runs inside a transaction that is rolled back at the end. This is handled by `TcBase` in each subdirectory:

```php
<?php
// file: test/models/tc_base.php
class TcBase extends TcAtk14Model {

	function _setUp(){
		$this->dbmole->begin();      // begin transaction
		$this->setUpFixtures();      // insert fixture data
	}

	function _tearDown(){
		$this->dbmole->rollback();   // roll back all changes
	}
}
```

This means every test method receives a clean copy of the data defined in fixtures, and any changes (INSERT, UPDATE, DELETE) are automatically rolled back after the test.

Testing models
--------------

```php
<?php
/**
 * @fixture users
 */
class TcUser extends TcBase {

	function testHashingPassword(){
		$rambo = $this->users["rambo"];

		// password is stored as a hash, not plain text
		$this->assertTrue($rambo->getPassword() != "secret");

		// login with the correct password
		$user = User::Login("rambo", "secret");
		$this->assertNotNull($user);

		// login with the wrong password
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
```

Testing controllers
-------------------

The base class for controller tests provides an HTTP client that simulates real requests. CSRF protection is bypassed in tests by setting a test token:

```php
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
```

The test itself then simulates GET and POST requests and verifies the responses:

```php
<?php
/**
 * @fixture users
 */
class TcLogins extends TcBase {

	function test(){
		$client = $this->client;

		// user is not logged in yet
		$client->get("main/index");
		$this->assertEquals(200, $client->getStatusCode());
		$this->assertStringNotContains("rambo", $client->getContent());

		// attempt to log in with the wrong password
		$ctrl = $client->post("logins/create_new", array(
			"login"    => "rambo",
			"password" => "wrong",
		));
		$this->assertEquals(200, $client->getStatusCode());
		$this->assertTrue($ctrl->form->has_errors());

		// login with the correct password
		$ctrl = $client->post("logins/create_new", array(
			"login"    => "rambo",
			"password" => "secret",
		));
		$this->assertEquals(303, $client->getStatusCode()); // redirect
		$this->assertFalse($ctrl->form->has_errors());

		// user is now logged in
		$client->get("main/index");
		$this->assertStringContains("rambo", $client->getContent());

		// logout
		$client->post("logins/destroy");
		$this->assertEquals(303, $client->getStatusCode());
	}
}
```

Dangerous tests
---------------

Some tests you don't want to run automatically — for example those that send real emails or modify production data. Name them with an exclamation mark at the beginning:

```text
!tc_send_real_email.php
```

Such files are skipped by `run_unit_tests`. You can run them explicitly only:

```shell
$ run_unit_tests \!tc_send_real_email.php
```
