Testing
=======

Historicky text o testovani. NUTNO PREPSAT!!!

## Unit testy

Script scripts/run\_unit\_tests vyhleda v pracovnim adresari soubory, zinicializuje tridy testovacich pripadu (Test Case) a spusti testy. Je mozne jej poustet pomoci PHP4 i PHP5.

### Pozadavky pro PHP4

	$ pear install PHPUnit

### Pozadavky pro PHP5

	$ pear install --alldeps PHPUnit2

Pozn.: Pokud mame v sytemu obe verze PHP, muzeme pomoci promenne PHP_PEAR_PHP_BIN urcit, ktera verze interpretu bude spoustena:

	$ PHP_PEAR_PHP_BIN=/usr/bin/php-cgi pear install PHPUnit

### Tridy testovaci pripadu

Unit testy umistujeme do adresare test do souboru tc_*.inc. Kazdy soubor tc_*.inc musi obsahovat tridu tc_*, ktera musi byt dedicem tridy tc_base.

Napriklad hodlame-li napsat unit testy pro funkcni kody vnitrnich objektu, ktere se nachazeji v adreasari sys/extras/inobj/, vytvorime adresar sys/extras/inobj/test/, ve kterem potom zacneme tvorit soubory tc_*.inc.

Napr. soubor test/tc_product.inc

	
	<?php
	class tc_product extends tc_base{

		// ....
		
	}
	

### Spousteni testu, skript run\_unit\_test

Testovani spustime tak, ze se prepneme do adresare s testy

	$ cd sys/extras/inobj/test/

a spustime skript run\_unit\_test

	$ run_unit_test.php

Skript automaticky vyhleda vsechny soubory tc_*.inc, nacte je a spusti metody testovacich trid zacinajici slovem test.

Skript run\_unit\_test.php jsem napsal sam a je dispozici v scripts/run\_unit\_test. Je dobre k nemu mit nastavenou cestu.

	<?php
	class tc_product extends tc_base{
		
		// bude automaticky spusteno behem testovani
		function test_zjistovani_poctu_na_sklade(){
		 // ...
		}

		// taky bude automaticky spusteno behem testovani
		function test_zjistovani_ceny(){
		 // ...
		}
	 
		// nasledujici funkce nezacina slovem test a nebude proto behem testovani spustena
		function _priprav_tesatovaci_product(){
		 //..
		}
	}
	

### Inicializace testovaciho prostredi

V pripade, ze je nutne pred samotnym zahajenim testu inicializovat nejakym zpusobem prostredi: naincludovat soubory, definovat konstanty apod, zapiseme vse potrebne do souboru test/initialize.inc.

Dulezita poznamka: pokud v souboru test/initialize.inc bude naincludovan dbmole.inc a pgmole.inc (nebo oraclemole.inc), bude automaticky vytvorena instance $dbmole. Rovnez bude automaticky zaregistrovana funcke pro zachytavani DbMole chyb.

Ukazka souboru test/initialize.inc

	<?php
	require("../../../../init.inc");
	require_once(PATH_EXTRAS_CLASSES."dbmole.inc");
	require_once(PATH_EXTRAS_CLASSES."pgmole.inc");
	require_once(PATH_EXTRAS_CLASSES."inobj/load.inc");
	require_once(PATH_EXTRAS_CLASSES."functions.inc");
	require_once(PATH_EXTRAS_CLASSES."xmole.inc");
	require_once(PATH_EXTRAS_CLASSES."dates.inc");
	require_once(PATH_EXTRAS_CLASSES."translate.inc");
	require_once(PATH_EXTRAS_CLASSES."logger.inc");
	require_once(PATH_EXTRAS_CLASSES."masterapi/load.inc");
	require_once(APP_DOCUMENT_ROOT."dbconnect.inc");
	

### Bazova trida tc_base

Pokud chceme v testovacich tridach pouzivat spolecne metody nebo vlastnosti, muzeme je definovat ve tride tc_base.

