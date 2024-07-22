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

defined('_DOREW') or die('Access denied');

ini_set('display_errors', 0);
ini_set('display_startup_errors', 1);
error_reporting(E_ERROR);
ini_set('default_charset', 'UTF-8');
date_default_timezone_set('Asia/Ho_Chi_Minh');

require_once $_SERVER['DOCUMENT_ROOT'] . '/system/config/system.config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/system/config/func/QuerySQL.php';
if (class_exists('QuerySQL')) {
    $QuerySQL = new QuerySQL();
} else {
    echo 'Class QuerySQL does not exist';
    exit;
}
require_once $_SERVER['DOCUMENT_ROOT'] . '/system/config/func/SMTP.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/system/config/func/PHPMailer.php';

function is_login()
{
    global $QuerySQL;
    $account = strtolower($_COOKIE['user']);
    if (isset($account)) {
        $count = $QuerySQL->get_row_count('users', ['nick' => $account, 'operator' => '=']);
        if ($count > 0) {
            $row = $QuerySQL->select_table_row_data('users', 'nick', $account);
            $encrypted_pass = encrypt_password($account, $row['pass']);
            if ($_COOKIE['token'] == $encrypted_pass) {
                return $row['nick'];
            } else {
                return false;
            }
        }
    }
}

function encrypt_password($user = null, $pass = null)
{
    if (!$user || !$pass) {
        return false;
    } else {
        $user = strtolower($user);
        $new_pass = sha1(md5($user) . 'dorew' . $pass);
        return $new_pass;
    }
}

function login_password($pass = null)
{
    if (!$pass) {
        return false;
    } else {
        $new_pass = md5(md5($pass) . 'dorew');
        return $new_pass;
    }
}

function auto_login($user = null, $token = null)
{
    global $QuerySQL;
    if (!$user || !$token) {
        return false;
    } else {
        $user_data = $QuerySQL->select_table_row_data('users', 'nick', $user);
        $cond_pass = encrypt_password($user_data['nick'], $user_data['pass']);
        if ($token == $cond_pass) {
            setcookie('user', $user_data['nick'], time() + 31536000);
            setcookie('token', $cond_pass, time() + 31536000);
        } else return false;
    }
}

function display_layout()
{
    $ua = $_SERVER['HTTP_USER_AGENT'];
    $arrUA = strtolower($ua);
    if (preg_match('/windows|ipod|ipad|iphone|android|webos|blackberry|midp/', $arrUA) && preg_match('/mobile/', $arrUA)) {
        return 'mobile';
    } elseif (preg_match('/mobile/', $arrUA)) return 'mobile';
    else return 'desktop';
}

function paging($url, $p, $max)
{
    $p = (int)$p;
    $max = (int)$max;
    $b = '';
    if ($max > 1) {
        $a = '<a class="pagenav" href="' . $url;
        if ($p > $max) {
            $p = $max;
            $b .= 'a';
        }
        if ($p > 1) {
            $b .= $a . ($p - 1) . '">&laquo;</a>';
        }
        if ($p > 3) {
            $b .= $a . '1">1</a>';
        }
        if ($p > 4) {
            $b .= '<span class="disabled">...</span>';
        }
        if ($p > 2) {
            $b .= $a . ($p - 2) . '">' . ($p - 2) . '</a>';
        }
        if ($p > 1) {
            $b .= $a . ($p - 1) . '">' . ($p - 1) . '</a>';
        }
        $b .= '<span class="currentpage"><b>' . $p . '</b></span>';
        if ($p < ($max - 1)) {
            $b .= $a . ($p + 1) . '">' . ($p + 1) . '</a>';
        }
        if ($p < ($max - 2)) {
            $b .= $a . ($p + 2) . '">' . ($p + 2) . '</a>';
        }
        if ($p < ($max - 3)) {
            $b .= '<span class="disabled">...</span>';
        }
        if ($p < $max) {
            $b .= $a . $max . '">' . $max . '</a>';
        }
        if ($p < $max) {
            $b .= $a . ($p + 1) . '">&raquo;</a>';
        }
        return $b;
    }
}

function fulltime_ago($time_in_thePast)
{
    $result = date('H:i, d/m/Y', $time_in_thePast);
    return $result;
}

