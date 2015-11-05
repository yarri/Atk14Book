Postupy a doporučení pro nalezení chyby
=======================================

Jednotlivá doporučení jsou seřazena podle významu.

### Provedení migrací

Je možné, že chyba, se kterou se potýkáte, souvisí s tím, že nemáte zrealizovány všechny migrace databázového schématu. Zkontrolujte:

	$ ./scripts/migrate

### Kontrola submodulů

Verzujete na gitu? A máte ATK14 do projektu vložen jako submodul? Prověřte, že máte aktuální verzi.

	$ git submodule update

### Nainstalujte si Tracy

[_Tracy_](https://tracy.nette.org/) je výborný ladící nástroj. Nainstalujte si ho. [Tady je návod.](http://forum.atk14.net/cs/topics/detail/?id=100)

### Kompilace všech šablon

Skript _compile\_all\_templates_ se pokusí zkompilovat všechny šablony. Lze tak snadno nalézt šablonu se syntaktickou chybou.

	$ ./scripts/compile_all_templates

### Nastavení php.ini

Ve vývojovém prostředí je důležité nechat si zobrazovat chyby. Otevřete si soubor php.ini

	$ sudo mcedit /etc/php5/apache2/php.ini

A v něm prověřte, že máte nastaveno 

	display_errors = On

Pamatujte, že pokud změníte nastavení v php.ini, je nutné restartovat Apache.

	$ sudo service apache2 restart

### Inspekce logu

Prozkoumejte chybový log Apache. Tímto příkazem zobrazíte posledních 100 řádků v error logu:

	$ tail -100 /var/log/apache2/error.log

Nebo si nechte nové řádky vypisovat v reálném čase:

	$ tail -f /var/log/apache2/error.log

### Promazání dočasných souborů

Pokud chybu stále nelze objevit, smažte veškeré dočasné soubory.

	$ ./scripts/delete_temporary_files

### Prověření instalace

ATK14 obsahuje skript _check\_installation_ pro ověření instalace. Spuťte ho a pokud se zde objeví něco nedobrého, sjednejte nápravu.

	$ ./scripts/check_installation

### Vyčištění a obnovení databáze

Následujícím postupem zcela vyčištíte a následně zase obnovíte databázi.

To může přijít vhod, když už všechny postupy selžou.

	$ ATK14_ENV=development ./scripts/destroy_database_objects
	$ ATK14_ENV=development ./scripts/migrate

Upozorňujeme však, že tak ztratíte z databáze všechna data, která nevznikla během migrací.

### Chybějící mod_rewrite

Pokud se v error logu Apache objeví něco takového

    Invalid command 'RewriteEngine', perhaps misspelled or defined by a module not included in the server configuration

zapněte mod_rewrite příkazem

    sudo a2enmod rewrite

a restartujte Apache

    service apache2 restart	
