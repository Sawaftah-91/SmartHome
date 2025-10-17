<?php
include "../includes/functions.php";
$user_name_ar = mb_convert_case(
    str_replace("'", "\'", $_POST["user_name_ar"]),
    MB_CASE_TITLE
);
$user_email = $_POST["user_email"];
$user_phone = $_POST["user_phone"];
$user_username = $_POST["user_username"];
$password = crypt($_POST["user_password"], SYSTEM_NAME);
$user_role = $_POST["user_role"];
$primary_id = $_POST["primary_id"];
$userId = $_SESSION["user_id"];

if (isset($_POST["add"])) {
    // أولاً نتحقق إذا كان الايميل أو الهاتف موجودين
    $check_query = "SELECT * FROM users WHERE email = '$user_email' OR phone = '$user_phone'";
    $result = mysqli_query($conn, $check_query);

    if (mysqli_num_rows($result) > 0) {
        // البريد أو الهاتف موجود مسبقاً
        $_SESSION["VALUE_EXISTS"] = 1;
    } else {
        // لم يتم العثور على البريد أو الهاتف، نكمل الإدراج
        $add = "INSERT INTO `users`(`name_ar`, `email`, `phone`, `username`, `password`, `role_id`) 
                VALUES ('$user_name_ar','$user_email','$user_phone','$user_username','$password','$user_role')";

        if ($add) {
            insert_query($add);
            $_SESSION["SUCCESS_ADD"] = 1;
        } else {
            $_SESSION["ERROR_ADD"] = 1;
        }
    }
} elseif (isset($_POST["edit"])) {
    if (!empty($password)) {
        $update = "UPDATE `users` 
                   SET 
                       `name_ar` = '$user_name_ar',
                       `email` = '$user_email',
                       `phone` = '$user_phone',
                       `username` = '$user_username',
                       `password` = '$password',
                       `role_id` = '$user_role'
                   WHERE `id` = '$primary_id'";
    } else {
        // عدم تحديث كلمة المرور
        $update = "UPDATE `users` 
                   SET 
                       `name_ar` = '$user_name_ar',
                       `email` = '$user_email',
                       `phone` = '$user_phone',
                       `username` = '$user_username',
                       `role_id` = '$user_role'
                   WHERE `id` = '$primary_id'";
    }

    if (update_query($update)) {
        $_SESSION["SUCCESS_EDIT"] = 1;
    } else {
        $_SESSION["ERROR_ADD"] = 1;
    }
}
header("location: " . $_SERVER["HTTP_REFERER"]);
exit();
?>
