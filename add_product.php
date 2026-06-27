<?php
session_start();
if (!isset($_SESSION['admin'])) { header('Location: login.php'); exit; }
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $stmt = $pdo->prepare("INSERT INTO products (name, price, stock, description) VALUES (?, ?, ?, ?)");
    $stmt->execute([$_POST['name'], $_POST['price'], $_POST['stock'], $_POST['desc']]);
    header('Location: products.php'); exit;
}
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head><meta charset="UTF-8"><title>افزودن</title></head>
<body style="font-family:tahoma; padding:20px;">
    <h3>افزودن محصول جدید</h3>
    <form method="post">
        <input type="text" name="name" placeholder="نام" required><br><br>
        <input type="number" name="price" placeholder="قیمت" required><br><br>
        <input type="number" name="stock" placeholder="موجودی" required><br><br>
        <textarea name="desc" placeholder="توضیحات"></textarea><br><br>
        <button type="submit">ذخیره</button>
    </form>
</body>
</html>
