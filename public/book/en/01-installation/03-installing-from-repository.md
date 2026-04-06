Installing from a repository
============================

Imagine you want to install the application running at <http://www.atk14.net/> into `~/projects/atk14/` on your computer.
This application has a public repository on GitHub: <https://github.com/yarri/Atk14Net>

### Installing the source code

```bash
cd ~/projects/
mkdir atk14
cd atk14
git clone https://github.com/yarri/Atk14Net.git ./
git submodule init
git submodule update
composer update

chmod 777 tmp
chmod 777 log
```

### Setting up the database

```bash
./scripts/create_database
./scripts/migrate
```

You can access the database console with:

```bash
./scripts/dbconsole
```

### Apache configuration

Run this command and follow the displayed instructions:

```bash
./scripts/virtual_host_configuration
```

Then open <http://atk14.localhost/> in your browser.

### ... when something doesn't work

In case of trouble, or right after a fresh installation, run:

```bash
./scripts/check_installation
```

The output may point you to where the problem is.

### Setting up the test database

To run tests you need to create and initialize a test database:

```bash
ATK14_ENV=TEST ./scripts/create_database
ATK14_ENV=TEST ./scripts/migrate
```

You can access the test database console like this:

```bash
ATK14_ENV=TEST ./scripts/dbconsole
```

### Running tests

Run all application tests with:

```bash
./scripts/run_all_tests
```
