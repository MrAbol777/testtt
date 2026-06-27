<?php
session_start();
require_once 'db.php';

// ۱. مدیریت افزودن محصول به سبد خرید (ارسال شده از index.php)
if (isset($_POST['add_to_cart'])) {
    $product_id = (int)$_POST['product_id'];
    $quantity = (int)$_POST['quantity'];

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // اگر محصول از قبل در سبد بود، تعداد را اضافه کن، وگرنه محصول جدید بساز
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] += $quantity;
    } else {
        $_SESSION['cart'][$product_id] = $quantity;
    }
    header("Location: cart.php"); // رفرش برای جلوگیری از ارسال مجدد فرم
    exit();
}

// ۲. مدیریت حذف محصول از سبد
if (isset($_GET['remove'])) {
    $remove_id = (int)$_GET['remove'];
    unset($_SESSION['cart'][$remove_id]);
    header("Location: cart.php");
    exit();
}

// ۳. دریافت اطلاعات محصولات موجود در سبد از دیتابیس
$cart_products = [];
$total_price = 0;

if (!empty($_SESSION['cart'])) {
    $ids = implode(',', array_keys($_SESSION['cart']));
    $stmt = $pdo->query("SELECT * FROM products WHERE id IN ($ids)");
    $cart_products = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="fa">
<head>
    <meta charset="UTF-8">
    <title>سبد خرید شما</title>
    <style>
        body { font-family: Tahoma; direction: rtl; background: #f9f9f9; padding: 20px; }
        table { width: 100%; border-collapse: collapse; background: white; }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: center; }
        th { background: #f4f4f4; }
        .total { font-size: 20px; font-weight: bold; margin-top: 20px; color: green; }
        .btn-remove { color: red; text-decoration: none; font-weight: bold; }
        .back-link { display: inline-block; margin-bottom: 15px; text-decoration: none; color: blue; }
        .checkout-btn { display: inline-block; padding: 10px 20px; background: #28a745; color: white; text-decoration: none; border-radius: 5px; margin-top: 10px; }
    </style>
</head>
<body>

    <h1>سبد خرید</h1>
    <a href="index.php" class="back-link">← بازگشت به فروشگاه</a>

    <?php if (empty($cart_products)): ?>
        <p>سبد خرید شما خالی است.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>نام محصول</th>
                    <th>تعداد</th>
                    <th>قیمت واحد</th>
                    <th>قیمت کل</th>
                    <th>عملیات</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cart_products as $product): 
                    $qty = $_SESSION['cart'][$product['id']];
                    $subtotal = $product['price'] * $qty;
                    $total_price += $subtotal;
                ?>
                <tr>
                    <td><?php echo htmlspecialchars($product['name']); ?></td>
                    <td><?php echo $qty; ?></td>
                    <td><?php echo number_format($product['price']); ?> تومان</td>
                    <td><?php echo number_format($subtotal); ?> تومان</td>
                    <td>
                        <a href="cart.php?remove=<?php echo $product['id']; ?>" class="btn-remove">حذف</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="total">جمع کل پرداختی: <?php echo number_format($total_price); ?> تومان</div>
        
        <!-- دکمه نهایی کردن خرید برای فاز بعدی -->
        <a href="checkout.php" class="checkout-btn">نهایی کردن خرید</a>
    <?php endif; ?>

</body>
</html>
