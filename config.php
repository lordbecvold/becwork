<?php // main page config

	namespace becwork\config;

	class PageConfig {

		public $config = [

			/* main config */
			"app-name"    => "Becwork",            	 // define app name
			"version"     => 4.0,                  	 // define app version
			"author"      => "Lukáš Bečvář",       	 // define app author
			"author-link" => "https://becvold.xyz/", // define author site
			"url-check"   => false,				     //	check if url valid
			"url"         => "localhost",   		 // define main app url
			"encoding"    => "utf8",               	 // define default charset
			
			/* page config */
			"maintenance" => false,		// Define maintenance status
			"dev-mode"    => true,		// define devmode enabled
			"https"       => false,		// If this = true (Site can run only on https://)

			/* mysql config */
			"mysql-address"		=> 	"127.0.0.1", 	// define mysql server ip
			"mysql-database" 	=> 	"becwork",  	// define mysql default db name
			"mysql-username"	=> 	"root", 		// define mysql user 
			"mysql-password" 	=> 	"root"			// define Mysql password
		];
	}
?>