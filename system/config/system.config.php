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

defined('_DOREW') or die('Access denied');

$system_root = $_SERVER['DOCUMENT_ROOT'];
$http_host = $_SERVER['HTTP_HOST'];
$request_uri = $_SERVER['REQUEST_URI'];

if (isset($http_host) && $http_host . $request_uri == 'nosine.alwaysdata.net' . $request_uri) {
    header('Location: http://dorew.gq' . $request_uri);
    exit();
}

// thông tin kết nối đến cơ sở dữ liệu chính
$config_builder = [
    'project_name' => 'Nosine',
    'host' => 'mysql-XXXXX.alwaysdata.net',
    'user' => 'YOUR_DB_USERNAME',
    'pass' => 'YOUR_DB_PASSWORD',
    'name' => 'YOUR_DB_NAME',
    'charset' => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
    'max_size' => 80 * 1024 * 1024 // 80MB - dung lượng tối đa của hệ thống
];

// kết nối đến cơ sở dữ liệu chính
$db = new mysqli(
    $config_builder['host'],
    $config_builder['user'],
    $config_builder['pass'],
    $config_builder['name']
);
$db->set_charset($config_builder['charset']);
$db->query("SET NAMES '{$config_builder['charset']}' COLLATE '{$config_builder['collation']}'");
if ($db->connect_error) {
    die('Không thể kết nối với CSDL. Nếu bạn nhìn thấy dòng này, hãy liên hệ với Admin!<br/>Thông tin: https://dorew.gq');
    exit;
} else {
    $result = $db->query("SHOW TABLES FROM {$config_builder['name']}");
    if ($result->num_rows == 0) {
        die('Lỗi truy vấn CSDL. Không thể tìm thấy danh sách website!');
        exit;
    } else {
        $mydb = mysqli_connect($config_builder['host'], $config_builder['user'], $config_builder['pass'], $config_builder['name']);
    }
}

// thông tin kết nối đến cơ sở dữ liệu của SiteBuilder
$host = str_replace('www.', '', $_SERVER['HTTP_HOST']);
$system_domain = $host;
$ArraySystemDomain = ['nosine.alwaysdata.net', 'localhost'];
$ArrayBuilderDomain = ['nosine.gq'];
$ArrayDomain = explode('.', $host);
if (!in_array($system_domain, $ArraySystemDomain) && count($ArrayDomain) > 2) {
    $_SERVER['DOCUMENT_ROOT'] = str_replace('module/builder/', '', $_SERVER['DOCUMENT_ROOT']);
    $system_root = $_SERVER['DOCUMENT_ROOT'];
    
    // dùng parked
    $system_sql_parked = $db->query("SELECT * FROM `users` WHERE `parked` = '{$host}'"); // kiểm tra tên miền mới
    $ParkedSite = $system_sql_parked->fetch_assoc();
    if ($ParkedSite['id'] && $ParkedSite['parked'] == $host) {
        $subdomain = $ParkedSite['subdomain'];
        $system_domain = $subdomain . '.' . $ArrayBuilderDomain[0];
        //die($system_domain);
    } else $subdomain = strtolower($ArrayDomain[0]);
    $system_domain = str_replace($subdomain . '.', '', $system_domain);
    $system_sql_user = "SELECT * FROM `users` WHERE `subdomain` = '{$subdomain}'";
    $system_result_user = $db->query($system_sql_user);
    $AuthorSite = $system_result_user->fetch_assoc();

    if (!$AuthorSite['id'] && isset($subdomain) && $subdomain != 'localhost' || !in_array($system_domain, $ArrayBuilderDomain)) {
        echo 'Trang web này không tồn tại hoặc đã bị vô hiệu hoá do vi phạm điều khoản của Dorew';
        header('Location: https://' . $system_domain);
        exit();
    } else {
        if ($AuthorSite['confirm'] != 1) {
            echo 'Trang web này đang được xử lý. Vui lòng quay lại sau!';
            exit();
        }
    }

    /**
     * Thư mục: public_html, backup_html
     * Tập tin: public_database, backup_database
     */
    $asset_site = [
        'private_demo' => $system_root . '/assets/builder/demo',
        'public_database' => $system_root . '/assets/builder/database/' . $AuthorSite['subdomain'],
        'backup_database' => $system_root . '/assets/builder/backup/database/' . $AuthorSite['subdomain']
    ];
    $builder_site = [
        'public_html' => $system_root . '/assets/builder/template/' . $AuthorSite['subdomain'],
        'public_database' => $asset_site['public_database'] . '/database.sqlite',
        'backup_html' => $system_root . '/assets/builder/backup/template/' . $AuthorSite['subdomain'],
        'backup_database' => $asset_site['backup_database'] . '/database.sqlite',
        'domain' => $system_domain,
        'default_index' => $AuthorSite['default_index'] ? $AuthorSite['default_index'] : 'index',
        'default_404' => $AuthorSite['default_404'] ? $AuthorSite['default_404'] : '_404',
        'default_login' => $AuthorSite['default_login'] ? $AuthorSite['default_login'] : 'dorew'
    ];
    
    // tạo thư mục trong $asset_site
    if (!file_exists($asset_site['public_database'])) {
        mkdir($asset_site['public_database'], 0777, true);
    }
    if (!file_exists($asset_site['backup_database'])) {
        mkdir($asset_site['backup_database'], 0777, true);
    }

    // tạo thư mục trong $builder_site
    if (!file_exists($builder_site['public_html'])) {
        mkdir($builder_site['public_html'], 0777, true);
        //file_put_contents($builder_site['public_html'] . '/' . $builder_site['default_index'], 'Xin chào, đây là một tâp tin mới!');
        $handler = scandir($asset_site['private_demo']);
        foreach ($handler as $file) {
            if ($file != '.' && $file != '..' && $file != '.htaccess') {
                copy($asset_site['private_demo'] . '/' . $file, $builder_site['public_html'] . '/' . $file);
            }
        }
    }
    if (!file_exists($builder_site['public_database'])) {
        file_put_contents($builder_site['public_database'], '');
    }
    if (!file_exists($builder_site['backup_html'])) {
        mkdir($builder_site['backup_html'], 0777, true);
    }

    $tfile_size = 0;
    $dir_public_html = new DirectoryIterator($builder_site['public_html']);
    foreach ($dir_public_html as $fileinfo) {
        if (!$fileinfo->isDot()) {
            $tfile_size += $fileinfo->getSize();
        }
    }
    $db_size = filesize($builder_site['public_database']);
    if (($tfile_size + $db_size) > ($AuthorSite['max_upload'] + 1048576)) {
        echo 'Dung lượng tối đa của website đã đạt đến giới hạn. Nếu bạn là Admin của trang, hãy nâng cấp giới hạn dung lượng hoặc xoá bớt các tập tin không cần thiết để tiếp tục sử dụng.';
        header('Refresh: 5; url=http://' . $system_domain);
        exit();
    }
}
$image_ext = ['png', 'jpg', 'jpeg', 'gif', 'ico', 'svg', 'bmp', 'tiff', 'tif', 'webp', 'psd'];
