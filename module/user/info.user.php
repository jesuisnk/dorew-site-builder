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
$title = 'Thông tin người dùng';
include $system_root . '/system/layout/header.php';

if (!is_login()) {
    header('Location: /');
    exit;
} else {
    echo '<div class="phdr"><i class="fa fa-user" aria-hidden="true"></i> <a href="/account">' . $title . '</a></div>';
    $mod = strtolower($_GET['mod']);
    switch ($mod) {
        case 'password':
            echo '<div class="topmenu" style="font-weight:700">Thay đổi mật khẩu</div>';
            if (strtolower($_SERVER['REQUEST_METHOD']) == 'post') {
                $current_pass = htmlspecialchars(addslashes($_POST['current']));
                $new_pass = htmlspecialchars(addslashes($_POST['pass']));
                $repass = htmlspecialchars(addslashes($_POST['repass']));
                //echo login_password($current_pass) . '<br/>' . $AuthorSite['pass'];
                if ($AuthorSite['pass'] != login_password($current_pass)) {
                    $div = 'rmenu';
                    $result = 'Mật khẩu hiện tại không đúng!';
                } elseif ($new_pass != $repass) {
                    $div = 'rmenu';
                    $result = 'Mật khẩu không khớp!';
                } else {
                    $div = 'gmenu';
                    $result = 'Thay đổi mật khẩu thành công!';
                    $QuerySQL->update_row_table('users', 'pass', login_password($new_pass), 'nick', $AuthorSite['nick']);
                    setcookie('user', '', 0);
                    unset($_COOKIE['user']);
                    setcookie('token', '', 0);
                    unset($_COOKIE['token']);
                    header('Refresh: 3; url=/login');
                }
                if (isset($result)) {
                    echo '<div class="' . $div . '">' . $result . '</div>';
                }
            }
?>
            <div class="menu">
                <form method="post">
                    <p>
                        <i class="fa fa-key" aria-hidden="true"></i> Mật khẩu hiện tại:<br />
                        <input type="password" class="w3-input" name="current">
                    </p>
                    <p>
                        <i class="fa fa-lock" aria-hidden="true"></i> Mật khẩu mới:<br />
                        <input type="password" class="w3-input" name="pass">
                    </p>
                    <p>
                        <i class="fa fa-check" aria-hidden="true"></i> Xác nhận mật khẩu mới:<br />
                        <input type="password" class="w3-input" name="repass">
                    </p>
                    <p>
                        <center>
                            <script src="/assets/system/js/doomcaptcha.js?1" countdown="on" label="Captcha" enemies="3" type="text/javascript"></script>
                            <button style="border: 4px solid red;" type="submit" id="submit" class="button" disabled>Xác nhận</button>
                        </center>
                    </p>
                </form>
            </div>
        <?php
            break;
        default:
            $percent_used = round(($AuthorSite['total_size'] / $AuthorSite['max_upload']) * 100);
            //echo file_size($AuthorSite['remaining_size']);
        ?>
            <div class="topmenu"><a href="/account?mod=password" style="font-weight:700">Thay đổi mật khẩu</a></div>
            <div class="menu">
                <p><b>Tên tài khoản:</b> <?php echo $AuthorSite['nick'] ?></p>
                <p><b>Email:</b>
                    <?php
                    echo $AuthorSite['email'] . ' ';
                    if ($AuthorSite['confirm'] == 1) echo '<span style="color:green">(Đã xác nhận)</span>';
                    else echo '<span style="color:red">(Chưa xác nhận)</span>';
                    ?>
                </p>
                <p><b>Trang web:</b> <a href="http://<?php echo $AuthorSite['subdomain'] ?>.<?php echo $system_domain ?>"><?php echo $AuthorSite['subdomain'] ?>.<?php echo $system_domain ?></a></p>
                <p><b>Ngày tham gia:</b> <?php echo fulltime_ago($AuthorSite['reg']) ?></p>
                <p>
                    <span style="color:red;font-weight:700">Dung lượng đã sử dụng:</span>
                    <br />- <b>Tập tin:</b> <?php echo file_size($AuthorSite['file_size']) ?>
                    <br />- <b>Cơ sở dữ liệu:</b> <?php echo file_size($AuthorSite['db_size']) ?>
                    <br />- <b>Tổng:</b> <?php echo file_size($AuthorSite['total_size']) ?> / <span style="color:red;font-weight:700"><?php echo file_size($AuthorSite['max_upload']) ?></span>
                    <img src="/assets/system/images/vote.php?percent=<?php echo $percent_used ?>" alt="<?php echo $percent_used ?>%" />
                </p>
            </div>
<?php
            break;
    }
}

include $system_root . '/system/layout/footer.php';