function time_ago($time_in_thePast)
{
    if (!$time_in_thePast) {
        $time_in_thePast = time();
    }
    $countdown = date('U') - $time_in_thePast;
    $time_day = date('z') - date('z', $time_in_thePast);
    if ($time_day < 0) {
        $time_day = date('z', $time_in_thePast) - date('z');
    }
    if ($countdown < 60 && $time_day == 0) {
        if ($countdown == 0) {
            $result = 'vừa xong';
        } else {
            $result = $countdown . ' giây trước';
        }
    } elseif ($countdown >= 60 && $time_day <= 1) {
        if ($time_day == 0) {
            if ($countdown > 3600) {
                $result = 'Hôm nay, ' . date('H:i', $time_in_thePast);
            } else {
                $result = round(trim($countdown / 60), '0') . ' phút trước';
            }
        } else {
            $result = 'Hôm qua, ' . date('H:i', $time_in_thePast);
        }
    } else {
        if ($countdown > 31622400) {
            $result = date('H:i, d/m/Y', $time_in_thePast);
        } elseif ($countdown >= 2592000) {
            $result = round(trim($countdown / 2592000), '0') . ' tháng trước';
        } elseif ($countdown >= 604800) {
            $result = round(trim($countdown / 604800), '0') . ' tuần trước';
        } else {
            $day = round(trim($countdown / 86400), '0');
            if ($day == 7) {
                $result = '1 tuần trước';
            } else {
                $result = $day . ' ngày trước';
            }
        }
    }
    return $result;
}

function rschar($string)
{
    $string = str_replace(" ", "-", str_replace("..", ".", $string));
    $string = str_replace("@", "a", str_replace("!", "i", $string));
    $string = str_replace("+", "cong", str_replace("&", "va", $string));
    $string = str_replace("(", "", str_replace(")", "", $string));
    $string = str_replace("^", "", str_replace("#", "", $string));
    $string = str_replace("*", "", str_replace("|", "", $string));
    $string = str_replace("=", "", str_replace("~", "", $string));
    $string = str_replace("/", "-", str_replace("\\", "-", $string));
    $string = str_replace(":", "", str_replace(",", "", $string));
    $string = str_replace("--", "-", $string);
    $string = str_replace("--", "-", $string);
    $string = str_replace("%", "", $string);
    $string = str_replace("$", "", $string);
    $string = str_replace(";", "", $string);
    $string = str_replace("'", "", $string);
    $string = str_replace('"', "", $string);
    $string = str_replace("?", "", $string);
    $string = str_replace("[", "", $string);
    $string = str_replace("]", "", $string);
    $string = str_replace("`", "", $string);
    $string = str_replace("•", "", $string);
    $string = str_replace("√", "", $string);
    $string = str_replace("π", "", $string);
    $string = str_replace("÷", "", $string);
    $string = str_replace("×", "", $string);
    $string = str_replace("¶", "", $string);
    $string = str_replace("∆", "", $string);
    $string = str_replace("£", "", $string);
    $string = str_replace("¢", "", $string);
    $string = str_replace("¥", "", $string);
    $string = str_replace(",", "", $string);
    $string = str_replace("°", "", $string);
    $string = str_replace("=", "", $string);
    $string = str_replace("{", "", $string);
    $string = str_replace("}", "", $string);
    $string = str_replace("…", "", $string);
    $string = str_replace("©", "", $string);
    $string = str_replace("]", "", $string);
    $string = str_replace("]", "", $string);
    $string = str_replace("<", "", $string);
    $string = str_replace(">", "", $string);
    $string = preg_replace("/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/", 'a', $string);
    $string = preg_replace("/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/", 'e', $string);
    $string = preg_replace("/(ì|í|ị|ỉ|ĩ)/", 'i', $string);
    $string = preg_replace("/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/", 'o', $string);
    $string = preg_replace("/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/", 'u', $string);
    $string = preg_replace("/(ỳ|ý|ỵ|ỷ|ỹ)/", 'y', $string);
    $string = preg_replace("/(đ)/", 'd', $string);
    $string = preg_replace("/(À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ)/", 'A', $string);
    $string = preg_replace("/(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ)/", 'E', $string);
    $string = preg_replace("/(Ì|Í|Ị|Ỉ|Ĩ)/", 'I', $string);
    $string = preg_replace("/(Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ)/", 'O', $string);
    $string = preg_replace("/(Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ)/", 'U', $string);
    $string = preg_replace("/(Ỳ|Ý|Ỵ|Ỷ|Ỹ)/", 'Y', $string);
    $string = preg_replace("/(Đ)/", 'D', $string);
    $string = mb_strtolower($string, 'utf8');
    return $string;
}

