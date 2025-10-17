<?php
// مدة عدم النشاط المسموحة (15 دقيقة = 900 ثانية)
$timeout_duration = 3600; 

// التحقق من تسجيل الدخول
if (!isset($_SESSION['in_login']) || $_SESSION['in_login'] != 1) {
    header('Location: login.php');
    exit();
}

// التحقق من آخر نشاط
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $timeout_duration) {
    // تحديث حالة المستخدم في قاعدة البيانات (اختياري)
    if (isset($_SESSION['user_id'])) {
        include "../includes/functions.php";
        $user_id = intval($_SESSION['user_id']);
        mysqli_query($conn, "UPDATE users SET is_logged_in = 0 WHERE id = $user_id");
    }

    // إنهاء الجلسة
    session_unset();
    session_destroy();
    header("Location: login.php?timeout=1");
    exit();
}

// تحديث آخر نشاط
$_SESSION['last_activity'] = time();

// تخزين بيانات المستخدم في متغير $user لاستخدامها لاحقًا
$user = $_SESSION['user'];
?>
