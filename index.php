<?php 
session_start();
include("includes/connect.php");

// إذا كان المستخدم غير مسجّل الدخول ولكن هناك كوكي remember_username
if (!isset($_SESSION['in_login']) && isset($_COOKIE['remember_username'])) {
    $username = mysqli_real_escape_string($conn, $_COOKIE['remember_username']);
    $result = mysqli_query($conn, "SELECT * FROM users WHERE username = '$username'");

    if (mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);
        $_SESSION['user'] = $user;
        $_SESSION['in_login'] = 1;

        // تحديث last_activity في قاعدة البيانات
        $now = date('Y-m-d H:i:s');
        mysqli_query($conn, "UPDATE users SET last_activity = '$now' WHERE username = '$username'");
    }
}

// الآن تحقق من الجلسة
if (isset($_SESSION['in_login']) && $_SESSION['in_login'] == 1) {
    header('Location: home');
    exit();
} else {
    header('Location: login');
    exit();
}
?>
