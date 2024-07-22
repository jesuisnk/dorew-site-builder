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

define('_DOREW', 1);

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ERROR);
ini_set('default_charset', 'UTF-8');
date_default_timezone_set('Asia/Ho_Chi_Minh');

require_once str_replace('module/builder/', '', $_SERVER['DOCUMENT_ROOT']) . '/system/config/system.config.php';

if (in_array($system_domain, $ArraySystemDomain) || count($ArrayDomain) <= 2) {
	header('Location: /error');
	exit;
} else {
	require_once str_replace('module/builder/', '', $_SERVER['DOCUMENT_ROOT']) . '/module/home/builder.index.php';
}