function rwurl($string)
{
    $patterns = array("/ắ/", "/ằ/", "/ẳ/", "/ẵ/", "/ặ/", "/ấ/", "/ầ/", "/ẩ/", "/ẫ/", "/ậ/", "/ố/", "/ồ/", "/ổ/", "/ỗ/", "/ộ/", "/ớ/", "/ờ/", "/ở/", "/ỡ/", "/ợ/", "/ứ/", "/ừ/", "/ử/", "/ữ/", "/ự/", "/á/", "/à/", "/ả/", "/ã/", "/ạ/", "/ó/", "/ò/", "/ỏ/", "/õ/", "/ọ/", "/é/", "/è/", "/ẻ/", "/ẽ/", "/ẹ/", "/ế/", "/ề/", "/ể/", "/ễ/", "/ệ/", "/í/", "/ì/", "/ỉ/", "/ĩ/", "/ị/", "/ý/", "/ỳ/", "/ỷ/", "/ỹ/", "/ỵ/", "/ú/", "/ù/", "/ủ/", "/ũ/", "/ụ/", "/Ắ/", "/Ằ/", "/Ẳ/", "/Ẵ/", "/Ặ/", "/Ấ/", "/Ầ/", "/Ẩ/", "/Ẫ/", "/Ậ/", "/Ố/", "/Ồ/", "/Ổ/", "/Ỗ/", "/Ộ/", "/Ớ/", "/Ờ/", "/Ở/", "/Ỡ/", "/Ợ/", "/Ứ/", "/Ừ/", "/Ử/", "/Ữ/", "/Ự/", "/Á/", "/À/", "/Ả/", "/Ã/", "/Ạ/", "/Ó/", "/Ò/", "/Ỏ/", "/Õ/", "/Ọ/", "/É/", "/È/", "/Ẻ/", "/Ẽ/", "/Ẹ/", "/Ế/", "/Ề/", "/Ể/", "/Ễ/", "/Ệ/", "/Í/", "/Ì/", "/Ỉ/", "/Ĩ/", "/Ị/", "/Ý/", "/Ỳ/", "/Ỷ/", "/Ỹ/", "/Ỵ/", "/Ú/", "/Ù/", "/Ủ/", "/Ũ/", "/Ụ/");
    $replacements = array("ắ", "ằ", "ẳ", "ẵ", "ặ", "ấ", "ầ", "ẩ", "ẫ", "ậ", "ố", "ồ", "ổ", "ỗ", "ộ", "ớ", "ờ", "ở", "ỡ", "ợ", "ứ", "ừ", "ử", "ữ", "ự", "á", "à", "ả", "ã", "ạ", "ó", "ò", "ỏ", "õ", "ọ", "é", "è", "ẻ", "ẽ", "ẹ", "ế", "ề", "ể", "ễ", "ệ", "í", "ì", "ỉ", "ĩ", "ị", "ý", "ỳ", "ỷ", "ỹ", "ỵ", "ú", "ù", "ủ", "ũ", "ụ", "Ắ", "Ằ", "Ẳ", "Ẵ", "Ặ", "Ấ", "Ầ", "Ẩ", "Ẫ", "Ậ", "Ố", "Ồ", "Ổ", "Ỗ", "Ộ", "Ớ", "Ờ", "Ở", "Ỡ", "Ợ", "Ứ", "Ừ", "Ử", "Ữ", "Ự", "Á", "À", "Ả", "Ã", "Ạ", "Ó", "Ò", "Ỏ", "Õ", "Ọ", "É", "È", "Ẻ", "Ẽ", "Ẹ", "Ế", "Ề", "Ể", "Ễ", "Ệ", "Í", "Ì", "Ỉ", "Ĩ", "Ị", "Ý", "Ỳ", "Ỷ", "Ỹ", "Ỵ", "Ú", "Ù", "Ủ", "Ũ", "Ụ");
    $string = preg_replace($patterns, $replacements, $string);
    return rschar($string);
}

function remove_dir($dir)
{
    if ($handle = opendir($dir)) {
        while (false !== ($item = readdir($handle))) {
            if ($item != '.' && $item != '..') {
                if (is_dir($dir . '/' . $item)) {
                    remove_dir($dir . '/' . $item);
                } else {
                    unlink($dir . '/' . $item);
                }
            }
        }
        closedir($handle);
        rmdir($dir);
    }
}

