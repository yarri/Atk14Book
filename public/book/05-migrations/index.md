Migrace
=======

Migrace pomáhají udržet shodné databázové schéma ve všech nainstalovaných instancích aplikace.

Migrace je série seřazených patchů do databáze, pomocí kterých je možné přidávat tabulky, měnit stávající tabulky, vytvářet indexy, plnit tabulky datama...

Migrační soubory umísťujte do adresáře _db/migrations/_.

Every migration file must start with a numeric prefix which helps to order them. Good choice could be sequence 0001, 0002, 0003 or 201101121550, 201101121700 (i.e. current date in format YmdHi) or any other reasonable alphabetically increasing sequence.

### Příklady názvů migračních souborů

	$ ls db/migrations/
	0001_create_table_sections.sql
	0002_create_table_article.sql
	0003_filling_up_sections.php

### Let see inside a migration file

If a migration file is a SQL script (0001_create_table_sections.sql), it's content should be like this:

	-- file: db/migrations/0001_create_table_sections.sql
	-- this migration creates the section table
	CREATE SEQUENCE seq_sections;
	CREATE TABLE sections(
	 id INT PRIMARY KEY,
	 title VARCHAR(255)
	);

If a migration file is a PHP script (0003_filling_up_sections.inc), it's content should be like this:

	<?php
	// file: db/migrations/0003_filling_up_sections.php
	// this migration fills up the migration table
	class FillingUpSections extends Atk14Migration{
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

### Do the migrations

Now run

	$ php scripts/migration.php

... and all pending migrations will be executed in the given order.

In the production environment you have to run

	$ ATK14_ENV=production php scripts/migration.php

or

	$ export ATK14_ENV=production
	$ php scripts/migration.php

Vlídné doporučení
-----------------

Jakkoli máte rádi visuální nastroje typu _phpMyAdmin_, nepoužívejte je pro úpravu struktury databáze. Všechny změny vždy provádějte pomocí migrací. Ušetříte tak práci sobě, ostatním, kteří aplikaci vyvíjejí s vámi. Jen si představte, že nově příchozí programátor, který vstupuje do projektu, si z verzovacího systémy stáhne vývojovou větev, spustí migrace a v tu ránu může začít pracovat na své vlastní kopii.
