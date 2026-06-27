<?php
session_start();
if (!isset($_SESSION['admin'])) { header('Location: login.php'); exit; }
require 'db.php';

if (isset($_GET['id'])) {
    $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
    $stmt->execute([$_GET['id']]);
}
header('Location: products.php');
exit;
