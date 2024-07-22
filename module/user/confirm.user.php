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
$title = 'Kích hoạt tài khoản';
include $system_root . '/system/layout/header.php';

if (is_login()) {
    header('Location: /');
    exit;
} else {
    echo '<div class="phdr">' . $title . '</div>';
    $nick = rwurl($_GET['user']) ? rwurl($_GET['user']) : '.';
    $subdomain = rwurl($_GET['domain']) ? rwurl($_GET['domain']) : '?';
    $check_token = htmlspecialchars(addslashes($_GET['token']));
    $user = $QuerySQL->select_table_row_data('users', 'nick', $nick);
    if ($user['subdomain'] == $subdomain && $user['confirm'] && $subdomain) {
        if ($user['confirm'] == 1) {
            echo '<div class="rmenu">Thao tác không hợp lệ!</div>';
        } else {
            if (md5(substr($user['pass'], 0, 6)) == $check_token) {
                if (strtolower($_SERVER['REQUEST_METHOD']) == 'post') {
                    $confirm_enter = htmlspecialchars(addslashes($_POST['confirm']));
                    if ($confirm_enter == $user['confirm']) {
                        $QuerySQL->update_row_table('users', 'confirm', 1, 'id', $user['id']);
                        $new_pass = encrypt_password($user['nick'], $user['pass']);
                        setcookie('user', $user['nick'], time() + 31536000);
                        setcookie('token', $new_pass, time() + 31536000);
                        echo '<div class="gmenu">Kích hoạt tài khoản thành công!</div>';
                        header('Refresh: 3; url=/');
                    } else {
                        echo '<div class="rmenu">Mã xác nhận không đúng!</div>';
                    }
                }
?>
                <div class="menu" style="text-align:center">
                    <form action="" method="post">
                        <p>Nhập mã xác nhận: <input type="text" name="confirm" /></p>
                        <p><input type="submit" value="Kích hoạt" /></p>
                        <p><br /><span style="color:#444">(Tôi chưa nhận được email của hệ thống? <a href="?user=<?php echo $nick ?>&domain=<?php echo $subdomain ?>">Yêu cầu xác nhận lại</a>!)</span></p>
                    </form>
                </div>
            <?php
            } else {
                if (strtolower($_SERVER['REQUEST_METHOD']) == 'post') {
                    $check_pass =  htmlspecialchars(addslashes($_POST['pass']));
                    $enter_email =  htmlspecialchars(addslashes($_POST['email']));
                    $check_email = htmlspecialchars(addslashes($_POST['remail']));
                    if (login_password($check_pass) != $user['pass']) {
                        echo '<div class="rmenu">Mật khẩu không chính xác!</div>';
                    } elseif ($enter_email != $user['email'] || $enter_email != $check_email) {
                        echo '<div class="rmenu">Thư điện tử không chính xác!</div>';
                    } else {
                        $confirm_account = substr($user['pass'], 0, 6);
                        $confirm_subject = 'Kích hoạt tài khoản';
                        $email_from = htmlspecialchars('noreply@nosine.gq');
                        $email_to = $user['email'];
                        $confirm_message = '
                        Xin chào, <b>' . $user['nick'] . '</b>!
                        <br/>Chào mừng bạn đến với hệ thống Nosine.<br/>
                        <br/>Bạn đang thực hiện kích hoạt trang web <b>' . $user['subdomain'] . '.' . $system_domain . '</b> của mình.
                        <br/>Mã kích hoạt của bạn là: 
                        <div style="text-align:center;font-weight:700">' . $confirm_account . '</div>
                        Vui lòng click vào đường dẫn sau để nhập mã kích hoạt: <a target="_blank" href="https://' . $system_domain . '/reg.confirm?user=' . $user['nick'] . '&domain=' . $user['subdomain'] . '&token=' . md5($confirm_account) . '">https://' . $system_domain . '/reg.confirm?user=' . $user['nick'] . '&domain=' . $user['subdomain'] . '</a>
                        <br/>Bạn có thể kích hoạt tài khoản trong vòng 24 giờ, sau khoảng thời gian này, nếu tài khoản không được kích hoạt, trang web của bạn sẽ bị xoá khỏi hệ thống.
                        <br/>Nếu bạn có bất kỳ thắc mắc nào, vui lòng liên hệ với chúng tôi qua địa chỉ: <a href="https://dorew.gq" target="_blank">https://dorew.gq</a> 
                        <br/>Cảm ơn bạn đã sử dụng hệ thống của chúng tôi!<br/>
                        <br/>Nosine!
                        <br/><br/>- - - - -<br/><br/>
                        Nếu đây không phải là bạn, hãy bỏ qua email này.
                        ';
                        $confirm_success = sendMail($confirm_subject, $confirm_message, $email_to, $email_to);
                        if ($confirm_success != 1) {
                            echo '<div class="rmenu">Lỗi rồi! Không thể gửi email xác nhận!</div>';
                        } else {
                            $QuerySQL->update_row_table('users', 'confirm', $confirm_account, 'id', $user['id']);
                            echo '<div class="gmenu">Email xác nhận đã được gửi đến địa chỉ thư điện tử của bạn!</div>';
                            header('Refresh: 3; url=?user=' . $nick . '&domain=' . $subdomain . '&token=' . md5($confirm_account));
                        }
                    }
                }
            ?>
                <div class="menu">
                    <form method="post">
                        <p><b>Tôi không thể nhận được email của hệ thống?</b></p>
                        <p>
                            <i class="fa fa-lock" aria-hidden="true"></i> Mật khẩu:<br />
                            <input type="pass" class="w3-input" name="pass">
                        </p>
                        <p>
                            <i class="fa fa-envelope" aria-hidden="true"></i> Nhập lại Email:<br />
                            <input type="text" class="w3-input" name="email">
                        </p>
                        <p>
                            <i class="fa fa-check" aria-hidden="true"></i> Xác nhận Email:<br />
                            <input type="text" class="w3-input" name="remail">
                        </p>
                        <p>
                            <center>
                                <script src="/assets/system/js/doomcaptcha.js?1" countdown="on" label="Captcha" enemies="3" type="text/javascript"></script>
                                <button style="border: 4px solid red;" type="submit" id="submit" class="button" disabled>Gửi lại yêu cầu</button>
                            </center>
                        </p>
                    </form>
                </div>
<?php
            }
        }
    } else {
        echo '<div class="rmenu">Bạn không phải chủ sở hữu của tên miền này!</div>';
    }
}

include $system_root . '/system/layout/footer.php';