function move_dir($old_dir, $new_dir)
{
    $handler = scandir($old_dir);
    foreach ($handler as $file) {
        if ($file != "." && $file != "..") {
            if (is_dir($old_dir . "/" . $file)) {
                if (!is_dir($new_dir . "/" . $file)) {
                    mkdir($new_dir . "/" . $file);
                }
                move_dir($old_dir . "/" . $file, $new_dir . "/" . $file);
            } else {
                copy($old_dir . "/" . $file, $new_dir . "/" . $file);
                unlink($old_dir . "/" . $file);
            }
        }
    }
}

function rrmdir($dir)
{
    if (is_dir($dir)) {
        $files = scandir($dir);
        foreach ($files as $file)
            if ($file != "." && $file != "..") rrmdir("$dir/$file");
        rmdir($dir);
    } else if (file_exists($dir)) unlink($dir);
}

function rcopy($src, $dst)
{
    if (file_exists($dst))
        rrmdir($dst);
    if (is_dir($src)) {
        mkdir($dst);
        $files = scandir($src);
        foreach ($files as $file)
            if ($file != "." && $file != "..")
                rcopy("$src/$file", "$dst/$file");
    } else if (file_exists($src))
        copy($src, $dst);
}

function private_scan($dir, $array_ext)
{
    $private_upload = scandir($dir);
    $ext_unsupported = 0;
    foreach ($private_upload as $key => $value) {
        if (in_array($value, $array_ext)) {
            $ext_unsupported++;
        }
    }
    return $ext_unsupported;
}

function file_size($byte)
{
    if ($byte >= '1073741824') {
        $result = round(trim($byte / 1073741824), '2') . ' GB';
    } else if ($byte >= '1048576') {
        $result = round(trim($byte / 1048576), '2') . ' MB';
    } else if ($byte >= '1024') {
        $result = round(trim($byte / 1024), '2') . ' KB';
    } else {
        $result = round($byte, '2') . ' Bytes';
    }
    return $result;
}

function getDirectorySize($path)
{
    $totalsize = 0;
    $totalcount = 0;
    $dircount = 0;
    if ($handle = opendir($path)) {
        while (false !== ($file = readdir($handle))) {
            $nextpath = $path . '/' . $file;
            if ($file != '.' && $file != '..' && !is_link($nextpath)) {
                if (is_dir($nextpath)) {
                    $dircount++;
                    $result = getDirectorySize($nextpath);
                    $totalsize += $result['size'];
                    $totalcount += $result['count'];
                    $dircount += $result['dircount'];
                } elseif (is_file($nextpath)) {
                    $totalsize += filesize($nextpath);
                    $totalcount++;
                }
            }
        }
    }
    closedir($handle);
    $total['size'] = $totalsize;
    $total['count'] = $totalcount;
    $total['dircount'] = $dircount;
    return $total;
}

