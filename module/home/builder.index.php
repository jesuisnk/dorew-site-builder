<?php

/**
 * DorewSite Software
 * Version: SiteBuilder
 * Author: Dorew
 * Website: https://dorew.gq
 * License: license.txt
 * Copyright: (C) 2022 Dorew All Rights Reserved.
 * This file is part of the source code.
 */

$builder_root = str_replace('module/builder/', '', $_SERVER['DOCUMENT_ROOT']);

require_once $builder_root . '/system/config/system.config.php';

// Is the system path correct?
if (!is_dir($builder_root)) {
	header('HTTP/1.1 503 Service Unavailable.', TRUE, 503);
	echo 'Your system folder path does not appear to be set correctly. Please open the following file and correct this: ' . pathinfo(__FILE__, PATHINFO_BASENAME);
	exit(3); // EXIT_CONFIG
}

define('SELF', pathinfo(__FILE__, PATHINFO_BASENAME));

// Path to the system directory
define('BASEPATH', $builder_root);
define('VIEWPATH', $builder_site['public_html']);

require_once BASEPATH . 'system/config/builder.config.php';
require_once BASEPATH . 'system/vendor/autoload.php';

//echo $builder_site['public_html'];

$ext_path = explode('.', $pathTWIG);
if (count($ext_path) < 2) {
	$check_ext = 'html';
} else {
	$check_ext = array_pop($ext_path);
}
$check_ext = strtolower($check_ext);

function get_format($ext)
{
	$mime = array(
		'htm' => 'text/html',
		'html' => 'text/html',
		'php' => 'text/html',
		'twig' => 'text/html',
		'txt' => 'text/plain',
		'css' => 'text/css',
		'js' => 'application/javascript',
		'json' => 'application/json',
		'xml' => 'application/xml',
		'rss' => 'application/rss+xml',
		/*
		'png' => 'image/png',
		'jpg' => 'image/jpeg',
		'jpeg' => 'image/jpeg',
		'gif' => 'image/gif',
		'ico' => 'image/x-icon',
		'svg' => 'image/svg+xml',
		'bmp' => 'image/bmp',
		'tiff' => 'image/tiff',
		'tif' => 'image/tiff',
		'webp' => 'image/webp',
		'psd' => 'image/vnd.adobe.photoshop',
		*/
	);
	if ($mime[$ext]) {
		return $mime[$ext];
	} else {
		return 'text/html';
	}
}

header('Content-Type: ' . get_format($check_ext));

if (in_array($check_ext, $image_ext)) {
	readfile($builder_site['public_html'] . '/' . $pathTWIG);
	exit;
}

$loader = new \Twig\Loader\FilesystemLoader(VIEWPATH);
$twig = new \Twig\Environment($loader);
spl_autoload_register(function ($className) {
	$filepath = BASEPATH . "system/functions/" . $className . ".php";
	if (file_exists($filepath)) require_once $filepath;
	//echo $className;
});

$FormURI = new FormURI();
$phpSQLite3 = new phpSQLite3();
$SomeFunctions = new SomeFunctions();

$twig->addExtension($FormURI);
$twig->addExtension($phpSQLite3);
$twig->addExtension($SomeFunctions);
$twig->addExtension(new SomeFilter());

$twigrender = $twig->render($pathTWIG, [
	'dir' => ['css' => '/', 'js' => '/', 'img' => '/'],
	'api' => [
		'is_login' => $GLOBALS['phpSQLite3']->is_login(),
		'uri' => [
			'segments' => $GLOBALS['FormURI']->get_uri_segments(),
			'current' => $GLOBALS['FormURI']->current_url()
		],
		'browser' => [
			'ip' => $GLOBALS['SomeFunctions']->ip(),
			'user_agent' => $GLOBALS['SomeFunctions']->user_agent()
		],
	]
]);

if (get_format($check_ext) == 'text/html') {
    echo str_replace('</body>', '<div style="display:none!important">Powered By DorewSite</div>
    </body>', $twigrender);
} else echo $twigrender;