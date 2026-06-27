<?php
session_start();
if (!isset($_SESSION['admin'])) { header('Location: login.php'); exit; }
require 'db.php';

$id = $_GET['id'];
$p = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$p->execute([$id]);
$product = $p->fetch();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $stmt = $pdo->prepare("UPDATE products SET name=?, price=?, stock=?, description=? WHERE id=?");
    $stmt->execute([$_POST['name'], $_POST['price'], $_POST['stock'], $_POST['desc'], $id]);
    header('Location: products.php'); exit;
}
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head><meta charset="UTF-8"><title>ویرایش</title></head>
<body style="font-family:tahoma; padding:20px;">
    <h3>ویرایش محصول</h3>
    <form method="post">
        <input type="text" name="name" value="<?= $product['name'] ?>" required><br><br>
        <input type="number" name="price" value="<?= $product['price'] ?>" required><br><br>
        <input type="number" name="stock" value="<?= $product['stock'] ?>" required><br><br>
        <textarea name="desc"><?= $product['description'] ?></textarea><br><br>
        <button type="submit">بروزرسانی</button>
    </form>
</body>
</html>
