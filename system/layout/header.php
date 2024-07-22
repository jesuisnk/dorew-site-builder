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
require_once $_SERVER['DOCUMENT_ROOT'] . '/system/config/system.func.php';
?>
<!DOCTYPE html PUBLIC "-//WAPFORUM//DTD XHTML Mobile 1.2//EN" "http://www.openmobilealliance.org/tech/DTD/xhtml-mobile12.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width,initial-scale=1">
<meta name="description" content="Thích Lãng Du">
<meta property="og:site_name" content="Nosine">
<meta name="theme-color" content="#22292F">
<meta name="robots" content="index,follow">
<meta name="googlebot" content="index,follow">
<meta name="google" content="notranslate">
<meta name="format-detection" content="telephone=no">
<link rel="dns-prefetch" href="https://i.imgur.com">
<link rel="dns-prefetch" href="https://cdnjs.cloudflare.com">
<link rel="dns-prefetch" href="https://raw.githack.com">
<link rel="dns-prefetch" href="https://images.weserv.nl">
<link rel="dns-prefetch" href="https://cdn.jsdelivr.net">
<link rel="shortcut icon" href="https://i.imgur.com/2pfDfoN.png" />
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="/assets/system/css/default.css" rel="stylesheet">
<link href="/assets/system/css/main.css" rel="stylesheet">
<title>
<?php echo $title ? $title : $config_builder['project_name']; ?>
</title>
</head>
<body data-instant-allow-query-string>
<div class="phdr2">
<a href="/"><img src="https://i.imgur.com/vIPOhKT.png" height="60" width="60"></a>
<br/><?php echo $config_builder['project_name'] ?> - Trình Tạo Lập Trang Web
</div>
 <div class="phdr" style="text-align:center" id="head">
<?php if (is_login()) { ?>
<a href="/index.php"><i class="fa fa-home fa-lg" aria-hidden="true"></i></a>
• <a href="/account"><i class="fa fa-user fa-lg" aria-hidden="true"></i></a>
• <a href="/database"><i class="fa fa-database fa-lg" aria-hidden="true"></i></a>
• <a href="/exit"><i class="fa fa-sign-out fa-lg" aria-hidden="true"></i></a>
<?php } else { ?>
<a href="/login"><i class="fa fa-sign-in fa-lg" aria-hidden="true"></i></a>
• <a href="/reg"><i class="fa fa-user-plus fa-lg" aria-hidden="true"></i></a>
• <a href="/recover"><i class="fa fa-key fa-lg" aria-hidden="true"></i></a>
<?php } ?>
</div>