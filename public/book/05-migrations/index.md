Migrace
=======

Migrace pomáhají udržet shodné databázové schéma ve všech nainstalovaných instancích aplikace.

Jedná se sérií seřazených patchů do databáze, pomocí kterých je možné přidávat tabulky, měnit stávající tabulky, vytvářet indexy, plnit číselníky...

Migrační soubory umísťujeme do adresáře _db/migrations/_.

Každý migrační soubor musí začínat numerickým prefixem, který zajistí provádění jednotlivých migrací ve správném pořadí. Dobrá volba může být posloupnost 0001, 0002, 0003, nebo 201101121550, 201101121700 (tj. aktualní datum a čas ve formátu YmdHi) a podobně.

### Příklady názvů migračních souborů

	$ ls db/migrations/
	0000_sessions.sql
	0001_users.sql
	0002_reset_admins_password_migration.php
	0003_create_table_sections.sql
	0004_filling_up_sections_migration.php
	application_migration.php

### Pojďme se podívat dovnitř migračních souborů

Pokud je migrační soubor SQL skript, může jeho obsah vypadat třeba takto:

	-- file: db/migrations/0003_create_table_sections.sql
	-- this migration creates the section table
	CREATE SEQUENCE seq_sections;
	CREATE TABLE sections(
	 id INT PRIMARY KEY,
	 title VARCHAR(255)
	);

Pokud je migrační soubor PHP skript, očekává se, že obsahuje třídu se stejným názvem, která je potomkem třídy Atk14Migration a která implementuje metodu up() - právě tato metoda je během migrace spušťena.

	<?php
	// file: db/migrations/0004_filling_up_sections_migration.php
	// this migration fills up the migration table
	class FillingUpSectionsMigration extends ApplicationMigration{
	 function up(){
		 foreach((array("Javascript","PHP","CSS","Python","Ruby") as $title){
			 Section::CreateNewRecord(array("title" => $title));
		 }

		 // it's also possible to utilize the dbmole!
		 $this->dbmole->insertIntoTable("sections",array(
			 "title" => "Perl"
		 ));
	 }
	}

Třída ApplicationMigration je určena pro společné funkce všech PHP migrací. V některých aplikacích to může být užitečné.

	<?php
	// file: db/migrations/application_migration.php
	/**
	 * The base class for all PHP migrations
	 *
	 * The perfect place for common methods (e.g. lorem ipsum generator)
	 */
	class ApplicationMigration extends Atk14Migration{

	}

### Spuštění migrací

Pro provedení všech čekajících migrací spusťte

	$ ./scripts/migration

V produkci je nutné mít správně nastavenou proměnou prostředí ATK14_ENV

	$ ATK14_ENV=production ./scripts/migration

nebo

	$ export ATK14_ENV=production
	$ ./scripts/migration

### Hrátky na příkazové řádce

Zjištění, které migrace ještě nebyly provedeny

	$ ./scripts/migrace -p
	# or
	$ ./scripts/migrace --preview

Zjištění, které migrace již proběhly

	$ ./scripts/migrace -l
	# or
	$ ./scripts/migrace --list

Provedení vybraných čekajících migrace mimo definované pořadí

	$ ./scripts/migrace 0145_altering_basket_items.sql 0146_order_gifts.sql

Opakované provedení migrace

	$ ./scripts/migrace -f 0002_reset_admins_password_migration.php
	# or
	$ ./scripts/migrace --force 0002_reset_admins_password_migration.php

Závěrem
-------

Jakkoli máte rádi visuální nastroje typu _phpPgAdmin_ nebo _Adminer_, nepoužívejte je pro úpravu struktury databáze. Všechny změny vždy provádějte pomocí migrací. Ušetříte tak práci sobě, ostatním, kteří aplikaci vyvíjejí s vámi.

Jen si uvědomte, že nově příchozí programátor, který vstupuje do projektu, si jej z Gitu naklonuje, vytvoří databázi, spustí migrace a v tu ránu může začít pracovat na své vlastní kopii.

Podobně budou patrně postupovat i nástroje pro automatické testování projektu (např. [Travis](https://travis-ci.org/)): naklonování zdrojového kódu &rarr; vytvoření prázdné testovací databáze &rarr; spuštění migrací &rarr; spuštění všech testů &rarr; úklid (smazání testovací databáze...) &rarr; report výsledku testování.
