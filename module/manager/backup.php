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
$title = 'Sao lưu template';
include $system_root . '/system/layout/header.php';

if (is_login()) {
    $act = $_GET['act'];
    if ($act == 'upload') {
        //upload the template zip to the `backup` folder
        $file = $_FILES['file'];
        echo '
        <div class="phdr"><a href="/cms" title="Quản lý tập tin"><i class="fa fa-tachometer" aria-hidden="true"></i> Quản lý tập tin</a> | <b>Tải lên template</b></div>
        ';
        if (strtolower($_SERVER['REQUEST_METHOD']) == 'post') {
            if (!empty($file['name'])) {
                $file_name = $file['name'];
                $file_tmp = $file['tmp_name'];
                $maxSizeAllow = $AuthorSite['remaining_size']; // mặc định: 2MB
                $file_size = $file['size'];
                $file_error = $file['error'];
                $ext_allow = array('zip');
                $file_ext = strtolower(end(explode('.', $file_name)));
                if (in_array($file_ext, $ext_allow) && $file_error == 0 && $file_size <= $maxSizeAllow) {
                    $file_name_new = rwurl($file_name);
                    $file_destination = $builder_site['backup_html'] . '/' . $file_name_new;
                    if (move_uploaded_file($file_tmp, $file_destination)) {
                        $zip = new ZipArchive;
                        $zip->extractTo($asset_site['private_upload']);
                        $ext_unsupported = private_scan($asset_site['private_upload'], $img_ext);
                        if ($ext_unsupported > 0) {
                            unlink($file_destination);
                            remove_dir($asset_site['private_upload']);
                            echo '<div class="rmenu">Tải lên không thành công! Trong tệp nén của bạn có chứa tập tin không hợp lệ!</div>';
                        } else {
                            //open zip
                            $zip->open($file_destination);
                            echo '<div class="gmenu">Tải lên thành công - <b>' . file_size($file_size) . '</b></div>';
                            header('Refresh: 3; url=/cms/backup.php');
                        }
                    } else {
                        echo '<div class="rmenu">Lỗi tải lên</div>';
                    }
                } else {
                    echo '<div class="rmenu">Tập tin không hợp lệ</div>';
                }
            } else {
                echo '<div class="rmenu">Chưa chọn tập tin</div>';
            }
        }
        //form upload
        echo '
        <div class="menu" style="text-align:center">
            <form action="backup.php?act=upload" method="post" enctype="multipart/form-data">
                <p><input type="file" name="file" /></p>
                <p><button class="button" type="submit">Tải lên</button></p>
            </form>
        </div>
        ';
    } else {
        $filename = $_GET['file'];
        //check the existence of the file $filename, if so let's practice
        if (file_exists($builder_site['backup_html'] . '/' . $filename) && !empty($filename)) {
            if ($act == 'use') {
                //use template
                remove_dir($builder_site['public_html']);
                mkdir($builder_site['public_html']);
                $zip_name = $builder_site['backup_html'] . '/' . $filename;
                $zip = new ZipArchive();
                //open zip
                $zip->open($zip_name);
                // check ext unsupported
                $zip->extractTo($asset_site['private_upload']);
                $ext_unsupported = private_scan($asset_site['private_upload'], $img_ext);
                if ($ext_unsupported > 0) {
                    unlink($zip_name);
                    remove_dir($asset_site['private_upload']);
                    echo '<div class="rmenu">Áp dụng template không thành công! Trong tệp nén của bạn có chứa tập tin không hợp lệ!</div>';
                } else {
                    //unzip
                    $zip->extractTo($builder_site['public_html']);
                    //close zip
                    $zip->close();
                    remove_dir($asset_site['private_upload']);
                    header('location: /cms');
                }
            } else {
                //remove template
                unlink($builder_site['backup_html'] . '/' . $filename);
                header('location: /cms/backup.php');
            }
            exit();
        } else {
            if (in_array($act, ['use', 'del'])) {
                echo '<div class="rmenu">Tập tin <b>' . $filename . '</b> không tồn tại</div>';
            }
        }
        if (!in_array($act, ['use', 'del'])) {
            $url_file = $builder_site['backup_html'] . '/' . $filename;
            $zip_name = rwurl(htmlspecialchars($_POST['backup'])) . '.zip';
            if (strtolower($_SERVER['REQUEST_METHOD']) == 'post') {
                $zip = new ZipArchive();
                $zip->open($builder_site['backup_html'] . '/' . $zip_name, ZipArchive::CREATE);
                $files = glob($builder_site['public_html'] . '/*');
                foreach ($files as $file) {
                    $zip->addFile($file, basename($file));
                }
                $zip->close();
            }
            echo '
            <div class="phdr"><a href="/cms" title="Quản lý tập tin"><i class="fa fa-tachometer" aria-hidden="true"></i> Quản lý tập tin</a> | <b>Sao lưu tệp</b></div>
            <div class="gmenu" style="font-weight:700"><a href="?act=upload"><i class="fa fa-upload" aria-hidden="true"></i> Tải lên template</a> (tối đa ' . file_size($AuthorSite['remaining_size']) . ')</div>
            <div class="menu" style="text-align:center">
                <form method="post" action="">
                    <p><b>Tên bản sao lưu:</b></p>
                    <p><input type="text" name="backup" value="" /></p>
                    <p><button class="button" type="submit">Sao lưu</button></p>
                </form>
            </div>
            <div class="phdr">Danh sách</div>
            ';
            //browse files contained in folder `backup`
            $results_array = array();
            if (is_dir($builder_site['backup_html'])) {
                if ($handle = opendir($builder_site['backup_html'])) {
                    chdir($builder_site['backup_html']);
                    array_multisort(array_map('filemtime', ($files = glob("*"))), SORT_DESC, $files);
                    foreach ($files as $value) {
                        $checkExt = array_pop(explode('.', $value));
                        if ($checkExt == 'zip') {
                            $results_array[] = $value;
                        }
                    }
                    closedir($handle);
                }
            }
            //chdir($root);
            $list_file = array();
            foreach ($results_array as $value) {
                if (is_file($builder_site['backup_html'] . '/' . $value)) {
                    $list_file[] = $value;
                }
            }
            $total_file = count($list_file);
            if ($total_file <= 0) {
                echo '<div class="list1">Thư mục sao lưu trống!</div>';
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
            for ($i = $start; $i < $end; $i++) {
                $file_name = $list_file[$i];
                $file_size = filesize($builder_site['backup_html'] . '/' . $file_name);
                $file_size = round($file_size / 1024, 2);
                $file_size = $file_size . ' KB';
                $file_time = date('U', filemtime($builder_site['backup_html'] . '/' . $file_name));
                echo '
                <div class="list1">
                    <table width="100%">
                        <tr>
                            <td style="width:60%;text-align:left">
                                <b><i class="fa fa-file-archive-o" aria-hidden="true"></i> ' . $file_name . '</b>
                                <br/><a href="?file=' . $file_name . '&act=use" title="Sử dụng Template này"><i class="fa fa-check-circle" aria-hidden="true"></i> Sử dụng</a> | 
                                <a href="?file=' . $file_name . '&act=del" title="Xóa Template này" style="color:red"><i class="fa fa-trash" aria-hidden="true"></i> Xóa</a> | 
                                <a href="/cms//download.php?get=' . $file_name . '" title="Tải Template này" style="color:green"><i class="fa fa-download" aria-hidden="true"></i> Tải về</a>
                            </td>
                            <td style="width:40%;text-align:right">
                                <b>' . $file_size . '</b>
                                <br/>' . time_ago($file_time) . '
                            </td>
                        </tr>
                    </table>
                </div>
                ';
            }
            if ($total_file > $per) {
                echo '<center><div class="topmenu"><div class="pagination">' . paging('?' . $namepage . '=', $page, $page_max) . '</div></div></center>';
            }
        }
    }
} else {
    header('Location: /cms');
    exit();
}
include $system_root . '/system/layout/footer.php';
