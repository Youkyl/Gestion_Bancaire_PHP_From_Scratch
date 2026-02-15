<?php 

include_once __DIR__ . '/helper.php';
include_once __DIR__ . '/constant.php';
include_once __DIR__ . '/env.php';

$supportedLangs = ['fr', 'en'];
$lang = $_GET['lang'] ?? $_COOKIE['lang'] ?? 'fr';

if (!in_array($lang, $supportedLangs, true)) {
	$lang = 'fr';
}

if (isset($_GET['lang']) && $_GET['lang'] !== ($_COOKIE['lang'] ?? null)) {
	setcookie('lang', $lang, time() + 60 * 60 * 24 * 30, '/');
}

$langFile = __DIR__ . '/lang/' . $lang . '.php';
$translations = file_exists($langFile)
	? require $langFile
	: require __DIR__ . '/lang/fr.php';

function t(string $key, array $params = [], ?string $default = null): string
{
	global $translations;
	$text = $translations[$key] ?? $default ?? $key;

	foreach ($params as $name => $value) {
		$text = str_replace('{' . $name . '}', (string) $value, $text);
	}

	return $text;
}

function current_lang(): string
{
	global $lang;
	return $lang;
}

function lang_switch_url(string $lang): string
{
	$uri = $_SERVER['REQUEST_URI'] ?? '/';
	$parts = parse_url($uri);
	$path = $parts['path'] ?? '/';
	$query = [];

	if (!empty($parts['query'])) {
		parse_str($parts['query'], $query);
	}

	$query['lang'] = $lang;
	$qs = http_build_query($query);

	return $path . ($qs ? '?' . $qs : '');
}