<?php
/**
 * Either some parts of ATK14 system (i.e. mailing subsystem) or some third party libs
 * could be configured by constants or variables.
 * 
 * This file is the right place to do such configuration.
 *
 * You can inspect all ATK14 application`s constants in atk14/default_settings.php
 * 
 * All the application constants should be inspected by calling:
 *	$ ./scripts/dump_settings
 * 
 * A certain constant should be inspected this way:
 *	$ ./scripts/dump_settings DEFAULT_EMAIL
 */

definedef("DEFAULT_EMAIL","your@email.com");
definedef("ATK14_ADMIN_EMAIL",DEFAULT_EMAIL); // the address for sending error reports and so on...

definedef("ATK14_APPLICATION_NAME","ATK14 Book");
definedef("ATK14_APPLICATION_DESCRIPTION","The greatest (and only) book about an awesome PHP framework ");

definedef("ATK14_HTTP_HOST",PRODUCTION ? "book.atk14.net" : "atk14book.localhost");

date_default_timezone_set('Europe/Prague');

definedef("REDIRECT_TO_SSL_AUTOMATICALLY",PRODUCTION);

// Automatic redirection to the ATK14_HTTP_HOST
definedef("REDIRECT_TO_CORRECT_HOSTNAME_AUTOMATICALLY",false);

definedef("SOURCE_CODE_SERVER_URL","https://www.atk14.net/");

if(DEVELOPMENT || TEST){
	// a place for development and testing environment settings

	ini_set("display_errors","1");
}

if(PRODUCTION){
	// a place for production environment settings

	if(php_sapi_name()!="cli"){
		ini_set("display_errors","0");
	}
}
