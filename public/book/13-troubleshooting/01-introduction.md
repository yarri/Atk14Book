Postupy a doporučení pro nalezení chyby
=======================================

Jednotlivá doporučení jsou seřazena podle významu.

### Provedení migrací

Je možné, že chyba, se kterou se potýkáš, souvisí s tím, že nemáš zrealizovány všechny migrace databázového schématu. Zkontroluj:

```shell
$ ./scripts/migrate
```

### Kontrola submodulů

Verzuješ na gitu? A máš ATK14 do projektu vložen jako submodul? Prověř, že máš aktuální verzi.

```shell
$ git submodule update
```

### Nainstalujte si Tracy

[_Tracy_](https://tracy.nette.org/) je výborný ladící nástroj. Nainstaluj si ho. [Tady je návod.](http://forum.atk14.net/cs/topics/detail/?id=100)

### Kompilace všech šablon

Skript _compile\_all\_templates_ se pokusí zkompilovat všechny šablony. Lze tak snadno nalézt šablonu se syntaktickou chybou.

```shell
$ ./scripts/compile_all_templates
```

### Nastavení php.ini

Ve vývojovém prostředí je důležité nechat si zobrazovat chyby. Otevři si soubor php.ini

```shell
$ sudo mcedit /etc/php5/apache2/php.ini
```

A v něm prověř, že máš nastaveno

```ini
display_errors = On
```

Pamatuj, že pokud změníš nastavení v php.ini, je nutné restartovat Apache.

```shell
$ sudo service apache2 restart
```

### Inspekce logu

Prozkoumej chybový log Apache. Tímto příkazem zobrazíš posledních 100 řádků v error logu:

```shell
$ tail -100 /var/log/apache2/error.log
```

Nebo si nech nové řádky vypisovat v reálném čase:

```shell
$ tail -f /var/log/apache2/error.log
```

### Promazání dočasných souborů

Pokud chybu stále nelze objevit, smažte veškeré dočasné soubory.

```shell
$ ./scripts/delete_temporary_files
```

### Prověření instalace

ATK14 obsahuje skript _check\_installation_ pro ověření instalace. Spusť ho a pokud se zde objeví něco nedobrého, sjednej nápravu.

```shell
$ ./scripts/check_installation
```

### Vyčištění a obnovení databáze

Následujícím postupem zcela vyčištíte a následně zase obnovíte databázi.

To může přijít vhod, když už všechny postupy selžou.

```shell
$ ATK14_ENV=development ./scripts/destroy_database_objects
$ ATK14_ENV=development ./scripts/migrate
```

Upozorňuji však, že tak ztratíš z databáze všechna data, která nevznikla během migrací.

### Chybějící mod_rewrite

Pokud se v error logu Apache objeví něco takového

```text
Invalid command 'RewriteEngine', perhaps misspelled or defined by a module not included in the server configuration
```

zapněte mod_rewrite příkazem

```shell
sudo a2enmod rewrite
```

a restartujte Apache

```shell
service apache2 restart
```
