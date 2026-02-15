<?php
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

require_once dirname(__DIR__) . '/vendor/autoload.php';
require_once dirname(__DIR__) . '/src/App/core/Router.php';
require_once dirname(__DIR__) . '/config/bootstrap.php';

$router = new \App\core\Router();
$router->run();