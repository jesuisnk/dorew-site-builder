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

if (!is_login()) {
    header('Location: /');
    exit;
}

$filename = htmlspecialchars(addslashes($_GET['get']));
if (file_exists($builder_site['backup_html'] . '/' . $filename)) {
    $real_link = $builder_site['backup_html'] . '/' . $filename;
    $new_filename =  $AuthorSite['subdomain'] . '-' . $filename;
    $fp = fopen($real_link, 'rb');
    header("Content-Type: application/octet-stream");
    header("Content-Disposition: attachment; filename=$new_filename");
    header("Content-Length: " . filesize($real_link));
    fpassthru($fp);
} else {
    echo 'Bạn không phải là chủ sở hữu của template này!';
    header('Refresh: 3; url=https://bafybeicspfvrspf7z3mhroqtqt6fr7ip33i3r3gh46p7srsfc7eeh6gjiq.ipfs.nftstorage.link');
    exit;
}
