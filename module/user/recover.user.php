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
$title = 'Quên mật khẩu';
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
        echo '<div class="phdr"><i class="fa fa-key" aria-hidden="true"></i> ' . $title . '</div>';
        if (strtolower($_SERVER['REQUEST_METHOD']) == 'post') {
            $user = strtolower(htmlspecialchars(addslashes($_POST['user'])));
            $email = htmlspecialchars(addslashes($_POST['email']));
            $check_email = $QuerySQL->get_row_count('users', ['nick' => $user, 'email' => $email, 'operator' => '=']);
            if ($check_email <= 0) {
                $div = 'rmenu';
                $result = 'Tên đăng nhập hoặc địa chỉ email không đúng!';
            } else {
                $new_pass = substr(md5(rand()), 0, 8);
                $confirm_subject = 'Khôi phục mật khẩu';
                $email_to = $email;
                $confirm_message = '
                Xin chào, <b>' . $user . '</b>!<br/>
                <br/>Bạn đang thực hiện khôi phục mật khẩu cho tài khoản của mình.
                <br/>Đây là mật khẩu mới của bạn:
                <div style="text-align:center;font-weight:700">' . $new_pass . '</div>
                Vui lòng không tiết lộ mật khẩu này cho bất kỳ ai, kể cả chúng tôi.
                <br/>Nếu bạn có bất kỳ thắc mắc nào, vui lòng liên hệ với chúng tôi qua địa chỉ: <a href="https://dorew.gq" target="_blank">https://dorew.gq</a> 
                <br/>Cảm ơn bạn đã sử dụng hệ thống của chúng tôi!<br/>
                <br/>Nosine!
                <br/><br/>- - - - -<br/><br/>
                Nếu đây không phải là bạn, hãy bỏ qua email này.
                ';
                $confirm_success = sendMail($confirm_subject, $confirm_message, $email_to, $email_to);
                if ($confirm_success != 1) {
                    $div = 'rmenu';
                    $result = 'Lỗi rồi! Không thể gửi email xác nhận!';
                } else {
                    $div = 'gmenu';
                    $result = 'Mật khẩu mới đã được gửi đến địa chỉ email của bạn!';
                    $QuerySQL->update_row_table('users', 'pass', login_password($new_pass), 'nick', $user);
                    header('Refresh: 3; url=/login');
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
                    <i class="fa fa-envelope" aria-hidden="true"></i> Địa chỉ Email:<br />
                    <input type="text" class="w3-input" name="email">
                </p>
                <p>
                    <center>
                        <script src="/assets/system/js/doomcaptcha.js?1" countdown="on" label="Captcha" enemies="4" type="text/javascript"></script>
                        <button style="border: 4px solid red;" type="submit" id="submit" class="button" disabled>Xác nhận</button>
                    </center>
                </p>
            </form>
        </div>
<?php
    }
}

include $system_root . '/system/layout/footer.php';
