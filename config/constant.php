<?php 

define('PATH_ROOT', dirname($_SERVER['DOCUMENT_ROOT']));

// Détection automatique du protocole (http ou https), compatible proxy (Render)
$protocol = $_SERVER['HTTP_X_FORWARDED_PROTO']
	?? (((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || ($_SERVER['SERVER_PORT'] ?? null) == 443) ? 'https' : 'http');

// Détection automatique de l'hôte et du port, compatible proxy (Render)
$host = $_SERVER['HTTP_X_FORWARDED_HOST']
	?? ($_SERVER['HTTP_HOST'] ?? ($_SERVER['SERVER_NAME'] . ':' . $_SERVER['SERVER_PORT']));

define('WEB_ROOT', $protocol . '://' . $host);

define('CSS_ROOT', WEB_ROOT . '/css');
define('JS_ROOT', WEB_ROOT . '/js');
define('LIMIT_PAR_PAGE', 10);