<?php
session_start();
require_once 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_SESSION['cart'])) {
    $name = $_POST['full_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $total_amount = 0;

    try {
        $pdo->beginTransaction();

        // ۱. مدیریت مشتری (طبق خواسته عکس استاد)
        // بررسی می‌کنیم آیا مشتری با این ایمیل از قبل وجود دارد؟
        $stmt = $pdo->prepare("SELECT id FROM customers WHERE email = ?");
        $stmt->execute([$email]);
        $customer = $stmt->fetch();

        if ($customer) {
            $customer_id = $customer['id'];
        } else {
            // اگر نبود، مشتری جدید می‌سازیم
            $stmt = $pdo->prepare("INSERT INTO customers (name, email, phone) VALUES (?, ?, ?)");
            $stmt->execute([$name, $email, $phone]);
            $customer_id = $pdo->lastInsertId();
        }

        // ۲. محاسبه مبلغ کل
        foreach ($_SESSION['cart'] as $id => $qty) {
            $stmt = $pdo->prepare("SELECT price FROM products WHERE id = ?");
            $stmt->execute([$id]);
            $total_amount += $stmt->fetch()['price'] * $qty;
        }

        // ۳. ثبت سفارش در جدول orders (با استفاده از customer_id)
        $sql_order = "INSERT INTO orders (customer_id, customer_name, customer_phone, customer_email, total_amount, status, order_date) 
                      VALUES (?, ?, ?, ?, ?, 'Pending', NOW())";
        $stmt = $pdo->prepare($sql_order);
        $stmt->execute([$customer_id, $name, $phone, $email, $total_amount]);
        $order_id = $pdo->lastInsertId();

        // ۴. ثبت در order_items و کاهش موجودی (Stock)
        foreach ($_SESSION['cart'] as $id => $qty) {
            $stmt = $pdo->prepare("SELECT price FROM products WHERE id = ?");
            $stmt->execute([$id]);
            $price = $stmt->fetch()['price'];

            $stmt = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, price_at_purchase) VALUES (?, ?, ?, ?)");
            $stmt->execute([$order_id, $id, $qty, $price]);

            $stmt = $pdo->prepare("UPDATE products SET stock = stock - ? WHERE id = ?");
            $stmt->execute([$qty, $id]);
        }

        $pdo->commit();
        unset($_SESSION['cart']);
        header("Location: thank_you.php");
        exit();

    } catch (Exception $e) {
        $pdo->rollBack();
        die("خطا: " . $e->getMessage());
    }
}
