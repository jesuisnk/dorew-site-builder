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
$title = 'Đăng nhập';
include $system_root . '/system/layout/header.php';

if (is_login()) {
    header('Location: /');
    exit;
} else {
    $auto_login = $_GET['token'];
    if (isset($auto_login)) {
        $auto = auto_login($_GET['user'], $auto_login);
        if ($auto == true) {
            header('Location: /');
            exit;
        }
    } else {
        echo '<div class="phdr"><i class="fa fa-sign-in" aria-hidden="true"></i> ' . $title . '</div>';
        if (strtolower($_SERVER['REQUEST_METHOD']) == 'post') {
            $user = strtolower(htmlspecialchars(addslashes($_POST['user'])));
            $pass = htmlspecialchars(addslashes($_POST['pass']));
            $check_pass = $QuerySQL->get_row_count('users', ['nick' => $user, 'pass' => login_password($pass), 'operator' => '=']);
            if ($check_pass <= 0) {
                $div = 'rmenu';
                $result = 'Tên đăng nhập hoặc mật khẩu không đúng!';
            } else {
                $user_data = $QuerySQL->select_table_row_data('users', 'nick', $user);
                $new_pass = encrypt_password($user, $user_data['pass']);
                if ($new_pass && $user_data['confirm'] == 1) {
                    $div = 'gmenu';
                    $result = 'Đăng nhập thành công';
                    setcookie('user', $user_data['nick'], time() + 31536000);
                    setcookie('token', $new_pass, time() + 31536000);
                    header('Refresh: 3; url=/');
                } elseif ($new_pass && $user_data['confirm'] != 1) {
                    $div = 'rmenu';
                    $result = 'Tài khoản của bạn chưa được kích hoạt!';
                } else {
                    $div = 'rmenu';
                    $result = 'Thông tin đăng nhập sai cmnr!';
                }
            }
            if (isset($result)) {
                echo '<div class="' . $div . '">' . $result . '</div>';
            }
        }
?>
        <div class="menu">
            <form method="post" action="">
                <p>
                    <i class="fa fa-user" aria-hidden="true"></i> Tên tài khoản:<br />
                    <input type="text" class="w3-input" name="user">
                </p>
                <p>
                    <i class="fa fa-lock" aria-hidden="true"></i> Mật khẩu:<br />
                    <input type="password" class="w3-input" name="pass">
                </p>
                
                <p><center><script src="/assets/system/js/doomcaptcha.js?1" countdown="on" label="Captcha" enemies="4" type="text/javascript"></script></p>
                <button style="border: 4px solid red;" type="submit" id="submit" class="button" disabled>Đăng nhập</button>    </center>            
                </p>
                <p style="text-align:center">(<a href="/recover">Quên mật khẩu?</a>)</p>
            </form>
        </div>
<?php
    }
}

include $system_root . '/system/layout/footer.php';
