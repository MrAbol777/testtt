<?php
session_start();
require_once 'db.php';

// اگر سبد خرید خالی بود، برگرد به صفحه اصلی
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>نهایی‌سازی خرید</title>
    <style>
        body { font-family: tahoma; background-color: #f8f9fa; padding: 20px; direction: rtl; }
        .checkout-container { max-width: 800px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        h2 { border-bottom: 2px solid #eee; padding-bottom: 10px; color: #333; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        table th, table td { border: 1px solid #ddd; padding: 12px; text-align: center; }
        table th { background-color: #f2f2f2; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input[type="text"], input[type="email"] { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px; box-sizing: border-box; }
        .btn-submit { background-color: #28a745; color: white; padding: 15px 20px; border: none; border-radius: 5px; width: 100%; cursor: pointer; font-size: 18px; }
        .btn-submit:hover { background-color: #218838; }
    </style>
</head>
<body>
    <div class="checkout-container">
        <h2>خلاصه سفارش</h2>
        <table>
            <thead>
                <tr>
                    <th>محصول</th>
                    <th>تعداد</th>
                    <th>قیمت واحد</th>
                    <th>جمع کل</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $total_price = 0;
                foreach ($_SESSION['cart'] as $id => $quantity) {
                    $stmt = $pdo->prepare("SELECT name, price FROM products WHERE id = ?");
                    $stmt->execute([$id]);
                    $product = $stmt->fetch();
                    $subtotal = $product['price'] * $quantity;
                    $total_price += $subtotal;
                    echo "<tr>
                            <td>{$product['name']}</td>
                            <td>{$quantity}</td>
                            <td>" . number_format($product['price']) . " تومان</td>
                            <td>" . number_format($subtotal) . " تومان</td>
                          </tr>";
                }
                ?>
                <tr>
                    <td colspan="3" style="text-align:left; font-weight:bold;">مبلغ قابل پرداخت:</td>
                    <td style="font-weight:bold; color:red;"><?php echo number_format($total_price); ?> تومان</td>
                </tr>
            </tbody>
        </table>

        <h2>اطلاعات خریدار</h2>
        <form action="process_order.php" method="POST">
            <div class="form-group">
                <label>نام و نام خانوادگی:</label>
                <input type="text" name="full_name" required placeholder="مثال: علی علوی">
            </div>
            <div class="form-group">
                <label>ایمیل:</label>
                <input type="email" name="email" required placeholder="example@mail.com">
            </div>
            <div class="form-group">
                <label>شماره تماس:</label>
                <input type="text" name="phone" required placeholder="09123456789">
            </div>
            <button type="submit" class="btn-submit">ثبت نهایی و پرداخت</button>
        </form>
    </div>
</body>
</html>