if (is_login()) {
    $AuthorSite = $QuerySQL->select_table_row_data('users', 'nick', is_login());
    /**
     * Thư mục: public_html, backup_html
     * Tập tin: public_database, backup_database
     */
    $asset_site = [
        'private_demo' => $system_root . '/assets/builder/demo',
        'private_upload' => $system_root . '/assets/builder/upload/' . $AuthorSite['subdomain'], // check ext hợp lệ trong mục backup
        'public_database' => $system_root . '/assets/builder/database/' . $AuthorSite['subdomain'],
        'backup_database' => $system_root . '/assets/builder/backup/database/' . $AuthorSite['subdomain']
    ];
    $builder_site = [
        'public_html' => $system_root . '/assets/builder/template/' . $AuthorSite['subdomain'],
        'public_database' => $asset_site['public_database'] . '/database.sqlite',
        'backup_html' => $system_root . '/assets/builder/backup/template/' . $AuthorSite['subdomain'],
        'backup_database' => $asset_site['backup_database'] . '/database.sqlite',
        'domain' => $system_domain,
        'default_index' => $AuthorSite['default_index'] ? $AuthorSite['default_index'] : 'index',
        'default_404' => $AuthorSite['default_404'] ? $AuthorSite['default_404'] : '_404',
        'default_login' => $AuthorSite['default_login'] ? $AuthorSite['default_login'] : 'dorew'
    ];

    // tạo thư mục trong $asset_site
    if (!file_exists($asset_site['public_database'])) {
        mkdir($asset_site['public_database'], 0777, true);
    }
    if (!file_exists($asset_site['backup_database'])) {
        mkdir($asset_site['backup_database'], 0777, true);
    }
    if (!file_exists($asset_site['private_upload'])) {
        mkdir($asset_site['private_upload'], 0777, true);
    }

    // tạo thư mục trong $builder_site
    if (!file_exists($builder_site['public_html'])) {
        mkdir($builder_site['public_html'], 0777, true);
        //file_put_contents($builder_site['public_html'] . '/' . $builder_site['default_index'], 'Xin chào, đây là một tâp tin mới!');
        $handler = scandir($asset_site['private_demo']);
        foreach ($handler as $file) {
            if ($file != '.' && $file != '..' && $file != '.htaccess') {
                copy($asset_site['private_demo'] . '/' . $file, $builder_site['public_html'] . '/' . $file);
            }
        }
    }
    if (!file_exists($builder_site['public_database'])) {
        file_put_contents($builder_site['public_database'], '');
    }
    if (!file_exists($builder_site['backup_html'])) {
        mkdir($builder_site['backup_html'], 0777, true);
    }
    /*
    if (!file_exists($builder_site['backup_database'])) {
        file_put_contents($builder_site['backup_database'], '');
    }
    */
    $AuthorSite_other = [
        'file_size' => getDirectorySize($builder_site['public_html'])['size'],
        'db_size' => filesize($builder_site['public_database']),
        'total_size' => getDirectorySize($builder_site['public_html'])['size'] + filesize($builder_site['public_database']),
        'remaining_size' => $AuthorSite['max_upload'] - (getDirectorySize($builder_site['public_html'])['size'] + filesize($builder_site['public_database'])),
    ];
    $AuthorSite = array_merge($AuthorSite, $AuthorSite_other);
}

function act_manager()
{
    global $AuthorSite, $builder_site;
    echo '
    <div class="phdr"><b><i class="fa fa-tachometer" aria-hidden="true"></i> Quản lý tập tin</b> | <i class="fa fa-external-link" aria-hidden="true"></i> <a target="_blank" href="http://' . $AuthorSite['subdomain'] . '.' . $builder_site['domain'] . '">Xem trang</a></div>
    <div class="menu" style="text-align:center">
        <i class="fa fa-plus-square" aria-hidden="true"></i> <a href="/cms/add.php">Thêm mới</a> | 
        <i class="fa fa-trash" aria-hidden="true"></i> <a href="/cms/delete.php">Xoá toàn bộ</a> | 
        <i class="fa fa-hdd-o" aria-hidden="true"></i> <a href="/cms/backup.php">Sao lưu</a> |
        <i class="fa fa-cog" aria-hidden="true"></i> <a href="/cms/setting.php">Cài đặt</a>
    ';
    if ($AuthorSite['right'] >= 1) echo ' | <i class="fa fa-tachometer" aria-hidden="true"></i> <a href="/cms/admin-panel.php">Quản trị</a>';
    echo '
    </div>
    ';
}

function sendMail($title, $content, $nTo, $mTo, $addcc = '')
{
    global $PHPMailer;
    $nFrom = 'Nosine';
    $mFrom = 'YOUR_EMAIL_SEND';  // địa chỉ email
    $mPass = 'YOUR_EMAIL_PASSWORD';       // mật khẩu
    $mail             = new PHPMailer();
    $body             = $content;
    $mail->IsSMTP();
    $mail->CharSet    = "utf-8";
    $mail->SMTPDebug  = 0;
    $mail->SMTPAuth   = true;
    $mail->SMTPSecure = "ssl";
    $mail->Host       = "YOUR_SMTP_SERVER";
    $mail->Port       = 465;
    $mail->Username   = $mFrom;
    $mail->Password   = $mPass;
    $mail->SetFrom($mFrom, $nFrom);
    $ccmail = explode(',', $addcc);
    $ccmail = array_filter($ccmail);
    if (!empty($ccmail)) {
        foreach ($ccmail as $k => $v) {
            $mail->AddCC($v);
        }
    }
    $mail->Subject    = $title;
    $mail->MsgHTML($body);
    $address = $mTo;
    $mail->AddAddress($address, $nTo);
    if (!$mail->Send()) {
        return 0;
    } else {
        return 1;
    }
}