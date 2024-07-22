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
$title = 'Đăng ký';
include $system_root . '/system/layout/header.php';
$reg = 'off';

if (is_login()) {
    header('Location: /');
    exit;
} else {
    echo '<div class="phdr"><i class="fa fa-user-plus" aria-hidden="true"></i> ' . $title . '</div>';
    $TotalSizeSystem = getDirectorySize($system_root)['size'];
    if ($TotalSizeSystem > $config_builder['max_size'] || $reg == 'off') {
        echo '<div class="menu">Chức năng này đang tạm khoá!</div>';
    } else {
        if (strtolower($_SERVER['REQUEST_METHOD']) == 'post') {
            $user = strtolower(htmlspecialchars(addslashes($_POST['user'])));
            $subdomain = strtolower(htmlspecialchars(addslashes($_POST['subdomain'])));
            $pass = htmlspecialchars(addslashes($_POST['pass']));
            $repass = htmlspecialchars(addslashes($_POST['repass']));
            $email = htmlspecialchars(addslashes($_POST['email']));
            $EmailDomainAllow = ['gmail.com', 'protonmail.com', 'proton.me','nosine.gq'];
            $EmailCurrent = explode('@', $email);
            $EmailDomain = $EmailCurrent[1];
            if (preg_match('/^[a-zA-Z0-9-]+$/', $user) && preg_match('/^[a-zA-Z0-9-]+$/', $subdomain)) {
                if (empty($user) || empty($subdomain) || empty($pass) || empty($repass) || empty($email)) {
                    echo '<div class="rmenu">Vui lòng nhập đầy đủ thông tin</div>';
                } elseif (strlen($user) > 30) {
                    echo '<div class="rmenu">Tên tài khoản không được dài quá 30 ký tự</div>';
                } elseif (strlen($subdomain) > 10) {
                    echo '<div class="rmenu">Tên miền không được dài quá 10 ký tự</div>';
                } elseif (strlen($pass) < 3) {
                    echo '<div class="rmenu">Mật khẩu phải có ít nhất 3 ký tự</div>';
                } elseif (!in_array($EmailDomain, $EmailDomainAllow)) {
                    echo '
                <div class="rmenu">
                    Thư điện tử không hợp lệ (chỉ hỗ trợ gmail, protonmail). 
                    Vui lòng nhập chính xác địa chỉ email của bạn để xác minh tài khoản.
                </div>
                ';
                } else {
                    if ($pass == $repass) {
                        $check_user = $db->query("SELECT * FROM `users` WHERE `nick` = '$user'")->fetch_assoc();
                        $check_sub = $db->query("SELECT * FROM `users` WHERE `subdomain` = '$subdomain'")->fetch_assoc();
                        $check_email = $db->query("SELECT * FROM `users` WHERE `email` = '$email'")->fetch_assoc();
                        if (!empty($check_user)) {
                            $div = 'rmenu';
                            $result = 'Lỗi rồi! Tên tài khoản đã tồn tại!';
                        } elseif (!empty($check_sub)) {
                            $div = 'rmenu';
                            $result = 'Lỗi rồi! Tên miền đã tồn tại!';
                        } elseif (!empty($check_email)) {
                            $div = 'rmenu';
                            $result = 'Lỗi rồi! Email đã tồn tại!';
                        } else {
                            $save_pass = login_password($pass);
                            $confirm_account = substr($save_pass, 0, 6);
                            $confirm_subject = 'Kích hoạt tài khoản';
                            $email_to = htmlspecialchars($email);
                            $confirm_message = '
                            Xin chào, <b>' . $user . '</b>!
                            <br/>Chào mừng bạn đến với hệ thống Nosine.<br/>
                            <br/>Bạn đang thực hiện kích hoạt trang web <b>' . $subdomain . '.' . $system_domain . '</b> của mình.
                            <br/>Mã kích hoạt của bạn là: 
                            <div style="text-align:center;font-weight:700">' . $confirm_account . '</div>
                            Vui lòng click vào đường dẫn sau để nhập mã kích hoạt: <a target="_blank" href="https://' . $system_domain . '/reg.confirm?user=' . $user . '&domain=' . $subdomain . '&token=' . md5($confirm_account) . '">https://' . $system_domain . '/reg.confirm?user=' . $user . '&domain=' . $subdomain . '</a>
                            <br/>Bạn có thể kích hoạt tài khoản trong vòng 24 giờ, sau khoảng thời gian này, nếu tài khoản không được kích hoạt, trang web của bạn sẽ bị xoá khỏi hệ thống.
                            <br/>Nếu bạn có bất kỳ thắc mắc nào, vui lòng liên hệ với chúng tôi qua địa chỉ: <a href="https://dorew.gq" target="_blank">https://dorew.gq</a> 
                            <br/>Cảm ơn bạn đã sử dụng hệ thống của chúng tôi!<br/>
                            <br/>Nosine!
                            <br/><br/>- - - - -<br/><br/>
                            Nếu đây không phải là bạn, hãy bỏ qua email này.
                            ';
                            $time_reg = date('U');
                            if ($save_pass) {
                                $confirm_success = sendMail($confirm_subject, $confirm_message, $email_to, $email_to);
                                if ($confirm_success != 1) {
                                    $div = 'rmenu';
                                    $result = 'Lỗi rồi! Không thể gửi email xác nhận!';
                                } else {
                                    $div = 'gmenu';
                                    $result = 'Đăng ký thành công. <b>Thông tin:</b>
                                    <br/>- Tên tài khoản: <b>' . $user . '</b>
                                    <br/>- Mật khẩu: <b>' . $pass . '</b>
                                    <br/><b>Chú ý:</b> 1 email đã được gửi đến hòm thư điện tử của bạn. Hãy kiểm tra tất cả các mục trong hòm thư để lấy mã kích hoạt, bao gồm cả mục SPAM';
                                    $db->query("INSERT INTO `users` (`nick`, `pass`, `subdomain`,`email`,`reg`,`on`,`confirm`) VALUES ('$user', '$save_pass', '$subdomain', '$email','$time_reg','$time_reg','$confirm_account')");
                                    header('Refresh: 3; url=/reg.confirm?user=' . $user . '&domain=' . $subdomain);
                                }
                            } else {
                                $div = 'rmenu';
                                $result = 'Thông tin đăng ký sai cmnr!';
                            }
                        }
                    } else {
                        $div = 'rmenu';
                        $result = 'Lỗi rồi! Xác nhận mật khẩu không hợp lệ';
                    }
                }
            } else {
                $div = 'rmenu';
                $result = 'Lỗi rồi! Tên tài khoản và tên miền chỉ gồm các ký từ A-Z, a-z, 0-9 và -';
            }
            if ($div && $result) {
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
                    <i class="fa fa-globe" aria-hidden="true"></i> Website:<br />
                    <input type="text" name="subdomain"><b>.<?php echo $system_domain ?></b>
                </p>
                <p>
                    <i class="fa fa-envelope" aria-hidden="true"></i> Email (chỉ hỗ trợ gmail, protonmail):<br />
                    <input type="text" class="w3-input" name="email">
                </p>
                <p>
                    <i class="fa fa-lock" aria-hidden="true"></i> Mật khẩu:<br />
                    <input type="password" class="w3-input" name="pass">
                </p>
                <p>
                    <i class="fa fa-check" aria-hidden="true"></i> Xác nhận mật khẩu:<br />
                    <input type="password" class="w3-input" name="repass">
                </p>
                <p>
                    <center>
                        <script src="/assets/system/js/doomcaptcha.js?1" countdown="on" label="Captcha" enemies="4" type="text/javascript"></script>
                        <button style="border: 4px solid red;" type="submit" id="submit" class="button" disabled>Đăng Ký</button>
                    </center>
                </p>
            </form>
        </div>
<?php
    }
}

include $system_root . '/system/layout/footer.php';
