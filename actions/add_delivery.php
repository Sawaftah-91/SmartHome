<?php
session_start();
include "../includes/functions.php";

// استلام القيم من الفورم
$customer_id      = intval($_POST['customer_id_to_delivery']);
$invoice_id       = !empty($_POST['invoice_id_to_delivery']) ? intval($_POST['invoice_id_to_delivery']) : 'NULL';
$delivery_address = mysqli_real_escape_string($conn, $_POST['delivery_address']);
$delivery_phone   = mysqli_real_escape_string($conn, $_POST['delivery_phone']);
$delivery_time    = $_POST['delivery_time'];
$delivery_status  = $_POST['delivery_status'];
$created_by       = intval($_SESSION['user_id'] ?? 0);

// تنفيذ عملية الإدخال
$sql = "INSERT INTO deliveries (customer_id, invoice_id, delivery_address, delivery_phone, delivery_time, delivery_status, created_by)
        VALUES ('$customer_id', $invoice_id, '$delivery_address', '$delivery_phone', '$delivery_time', '$delivery_status', '$created_by')";

// التحقق من نجاح العملية
if (mysqli_query($conn, $sql)) {
   $_SESSION["SUCCESS_ADD"] = 1;
} else {
 $_SESSION["ERROR_ADD"] = 1;
}
header("location: " . $_SERVER["HTTP_REFERER"]);
exit;
?>
