<?php 

define('PATH_ROOT', dirname($_SERVER['DOCUMENT_ROOT']));

// Détection automatique du protocole (http ou https)
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443 ? 'https' : 'http';

// Détection automatique de l'hôte et du port
$host = $_SERVER['HTTP_HOST'] ?? $_SERVER['SERVER_NAME'] . ':' . $_SERVER['SERVER_PORT'];

define('WEB_ROOT', $protocol . '://' . $host);

define('CSS_ROOT', WEB_ROOT . '/css');
define('JS_ROOT', WEB_ROOT . '/js');
define('LIMIT_PAR_PAGE', 10);