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
$title = 'Đăng xuất';
include $system_root . '/system/layout/header.php';

if (!is_login()) {
    header('Location: /');
    exit;
} else {
    echo '<div class="phdr">Đăng xuất</div>';
    if (strtolower($_SERVER['REQUEST_METHOD']) == 'post') {
        setcookie('user', '', 0);
        unset($_COOKIE['user']);
        setcookie('token', '', 0);
        unset($_COOKIE['token']);
        echo '<div class="gmenu">Đăng xuất thành công</div>';
        header('Refresh: 3; url=/');
    }
    ?>
    <div class="menu" style="text-align:center">Bạn muốn đăng xuất.!?<br/>
      <form method="post" action="">
        <input type="submit" name="logout" value="Đồng ý" /> 
        <a href="/" class="btn">Về trang chủ</a>
      </form>
    </div>
    <?php
}

include $system_root . '/system/layout/footer.php';
