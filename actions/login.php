<?php
session_start();
include "../includes/functions.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password']; // لن نعمل التشفير قبل التحقق من الحساب

    // جلب المستخدم بالاسم فقط
    $query = "SELECT * FROM users WHERE username='$username'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);

        // ✅ التحقق من حالة المستخدم قبل أي شيء
        if ($user['status'] == 0) {
            $_SESSION['INACTIVE_ACCOUNT'] = 1;
            header("Location: ../login");
            exit;
        }

        // التحقق من كلمة المرور بعد التأكد أن الحساب مفعل
        if (crypt($password, SYSTEM_NAME) === $user['password']) {
            // تسجيل الدخول وتخزين بيانات الجلسة
            $_SESSION['user'] = $user;
            $_SESSION['in_login'] = 1;
            $_SESSION['role_id'] = $user['role_id'];
            $_SESSION['user_id'] = $user['id'];
            
            // تحميل الصلاحيات
            $_SESSION['permissions'] = loadPermissions($conn, $user['role_id']);
            
            // ✅ تفعيل الكوكي في حال تم اختيار "Remember me"
            if (isset($_POST['remember'])) {
                setcookie("remember_username", $user['username'], time() + (86400 * 30), "/"); // 30 يوم
            }

            // تحديث حالة الدخول ووقت النشاط الأخير
            $now = date('Y-m-d H:i:s');
            mysqli_query($conn, "UPDATE users SET is_logged_in = 1, last_activity = '$now' WHERE username = '$username'");

            header("Location:../index");
            exit;
        } else {
            $_SESSION['WRONG_PASSWORD'] = 1;
            header("Location: ../login");
            exit;
        }
    } else {
        $_SESSION['WRONG_PASSWORD'] = 1;
        header("Location: ../login");
        exit;
    }
}
