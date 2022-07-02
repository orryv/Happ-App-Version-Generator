<?php

	use Core\Apps\Php;
	use Core\Apps\Mysql;
	
	require __DIR__.'/functions.php';
	require __DIR__.'/vendor/autoload.php';

	// echo 'Loading PHP Versions...'."\r\n";
	// Php::getVersions();

	echo 'Loading MySQL Versions...'."\r\n";
	Mysql::getVersions();

	echo 'Done!'."\r\n";