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
$title = 'Template | Tạo tâp tin mới';
include $system_root . '/system/layout/header.php';

if (is_login()) {
    echo '<div class="phdr"><a href="/cms" title="Quản lý tập tin"><i class="fa fa-tachometer" aria-hidden="true"></i> Quản lý tập tin</a> | <b>Tạo mới</b></div>';
    if ($AuthorSite['remaining_size'] < $AuthorSite['max_upload']) {
        $filename = rwurl(htmlspecialchars($_POST['name']));
        if (strtolower($_SERVER['REQUEST_METHOD']) == 'post') {
            file_put_contents($builder_site['public_html'] . '/' . $filename, 'Xin chào, đây là một tập tin mới');
            header('Location: /cms/edit.php?file=' . $filename);
            exit();
        }
        echo '
        <div class="menu" style="text-align:center">
            <form method="post" action="">
                <p><b>Nhập tên tập tin:</b></p>
                <p><input type="text" name="name" value="" /></p>
                <p><button type="submit" class="button">Tạo ngay</button></p>
            </form>
        </div>
        ';
    } else {
        echo '<div class="rmenu">Bạn không thể tạo tập tin nữa. Dung lượng tối đa cho tài khoản của bạn đã đạt đến giới hạn.</div>';
    }
} else {
    header('Location: /cms');
    exit();
}
include $system_root . '/system/layout/footer.php';
