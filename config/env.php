<?php 

use Dotenv\Dotenv;
use Dotenv\Exception\InvalidPathException;

$envPath = dirname(__DIR__) . DIRECTORY_SEPARATOR . '.env';

if (file_exists($envPath)) {
	$dotenv = Dotenv::createImmutable(dirname(__DIR__));

	try {
		$dotenv->load();
	} catch (InvalidPathException $e) {
		// Ignore missing .env on production (variables are provided by the host)
	}
}

//define("DATABASE_DRIVE",$_ENV[DATABASE_DRIVE]);

?>