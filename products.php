<?php
session_start();
if (!isset($_SESSION['admin'])) { header('Location: login.php'); exit; }
require 'db.php';

$products = $pdo->query("SELECT * FROM products ORDER BY id DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head><meta charset="UTF-8"><title>مدیریت محصولات</title></head>
<body style="font-family:tahoma; padding:20px;">
    <h2>پنل مدیریت کالاها</h2>
    <a href="add_product.php">[+] افزودن کالا</a> | <a href="logout.php">خروج</a>
    <table border="1" width="100%" style="margin-top:20px; border-collapse:collapse; text-align:center;">
        <tr><th>ID</th><th>نام</th><th>قیمت</th><th>موجودی</th><th>عملیات</th></tr>
        <?php foreach($products as $p): ?>
        <tr>
            <td><?= $p['id'] ?></td>
            <td><?= $p['name'] ?></td>
            <td><?= number_format((float)$p['price']) ?></td>
            <td><?= $p['stock'] ?></td>
            <td>
                <a href="edit_product.php?id=<?= $p['id'] ?>">ویرایش</a> | 
                <a href="delete_product.php?id=<?= $p['id'] ?>" onclick="return confirm('حذف شود؟')">حذف</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
