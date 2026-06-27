<?php
session_start();
if (isset($_POST['login'])) {
    if ($_POST['user'] == 'admin' && $_POST['pass'] == '1234') {
        $_SESSION['admin'] = true;
        header('Location: products.php');
        exit;
    } else {
        $error = "نام کاربری یا رمز اشتباه است";
    }
}
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head><meta charset="UTF-8"><title>ورود ادمین</title></head>
<body style="font-family:tahoma; text-align:center; padding-top:50px;">
    <form method="post" style="display:inline-block; border:1px solid #ccc; padding:20px;">
        <h3>ورود به پنل مدیریت</h3>
        <?php if(isset($error)) echo "<p style='color:red'>$error</p>"; ?>
        <input type="text" name="user" placeholder="نام کاربری" required><br><br>
        <input type="password" name="pass" placeholder="رمز عبور" required><br><br>
        <button type="submit" name="login">ورود</button>
    </form>
</body>
</html>
