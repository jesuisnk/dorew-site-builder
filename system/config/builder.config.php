<?php

/**
 * DorewSite Software
 * Author: Dorew
 * Email: khanh65me1@gmail.com or awginao@protonmail.com
 * Website: https://dorew.gq
 * License: license.txt
 * Copyright: (C) 2022 Dorew All Rights Reserved.
 * This file is part of the source code.
 */

defined('_DOREW') or die('Access denied');

require_once $_SERVER['DOCUMENT_ROOT'] . '/system/config/system.config.php';

$root = (isset($_SERVER['HTTPS']) ? "https://" : "http://") . $_SERVER['HTTP_HOST'];
$root .= str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);

function _remove_relative_directory($uri)
{
	$uris = array();
	$tok = strtok($uri, '/');
	while ($tok !== FALSE) {
		if ((!empty($tok) or $tok === '0') && $tok !== '..') {
			$uris[] = $tok;
		}
		$tok = strtok('/');
	}

	return implode('/', $uris);
}

function _parse_request_uri()
{
	if (!isset($_SERVER['REQUEST_URI'], $_SERVER['SCRIPT_NAME'])) {
		return '';
	}

	// parse_url() returns false if no host is present, but the path or query string
	// contains a colon followed by a number
	$uri = parse_url('http://dummy' . $_SERVER['REQUEST_URI']);
	$query = isset($uri['query']) ? $uri['query'] : '';
	$uri = isset($uri['path']) ? $uri['path'] : '';

	if (isset($_SERVER['SCRIPT_NAME'][0])) {
		if (strpos($uri, $_SERVER['SCRIPT_NAME']) === 0) {
			$uri = (string) substr($uri, strlen($_SERVER['SCRIPT_NAME']));
		} elseif (strpos($uri, dirname($_SERVER['SCRIPT_NAME'])) === 0) {
			$uri = (string) substr($uri, strlen(dirname($_SERVER['SCRIPT_NAME'])));
		}
	}

	// This section ensures that even on servers that require the URI to be in the query string (Nginx) a correct
	// URI is found, and also fixes the QUERY_STRING server var and $_GET array.
	if (trim($uri, '/') === '' && strncmp($query, '/', 1) === 0) {
		$query = explode('?', $query, 2);
		$uri = $query[0];
		$_SERVER['QUERY_STRING'] = isset($query[1]) ? $query[1] : '';
	} else {
		$_SERVER['QUERY_STRING'] = $query;
	}

	parse_str($_SERVER['QUERY_STRING'], $_GET);

	if ($uri === '/' or $uri === '') {
		return '/';
	}

	// Do some final cleaning of the URI and return it
	return _remove_relative_directory($uri);
}
$uri_path = _parse_request_uri();
if ($uri_path === '/' or substr($uri_path,  0, 1) == '_') $uri_path = $builder_site['default_index'];
$pathTWIG = explode('/', $uri_path)[0];
if (!file_exists(VIEWPATH . DIRECTORY_SEPARATOR . $pathTWIG)) {
	if (file_exists(VIEWPATH . DIRECTORY_SEPARATOR . '/' . $builder_site['default_404'])) {
		$pathTWIG = $builder_site['default_404'];
	} else {
		die('Page not found.');
	}
}
require_once BASEPATH . 'system/vendor/autoload.php';
