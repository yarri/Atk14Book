Migrations
==========

Migrations help keep the database schema consistent across all installed instances of an application.

They are a series of ordered patches applied to the database, through which you can add tables, modify existing tables, create indexes, populate lookup tables, and more.

Migration files are placed in the _db/migrations/_ directory.

Each migration file must start with a numeric prefix that ensures the migrations are applied in the correct order. Good choices include sequences like 0001, 0002, 0003, or timestamps like 201101121550, 201101121700 (i.e. the current date and time in YmdHi format), and similar.

### Examples of migration file names

```shell
$ ls db/migrations/
0000_sessions.sql
0001_users.sql
0002_reset_admins_password_migration.php
0003_create_table_sections.sql
0004_filling_up_sections_migration.php
application_migration.php
```

### Let's look inside migration files

If a migration file is an SQL script, its contents might look like this:

```sql
-- file: db/migrations/0003_create_table_sections.sql
-- this migration creates the section table
CREATE SEQUENCE seq_sections;
CREATE TABLE sections(
 id INT PRIMARY KEY,
 title VARCHAR(255)
);
```

If a migration file is a PHP script, it is expected to contain a class of the same name that is a subclass of Atk14Migration and implements the `up()` method — this method is executed during the migration.

```php
<?php
// file: db/migrations/0004_filling_up_sections_migration.php
// this migration fills up the migration table
class FillingUpSectionsMigration extends ApplicationMigration{
 function up(){
	 foreach(array("Javascript","PHP","CSS","Python","Ruby") as $title){
		 Section::CreateNewRecord(array("title" => $title));
	 }

	 // it's also possible to utilize the dbmole!
	 $this->dbmole->insertIntoTable("sections",array(
		 "title" => "Perl"
	 ));
 }
}
```

The `ApplicationMigration` class is intended for shared functionality across all PHP migrations. In some applications this can be useful.

```php
<?php
// file: db/migrations/application_migration.php
/**
 * The base class for all PHP migrations
 *
 * The perfect place for common methods (e.g. lorem ipsum generator)
 */
class ApplicationMigration extends Atk14Migration{

}
```

### Running migrations

To apply all pending migrations, run:

```shell
$ ./scripts/migration
```

In production, make sure the ATK14_ENV environment variable is set correctly:

```shell
$ ATK14_ENV=production ./scripts/migration
```

or

```shell
$ export ATK14_ENV=production
$ ./scripts/migration
```

### Command-line options

Check which migrations have not yet been applied:

```shell
$ ./scripts/migrace -p
# or
$ ./scripts/migrace --preview
```

Check which migrations have already been applied:

```shell
$ ./scripts/migrace -l
# or
$ ./scripts/migrace --list
```

Apply selected pending migrations out of the defined order:

```shell
$ ./scripts/migrace 0145_altering_basket_items.sql 0146_order_gifts.sql
```

Re-apply a migration:

```shell
$ ./scripts/migrace -f 0002_reset_admins_password_migration.php
# or
$ ./scripts/migrace --force 0002_reset_admins_password_migration.php
```

### Conclusion

However much you like visual tools like _phpPgAdmin_ or _Adminer_, don't use them to modify the database structure. Always make all changes through migrations. It will save work for you and everyone else developing the application alongside you.

Just consider a new developer joining the project: they clone it from Git, create a database, run migrations, and can immediately start working on their own copy.

Automated testing tools (e.g. [Travis](https://travis-ci.org/)) will likely follow the same approach: clone the source code &rarr; create an empty test database &rarr; run migrations &rarr; run all tests &rarr; clean up (delete the test database...) &rarr; report the test results.
