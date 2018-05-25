<?php
	
	ini_set("display_errors",1);

	defined('DS') ? null : define('DS', DIRECTORY_SEPARATOR);
	defined('WWW_ROOT') ? null : define('WWW_ROOT', __DIR__);
	defined('CORE') ? null : define('CORE','Core');

	include_once('autoloader.php');

	$application = new \Core\Application\Environment();
	$application->run();