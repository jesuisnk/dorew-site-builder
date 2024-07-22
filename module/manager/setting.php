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
$title = 'Cài đặt trang web';
include $system_root . '/system/layout/header.php';

if (!is_login()) {
    header('Location: /cms');
    exit();
} else {
    echo '<div class="phdr"><b><i class="fa fa-cog" aria-hidden="true"></i> Cài đặt mặc định</b></div>';
    if (strtolower($_SERVER['REQUEST_METHOD']) == 'post') {
        $new_index = $_POST['new_index'] ?: $AuthorSite['default_index'];
        $new_index = strtolower(htmlspecialchars(addslashes($new_index)));
        $new_404 = $_POST['new_404'] ?: $AuthorSite['default_404'];
        $new_404 = strtolower(htmlspecialchars(addslashes($new_404)));
        $new_login = $_POST['new_login'] ?: $AuthorSite['default_login'];
        $new_login = strtolower(htmlspecialchars(addslashes($new_login)));
        //$new_subdomain = $_POST['new_subdomain'] ?: $AuthorSite['subdomain'];
        $new_subdomain = $AuthorSite['subdomain'];
        $new_subdomain = strtolower(htmlspecialchars(addslashes($new_subdomain)));
        if (strlen($new_index) > 10 || strlen($new_404) > 10 || strlen($new_login) > 10) {
            echo '<div class="rmenu">Độ dài tên tập tin <b>index</b>, <b>error</b> và tên cookie không được vượt quá 10 ký tự!/div>';
        } elseif (strlen($new_subdomain) > 10) {
            echo '<div class="rmenu">Độ dài tên miền không được vượt quá 10 ký tự!/div>';
        } else {
            $check_sub = $QuerySQL->get_row_count('users', [
                'subdomain' => $new_subdomain,
                'operator' => '=',
            ]);
            if ($check_sub > 0 && $new_subdomain != $AuthorSite['subdomain']) {
                echo '<div class="rmenu">Tên miền này đã được sử dụng!</div>';
            } else {
                $QuerySQL->update_row_array_table('users', [
                    'default_index' => $new_index,
                    'default_404' => $new_404,
                    'default_login' => $new_login,
                    'subdomain' => $new_subdomain
                ], 'nick',  is_login());
                echo '<div class="gmenu">Cập nhật thành công!</div>';
                header('Refresh: 3; url=/cms/setting.php');
            }
        }
    }
    /*
                <tr>
                    <td class="left"><b>Tên miền:</b></td>
                    <td style="text-align:left"><input type="text" name="new_subdomain" value="' . $AuthorSite['subdomain'] . '" placeholder="' . $AuthorSite['subdomain'] . '" /><b>.' .  $system_domain . '</b></td>
                </tr>
    */
    echo '<div class="menu">
        <form method="post">
            <table width="100%">
                <tr>
                    <td class="left"><b>Trang lỗi:</b></td>
                    <td style="text-align:left"><input type="text" name="new_404" value="' . $AuthorSite['default_404'] . '" placeholder="' . $AuthorSite['default_404'] . '" /></td>
                </tr>
                <tr>
                    <td class="left"><b>Trang chủ:</b></td>
                    <td style="text-align:left"><input type="text" name="new_index" value="' . $AuthorSite['default_index'] . '" placeholder="' . $AuthorSite['default_index'] . '" /></td>
                </tr>
                <tr>
                    <td class="left"><b>Cookie login:</b></td>
                    <td style="text-align:left"><input type="text" name="new_login" value="' . $AuthorSite['default_login'] . '" placeholder="' . $AuthorSite['default_login'] . '" /></td>
                </tr>
            </table>
            <p><button type="submit">Thay đổi</button></p>
        </form>
    </div>
    <style>
        @media only screen and (min-width: 677px) {td.left{text-align:left;width:15%}}
        @media only screen and (max-width: 676px) {td.left{text-align:left;width:25%}}
        @media only screen and (max-width: 320px) {td.left{text-align:left;width:40%}}
    </style>
    ';
}
include $system_root . '/system/layout/footer.php';
