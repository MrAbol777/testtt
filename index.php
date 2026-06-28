<?php
session_start();

// اتصال به دیتابیس (MySQL داخلی کانتینر)
$host = 'db';
$db   = 'online_shop';
$user = 'shop_user';
$pass = 'shop_password';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e) {
    die("خطا در اتصال به دیتابیس: " . $e->getMessage());
}

// دریافت لیست محصولات
$sql = "SELECT id, name, description, price, image_url FROM products";
$result = $pdo->query($sql);
?>

<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>فروشگاه آنلاین</title>
    <style>
        body { font-family: 'Tahoma', sans-serif; background-color: #f0f2f5; margin: 0; padding: 20px; }
        .container { max-width: 1200px; margin: auto; }
        .header { display: flex; justify-content: space-between; align-items: center; background: #fff; padding: 20px; border-radius: 12px; margin-bottom: 30px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .product-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(230px, 1fr)); gap: 25px; }
        .product-card { background: #fff; border-radius: 15px; padding: 15px; text-align: center; box-shadow: 0 4px 10px rgba(0,0,0,0.05); transition: all 0.3s ease; border: 1px solid #eee; }
        .product-card:hover { transform: translateY(-8px); box-shadow: 0 8px 15px rgba(0,0,0,0.1); }
        
        /* استایل عکس محصول */
        .product-image { 
            width: 100%; 
            height: 200px; 
            object-fit: cover; 
            border-radius: 10px; 
            margin-bottom: 15px;
            background-color: #f9f9f9;
        }

        .price { color: #2ecc71; font-weight: bold; font-size: 1.2em; margin: 10px 0; }
        .btn-add { background: #3498db; color: white; border: none; padding: 12px; border-radius: 8px; cursor: pointer; width: 100%; font-family: 'Tahoma'; font-weight: bold; }
        .btn-add:hover { background: #2980b9; }
        .cart-link { text-decoration: none; color: #fff; background: #e67e22; padding: 12px 25px; border-radius: 8px; font-weight: bold; }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <h1 style="margin: 0; color: #333;">🛒 فروشگاه مدرن</h1>
        <a href="cart.php" class="cart-link">مشاهده سبد خرید (<?php echo isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0; ?>)</a>
    </div>

    <div class="product-grid">
        <?php
        if ($result->rowCount() > 0) {
            while($row = $result->fetch()) {
                ?>
                <div class="product-card">
                    <!-- لود عکس از مسیر ذخیره شده در دیتابیس (مثلاً Images/1.jpg) -->
                    <img src="<?php echo $row['image_url']; ?>" alt="<?php echo $row['name']; ?>" class="product-image" onerror="this.src='https://via.placeholder.com/200?text=No+Image'">
                    
                    <h3 style="font-size: 1.1em; color: #2c3e50;"><?php echo $row['name']; ?></h3>
                    <p style="font-size: 0.85em; color: #7f8c8d; height: 40px; overflow: hidden;"><?php echo $row['description']; ?></p>
                    <div class="price"><?php echo number_format($row['price']); ?> تومان</div>
                    
                    <form method="post" action="cart.php">
                        <input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">
                        <div style="margin-bottom: 10px;">
                            تعداد: <input type="number" name="quantity" value="1" min="1" style="width: 50px; padding: 5px; border: 1px solid #ddd; border-radius: 4px;">
                        </div>
                        <button type="submit" name="add_to_cart" class="btn-add">افزودن به سبد</button>
                    </form>
                </div>
                <?php
            }
        } else {
            echo "<div style='grid-column: 1/-1;'><h3>هنوز هیچ محصولی ثبت نشده است.</h3></div>";
        }
        ?>
    </div>
</div>

</body>
</html>
<?php
// PDO اتصال خودکار بسته میشه
$pdo = null;
?>
