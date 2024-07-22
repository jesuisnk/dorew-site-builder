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

require_once $_SERVER['DOCUMENT_ROOT'] . '/system/config/system.func.php';

if (is_login()) {
    header('Location: /');
    exit;
}

$title = 'Trỏ tên miền';
include $system_root . '/system/layout/header.php';

$API_url = 'https://api.alwaysdata.com/v1/site/';
$API_account = 'nosine';
$path_builder = '/www/module/builder/';

if (strtolower($_SERVER['REQUEST_METHOD']) == 'post') {
    $parked_builder = strtolower(htmlspecialchars(addslashes($_POST['domain'])));
    
    // Open cURL connection
    $ch = curl_init($API_url);
    
    // Initialize HTTP headers
    $credentials = 'APIKEY account=' . $API_account;
    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($ch, CURLOPT_USERPWD, $credentials);
    
    // Define data to POST
    $data = [
        'addresses' => [
            $parked_builder
        ],
        'type' => 'php',
        'path' => $path_builder,
        'php_ini' => 'extension=sqlite3'
    ];
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    
    // Execute HTTP request
    curl_exec($ch);
    
    // Close the connection
    curl_close($ch);
}

include $system_root . '/system/layout/footer.php';