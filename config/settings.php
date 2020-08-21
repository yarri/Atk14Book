<?php
/**
* Either some parts of ATK14 system (i.e. mailing subsystem) or some third party libs
* could be configured by constants or variables.
* 
* This file is the right place to do such configuration.
*
* You can inspect all ATK14 system`s constants in sys/src/default_settings.inc
*/

define("SECRET_TOKEN",PRODUCTION ? Files::GetFileContent(dirname(__FILE__)."/secret_token.txt") : "crDsduENNEnykcIwSqs0qI55AFco42NXj2AYNAqc0kTgImSBBUskdtjnlhMGwXBN");

define("ATK14_APPLICATION_NAME","ATK14 Book");
define("ATK14_HTTP_HOST",PRODUCTION ? "book.atk14.net" : "atk14book.localhost");
define("DEFAULT_EMAIL","your@email");

definedef("SOURCE_CODE_SERVER_URL","https://www.atk14.net/");

if(DEVELOPMENT || TEST){
	// a place for development and testing environment settings

	ini_set("display_errors","1");
}

if(TEST){
	// place for test environment settings

}


