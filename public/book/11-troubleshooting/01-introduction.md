Postupy a doporučení pro nalezení chyby
=======================================

Jednotlivá doporučení jsou seřazena podle významu.

### Provedení migrací

Je možné, že chyba, se kterou se potýkáte, souvisí s tím, že nemáte zrealizovány všechny migrace databázového schématu. Zkontrolujte:

	$ ./scripts/migrate

### Kontrola submodulů

Verzujete na gitu? A máte ATK14 do projektu vložen jako submodul? Prověřte, že máte aktuální verzi.

	$ git submodule update

### Použití Nette Laděnky

[_Nette Framework_](http://www.nette.org/) obsahuje skvělý nástroj [_Laděnka_](http://doc.nette.org/cs/debugging), který doporučujeme používat. Laděnka velmi pěkně vizualizuje chyby a výjimky.

Nainstalujte Nette:

	$ pear channel-discover pear.nette.org
	$ pear remote-list -c nette
	$ pear install nette/nette

Laděnku nastarujete v souboru lib/load.php

	<?php
	// file lib/load.php
	if(
		DEVELOPMENT &&
		!$HTTP_REQUEST->xhr()
	){
		require("Nette/loader.php");
		Nette\Diagnostics\Debugger::enable();
	}

V této ukázce bude Laděnka zapnuta pouze ve vývoji.

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

	$ rm -rf ./tmp/*

### Prověření instalace

ATK14 obsahuje skript _check\_installation_ pro ověření instalace. Spuťte ho a pokud se zde objeví něco nedobrého, sjednejte nápravu.

	$ ./scripts/check_installation

### Vyčištění a obnovení databáze

Následujícím postupem zcela vyčištíte a následně zase obnovíte databázi.

To může přijít vhod, když už všechny postupy selžou.

	$ ATK14_ENV=development ./scripts/purge_database_objects
	$ ATK14_ENV=development ./scripts/initialize_database
	$ ATK14_ENV=development ./scripts/migrate

Upozorňujeme však, že tak ztratíte z databáze všechna data, která nevznikla během migrací.
