<?php

	use Core\Apps\Php;
	use Core\Apps\Mysql;

	// set php timeout
	set_time_limit(0);
	
	require __DIR__.'/functions.php';
	require __DIR__.'/vendor/autoload.php';

	echo 'Loading PHP Versions...'."\r\n";
	// Php::getVersions();
	// Php::getPHPINIFiles();
	Php::createPHPINIFile();

	echo 'Loading MySQL Versions...'."\r\n";
	//Mysql::getVersions();

	echo 'Done!'."\r\n";

?>