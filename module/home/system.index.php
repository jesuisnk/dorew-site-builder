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
include $system_root . '/system/layout/header.php';
$support = 'https://dorew.gq';

if (is_login()) {
    $type = strtolower($_GET['type']);
    $list_type = ['css', 'js'];
    $display_type = ['CSS', 'Javascript'];
    $icon_type = ['<i class="fa fa-file-text-o" aria-hidden="true"></i>', '<i class="fa fa-code" aria-hidden="true"></i>'];
    echo act_manager(); // điều hướng quản lý tập tin
    if (!in_array($type, $list_type)) {
        echo '
        <div class="phdr"><i class="fa fa-list" aria-hidden="true"></i> <b>Danh sách</b></div>
        <div class="topmenu" style="padding:8px"><b>Tài nguyên</b></div>
        <a href="?type=css"><div class="list1"><i class="fa fa-folder-open-o" aria-hidden="true"></i> CSS</div></a>
        <a href="?type=js"><div class="list1"><i class="fa fa-folder-open-o" aria-hidden="true"></i> Javascript</div></a>
        <div class="topmenu" style="padding:8px"><b>Tập tin code</b></div>
        ';
    } else {
        echo '
        <div class="phdr"><a href="/cms"><i class="fa fa-list" aria-hidden="true"></i> Danh sách</a> | <b>' . str_replace($list_type, $display_type, $type) . '</b></div>
        ';
    }
    //quick action
    if ($_GET['act'] == 'remove' && strtolower($_POST['option']) == 'remove' && !in_array($type, $list_type)) {
        if (strtolower($_SERVER['REQUEST_METHOD']) == 'post') {
            $file = $_POST['twigfile'];
            for ($i = 0; $i < count($file); $i++) {
                $url_file = $builder_site['public_html'] . '/' . $file[$i];
                if (file_exists($url_file)) {
                    unlink($url_file);
                }
            }
            header('Location: /cms');
            exit();
        }
    }
    //browse files contained in folder `template`
    $results_array = array();
    if (is_dir($builder_site['public_html'])) {
        if ($handle = opendir($builder_site['public_html'])) {
            chdir($builder_site['public_html']);
            array_multisort(array_map('filemtime', ($files = glob("*"))), SORT_DESC, $files);
            foreach ($files as $value) {
                $checkExt = strtolower(array_pop(explode('.', $value)));
                $hidden_ext = array_merge(['zip', 'js', 'css'], $image_ext);
                if (!in_array($checkExt, $hidden_ext) && !in_array($type, $hidden_ext) || $checkExt == 'css' && $type == 'css' || $checkExt == 'js' && $type == 'js') {
                    $results_array[] = $value;
                }
            }
            closedir($handle);
        }
    }
    chdir($root);
    $list_file = array();
    foreach ($results_array as $value) {
        if (is_file($builder_site['public_html'] . '/' . $value)) {
            $list_file[] = $value;
        }
    }
    $total_file = count($list_file);
    if ($total_file <= 0) {
        echo '<div class="list1">Không có tập tin nào!</div>';
    }
    $per = 20;
    $namepage = 'page';
    $page_max = ceil($total_file / $per);
    $page = isset($_GET[$namepage]) ? (int) $_GET[$namepage] : 1;
    $start = ($page - 1) * $per;
    $end = $start + $per;
    if ($end >= $total_file) {
        $end = $total_file;
    }
    sort($list_file);
    if (!in_array($type, $list_type)) {
        echo '<form method="post" action="?act=remove">';
    }
    for ($i = $start; $i < $end; $i++) {
        $file_name = $list_file[$i];
        $file_size = filesize($builder_site['public_html'] . '/' . $file_name);
        $file_size = round($file_size / 1024, 2);
        $file_size = $file_size . ' KB';
        if (!in_array($type, $list_type)) {
            $input_checkbox = '<input type="checkbox" name="twigfile[]" value="' . $file_name . '" />';
            $file_name = '<a href="/cms/edit.php?file=' . $file_name . '">' . $file_name . '</a>';
        }
        $layout_list = '
        <div class="list1">
            <table width="100%">
                <tr>
                    <td style="width:60%;text-align:left">
                        <b>' . $input_checkbox . str_replace($list_type, $icon_type, $type) . ' ' . $file_name . '</b>
                    </td>
                    <td style="width:40%;text-align:right">
                        <b>' . $file_size . '</b>
                    </td>
                </tr>
            </table>
        </div>
        ';
        if (in_array($type, $list_type)) {
            echo '
            <a href="/cms/edit.php?file=' . $file_name . '">
                ' . $layout_list . '
            </a>
            ';
        } else {
            echo $layout_list;
        }
    }
    if ($total_file > $per) {
        if (in_array($type, $list_type)) {
            $get_type = 'type=' . $type . '&';
        }
        echo '<center><div class="topmenu"><div class="pagination">' . paging('?' . $get_type . $namepage . '=', $page, $page_max) . '</div></div></center>';
    }
    //menu quick action
    if (!in_array($type, $list_type)) {
        echo '<div class="phdr"><i class="fa fa-tasks" aria-hidden="true"></i> Thao tác nhanh</div>
    <div class="menu">
        <select name="option"><option value="remove"> Xoá tệp</option></select>
        <button type="submit">Thực hiện</button>
    </div>';
        echo '</form>';
    }
} else {
    echo '
    <div class="phdr"><i class="fa fa-superpowers" aria-hidden="true"></i> Giới thiệu</div>
    <div class="list1"><p><i class="fa fa-check-circle-o" aria-hidden="true"></i> Tạo trang web của bạn miễn phí và nhanh chóng</p>
    <p><i class="fa fa-check-circle-o" aria-hidden="true"></i> Tuỳ biến, code cùng với TWIG</p>
    <p><i class="fa fa-check-circle-o" aria-hidden="true"></i> Hệ quản trị cơ sở</p>
    <p><i class="fa fa-check-circle-o" aria-hidden="true"></i> Sao lưu, khôi phục dễ dàng</p></div>
    <div class="list1"><i class="fa fa-flag" aria-hidden="true"></i> <a href="/rules"> Điều khoản sử dụng</a></div>
    <div class="list1"><i class="fa fa-users" aria-hidden="true"></i> <a href="' . $support . '/category/21-dore-site/"> Diễn đàn hỗ trợ</a></div>
    ';
    $list_web = $QuerySQL->query_select_table('users', '*', 'WHERE `right` > "-1" AND `confirm` = "1" ORDER BY `id` DESC LIMIT 5');
    echo '
    <div class="phdr"><i class="fa fa-globe" aria-hidden="true"></i> Trang web mới (tổng '. count($QuerySQL->query_select_table('users', '*', 'WHERE `right` > "-1" AND `confirm` = "1"')) .')</div>
    ';
    if (count($list_web) > 0) {
        foreach ($list_web as $column => $row) {
            $domain = $row['subdomain'] . '.' . $system_domain;
            $url = 'http://' . $domain;
            echo '
            <div class="list1"><i class="fa fa-external-link" aria-hidden="true"></i> <a href="' . $url . '">' . $domain . '</a></div>
            ';
        }
    } else {
        echo '<div class="menu">Không có trang web nào!</div>';
    }
}

include $system_root . '/system/layout/footer.php';