Priklad tridy tc_base (soubor test/tc_base.inc)

	<?php
	class tc_base extends tc_super_base{
		var $_BossId = 10010;
		var $_DemoId = 26107;
		var $_FidorkaId = 19316;
		var $_KofolaId = 22199;
		var $_PapirId = 15001;

		function _vezmi_kofolu(){
			return $this->_get_product($this->_KofolaId);
		}

		function _vezmi_fidorku(){
			return $this->_get_product($this->_FidorkaId);
		}

		function _vezmi_papir(){
			return $this->_get_product($this->_PapirId);
		}

		function _vezmi_vyrobce_canon(){
			return inobj_Brand::GetInstanceById(8);
		}
	}
	

### Typy testu

	<?php
	class tc_product extends tc_base{
		function test(){
			$product = inobj_Product::GetInstanceById(5);

			$this->assertNotNull($product);                         // ocekavame, ze v $product neco je
				
			$this->assertEquals(5,$product->getId());               // ocekavame hodnotu 5
			$this->assertType("intege",$product->getId());          // ocekavame typ integer

			$null_product = inobj_Product::GetInstanceById(-100);   // neexistujici id
			$this->assertNull($null_product);                       // ocekavame null

			$this->assertTrue($product->IsAction());                // ocekavame true
			$this->assertFalse($product->IsDeleted());              // ocekavame false

			$this->assertRegExp("/papir/",$product->getLabel());    // musi byt porovnatelne s regularnim vyrazem
			$this->assertNotRegExp("/paper/",$product->getLabel()); // nesmi byt porovnatelne s regularnim vyrazem

			// Dalsi testy:
			// $this->assertContains();                             // string nebo pole musi obsahovat podstrink, resp. prvek
			// $this->assertNotContains();                          // opak assertContains()

			$this->fail("produkt musi mit cenu");                   // pokud dosahneme misto, ktere je z pohledu testovani spatne, muzeme primo pouzit metody fail
		} 
	}
	

### Bezpecne testovani

Pisme testy tak, aby nemenily data v databazi. Protoze pak bude mozne testy poustet i nad ostryma datama. Dosahneme toho tak, ze na zacatku testu zahajime transakci a na konci testi provedeme ROLLBACK.

	<?php
	class tc_product extends tc_base{
		global $dbmole;

		$dbmole->begin();

		$product = inobj_Product::GetInstanceById(5);

		$this->assertTrue($product->IsVisible());

		$dbmole->doQuery("UPDATE product SET visible='N' WHERE id=5");

		$this->assertFalse($product->IsVisible());

		$dbmole->rollback();
	}

Ale lepe...

	<?php
	// file: tc_base.php
	class tc_base extends tc_super_base{
		function setUp(){
			$GLOBALS["dbmole"]->begin();
		}

		function tearDown(){
			$GLOBALS["dbmole"]->rollback();
		}
	}

... metody setUp() a tearDown() jsou automaticky spouštěny před každou testovaci funkcí.
	

### Nebezpecne testy

V pripade, ze test po sobe zanecha nejakou zmenu, nebo nas muze pripravit o penize - zaregistruje domenu apod, umistime testovaci tridu do souboru, ktery zacina vykricnikem.

Tyto soubory nebudou pri testovani automaticky provedeny, paklize nebudou vyslovne uvedeny jako parametry skriptu run\_unit\_tests.

Soubor test/!tc_registrace_domeny.inc

	<?php
	class tc_domain_registration  extends tc_base {
		
		function test_registrace_domeny(){
			// ...
		}
	}
	

...

	$ run_unit_tests.php \!tc_domain_registration.inc

## Rozdily v testech pro PHP5

### testovani typu object

	$this->assertType("object",$obj); // nefunguje v PHP5
	$this->assertTrue(is_object($obj)); // funguje v PHP4 i PHP5

### testovani rovnosti
V PHP5 jsou pri volani assertEquals() testovany i typy hodnot.

	<?php
	// ...
	$val = 5.0;
	$this->assertEquals(5,$val); // dopadne spatne v PHP5
	$this->assertEquals(5.0,$val); // v poradku pro PHP4 i PHP5

Uff...
