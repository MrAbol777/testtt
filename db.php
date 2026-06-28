<?php
$host = 'sql101.infinityfree.com';
$db   = 'if0_42285735_online_shop_demo';
$user = 'if0_42285735';
$pass = '5LK1kA2lpzOA';

try {
    // ایجاد اتصال با استفاده از کتابخانه PDO
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // نمایش خطاها
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // دریافت داده‌ها به صورت آرایه انجمنی
        PDO::ATTR_EMULATE_PREPARES   => false,                  // امنیت بیشتر در مقابل SQL Injection
    ]);
} catch (PDOException $e) {
    // در صورت بروز خطا در اتصال، برنامه متوقف شده و پیام نمایش داده می‌شود
    die("خطا در اتصال به دیتابیس: " . $e->getMessage());
}
?>
