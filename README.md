# dorew-site-builder
DorewSite Builder old version

**Hướng dẫn**
- Yêu cầu: PHP >=7.1.3, extension iconv()
- Giải nén file .sql.zip, upload file .sql lên phpadmin
- Upload file zip lên host, giải nén.
- Cấu hình: 
1. **/system/config/system.config.php**
```PHP
$config_builder = [
    'project_name' => 'Nosine',
    'host' => 'YOUR_MYSQL_SERVER',
    'user' => 'YOUR_DB_USERNAME',
    'pass' => 'YOUR_DB_PASSWORD',
    'name' => 'YOUR_DB_NAME',
    'charset' => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
    'max_size' => 80 * 1024 * 1024 // 80MB - dung lượng tối đa của hệ thống
];
```
2. **/system/config/system.func.php**
```PHP
function sendMail
```