Instalace z repozitáře
======================

Představ si, že chceš na svůj počítač do adresáře `~/projects/atk14/` nainstalovat aplikaci běžící na <http://www.atk14.net/>.
Tato aplikace má veřejný repozitář na GitHubu: <https://github.com/yarri/Atk14Net>

### Instalace zdrojového kódu

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

### Příprava databáze

```bash
./scripts/create_database
./scripts/migrate
```

Do databázové konzole se dostaneš příkazem:

```bash
./scripts/dbconsole
```

### Konfigurace Apache

Spusť tento příkaz a postupuj podle zobrazených instrukcí:

```bash
./scripts/virtual_host_configuration
```

Poté otevři v prohlížeči <http://atk14.localhost/>.

### ... když něco nefunguje

V případě potíží nebo po čerstvé instalaci spusť:

```bash
./scripts/check_installation
```

Výstup ti může napovědět, kde je problém.

### Příprava testovací databáze

Pro spouštění testů je potřeba vytvořit a inicializovat testovací databázi:

```bash
ATK14_ENV=TEST ./scripts/create_database
ATK14_ENV=TEST ./scripts/migrate
```

Do testovací databázové konzole se dostaneš takto:

```bash
ATK14_ENV=TEST ./scripts/dbconsole
```

### Spouštění testů

Všechny testy aplikace spustíš příkazem:

```bash
./scripts/run_all_tests
```
