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
$title = 'Điều khoản sử dụng';
include $system_root . '/system/layout/header.php';
?>
<div class="phdr" style="font-weight:700"><?php echo $title ?></div>
<div class="menu">
    <p><?php echo $config_builder['project_name'] ?> là nền tảng tạo lập giúp bạn tạo nên một diễn đàn nhỏ hoặc một blog cá nhân</p>
    <p>Khi sử dụng <?php echo $config_builder['project_name'] ?> bạn phải hoàn toàn đồng ý với những quy định sau</p>
    <p><h4>1. Về nội dung</h4></p>
    <ul>
        <li>- Không được chứa nội dung liên quan đến các hành vi phạm tội</li>
        <li>- Không được chứa nội dung liên quan đến các vấn đề tiêu cực về tôn giáo, chính trị, chia rẽ khối đoàn kết dân tộc</li>
        <li>- Không được chứa nội dung vi phạm đạo đức, thuần phong mỹ tục của Việt Nam</li>
        <li>- Không được chứa nội dung xúc phạm danh dự, nhân phẩm hoặc xâm phạm đời tư của người khác</li>
        <li- >Những trang web vi phạm sẽ bị khóa không cần báo trước</li>
    </ul>
    <p><h4>2. Về trang web</h4></p>
    <p>- Chúng tôi chỉ là bên cung cấp nền tảng cho các bạn sử dụng.</p>
    <p>- Các bạn phải chịu hoàn toàn trách nhiệm về nội dung trên trang web của mình nếu liên quan đến các vấn đề pháp lý</p>
</div>
<div class="rmenu" style="text-align:center;font-weight:700">Nội dung quy định này có thể thay đổi bất kỳ lúc nào mà không cần báo trước!</div>
<?php
include $system_root . '/system/layout/footer.php';
