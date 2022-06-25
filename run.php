<?php

	use Core\Apps\Php;
	
	require __DIR__.'/functions.php';
	require __DIR__.'/vendor/autoload.php';

	echo 'Loading PHP Versions...'."\r\n";
	Php::getVersions();

	echo 'Done!'."\r\n";