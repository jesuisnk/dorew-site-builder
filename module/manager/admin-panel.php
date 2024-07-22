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
$title = 'Bảng quản trị';
include $system_root . '/system/layout/header.php';

if (is_login() && $AuthorSite['right'] >= 1) {
    echo '<div class="phdr" style="font-weight:700"><i class="fa fa-cogs" aria-hidden="true"></i> <a href="/cms/admin-panel.php">Quản trị</a></div>';
    $mod = isset($_GET['mod']) ? $_GET['mod'] : 'index';
    $nick = isset($_GET['user']) ? $_GET['user'] : '.';
    $user = $QuerySQL->select_table_row_data('users', 'nick', $nick);
    if ($user['id'] >= '1' && $user['nick'] != $AuthorSite['nick']) {
        switch ($mod) {
            case 'ban':
                echo '<div class="topmenu" style="font-weight:700">' . $user['subdomain'] .  '.' . $system_domain . ' / Khoá tài khoản</div>';
                if (strtolower($_SERVER['REQUEST_METHOD']) == 'post') {
                    $QuerySQL->update_row_array_table('users', [
                        'right' => '-1'
                    ], 'id', $user['id']);
                    echo '<div class="gmenu">Đã khoá tài khoản thành công</div>';
                    header('Refresh: 3; url=/cms/admin-panel.php?user=' . $nick);
                }
                echo '
                <div class="menu" style="text-align:center">
                    <form method="post" action="">
                        <p><button type="submit" class="button">Đồng ý</button></p>
                    </form>
                </div>';
                break;
            case 'unban':
                echo '<div class="topmenu" style="font-weight:700">' . $user['username'] . ' ' . $system_domain . '/ Mở khoá tài khoản</div>';
                if (strtolower($_SERVER['REQUEST_METHOD']) == 'post') {
                    $QuerySQL->update_row_array_table('users', [
                        'right' => '0'
                    ], 'id', $user['id']);
                    echo '<div class="gmenu">Đã mở khoá tài khoản thành công</div>';
                    header('Refresh: 3; url=/cms/admin-panel.php?user=' . $nick);
                }
                echo '
                <div class="menu" style="text-align:center">
                    <form method="post" action="">
                        <p><button type="submit" class="button">Đồng ý</button></p>
                    </form>
                </div>';
                break;
                break;
            case 'delete':
                echo '<div class="topmenu" style="font-weight:700">' . $user['subdomain'] .  '.' . $system_domain . ' / Xóa tài khoản</div>';
                if (strtolower($_SERVER['REQUEST_METHOD']) == 'post') {
                    $QuerySQL->delete_row_table('users', 'id', $user['id']);
                    $user_assets = [
                        'public_database' => $system_root . '/assets/builder/database/' . $user['subdomain'],
                        'backup_database' => $system_root . '/assets/builder/backup/database/' . $user['subdomain']
                    ];
                    $user_site = [
                        'public_html' => $system_root . '/assets/builder/template/' . $user['subdomain'],
                        'public_databse' => $user_assets['public_database'] . '/database.sqlite',
                        'backup_html' => $system_root . '/assets/builder/backup/template/' . $user['subdomain'],
                        'backup_databse' => $user_assets['backup_databse'] . '/database.sqlite'
                    ];

                    remove_dir($user_site['public_html']);
                    rmdir($user_site['public_html']);

                    unlink($user_site['public_databse']);
                    remove_dir($user_assets['public_database']);
                    rmdir($user_assets['public_database']);

                    unlink($user_site['backup_databse']);
                    remove_dir($user_assets['backup_database']);
                    rmdir($user_assets['backup_database']);

                    echo '<div class="gmenu">Đã xóa tài khoản thành công</div>';
                    header('Refresh: 3; url=/cms/admin-panel.php');
                }
                echo '
                <div class="menu" style="text-align:center">
                    <form method="post" action="">
                        <p><button type="submit" class="button">Đồng ý</button></p>
                    </form>
                </div>';
                break;
            case 'backup':
                echo '<div class="topmenu" style="font-weight:700">' . $user['subdomain'] .  '.' . $system_domain . ' / Dung lượng sao lưu</div>';
                if (strtolower($_SERVER['REQUEST_METHOD']) == 'post') {
                    $QuerySQL->update_row_array_table('users', [
                        'max_upload' => $_POST['max_upload']
                    ], 'id', $user['id']);
                    header('Refresh: 3; url=/cms/admin-panel.php?user=' . $nick);
                    echo '<div class="gmenu">Đã cập nhật dung lượng sao lưu thành công</div>';
                }
                $max_allow = [
                    '0' => '0 MB',
                    '2097152' => '2 MB',
                    '5242880' => '5 MB',
                    '750760' => '7 MB',
                    '10485760' => '10 MB',
                ];
                echo '
                <div class="menu" style="text-align:center">
                    <form method="post" action="">
                        <p>
                        Chọn mức dung lượng:
                        <select name="max_upload">
                ';
                foreach ($max_allow as $key => $val) {
                    if ($key == $user['max_upload']) {
                        echo '<option value="' . $key . '" selected>' . $val . '</option>';
                    } else {
                        echo '<option value="' . $key . '">' . $val . '</option>';
                    }
                }
                echo '
                        </select>
                        </p>
                        <p><button type="submit" class="button">Xác nhận</button></p>
                    </form>
                </div>';
                break;
            default:
                echo '
                <div class="topmenu" style="font-weight:700">' . $user['subdomain'] .  '.' . $system_domain . '</div>
                ';
                if ($user['right'] >= 0) {
                    echo '<div class="list1"><a href="?user=' . $user['nick'] .  '&mod=unban">Khoá tài khoản</a></div>';
                } else {
                    echo '<div class="list2"><a href="?user=' . $user['nick'] .  '&mod=ban">Khoá tài khoản</a></div>';
                }
                echo '
                <div class="list1"><a href="?user=' . $user['nick'] .  '&mod=delete">Xoá tài khoản</a></div>
                <div class="list1"><a href="?user=' . $user['nick'] .  '&mod=backup">Dung lượng sao lưu</a></div>
                ';
                break;
        }
    } else {
        echo '<div class="topmenu" style="font-weight:700">Danh sách thành viên</div>';
        $list_user = $QuerySQL->select_table_data('users', 'id', 'DESC');
        $total = count($list_user);
        $per = 10;
        $namepage = 'page';
        $page_max = ceil($total / $per);
        $page = isset($_GET[$namepage]) ? (int) $_GET[$namepage] : 1;
        $start = ($page - 1) * $per;
        $end = $start + $per;
        if ($end >= $total) {
            $end = $total;
        }
        $list_user_page = $QuerySQL->select_table('users', '*', null, 'id', 'ASC', ['start' => $start, 'end' => $end]);
        if ($list_user) {
            foreach ($list_user_page as $user) {
                echo '
                <div class="list1">
                    <a href="http://' . $user['subdomain'] .  '.' . $system_domain . '"><i class="fa fa-external-link" aria-hidden="true"></i></a> <a href="?user=' . $user['nick'] .  '">' . $user['subdomain'] .  '.' . $system_domain . '</a>';
                    if ($user['nick'] == $AuthorSite['nick']) echo ' (tôi)';
                echo '
                    <br/> <i class="fa fa-calendar" aria-hidden="true"></i> Ngày tham gia: <b>' . fulltime_ago($user['reg']) . '</b></div>
                ';
            }
            if ($total > $per) {
                echo '<center><div class="gmenu"><div class="pagination">' . paging('?' . $namepage . '=', $page, $page_max) . '</div></div></center>';
            }
        } else {
            echo '<div class="menu">Trống!</div>';
        }
    }
} else {
    header('Location: /cms');
    exit();
}

include $system_root . '/system/layout/footer.php';
