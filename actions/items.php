<?php
include "../includes/functions.php";

// جلب القيم من النموذج
$barcode        = $_POST['barcode'];
$item_name      = $_POST['item_name'];
$item_category  = $_POST['item_category'];
$item_unit      = $_POST['item_unit'];
$item_branding  = $_POST['item_branding'];
$sales_price    = floatval($_POST['sales_price']);          // السعر المدخل
$warehouse_alert= $_POST['warehouse_alert'];      // تنبيه المخزن
$tax_rate_f     = $_POST['item_tax_rate'];                  // قيمة الضريبة (ID من جدول الضرائب)
$price_includes_tax = isset($_POST['price_includes_tax']) ? 1 : 0;
$primary_id     = $_POST['primary_id'];
$userId         = $_SESSION["user_id"];

// ❶ جلب نسبة الضريبة الفعلية من جدول item_tax_rates
$check_query = "SELECT tax_percentage FROM item_tax_rates WHERE id = '$tax_rate_f'";
$check_result = mysqli_query($conn, $check_query);
$row = mysqli_fetch_assoc($check_result);
$tax_rate = $row ? $row['tax_percentage'] : 0;








// ❷ تحويل قيمة الضريبة إلى رقم
if (is_numeric($tax_rate)) {
    $tax_percent = floatval($tax_rate);
} else {
    $tax_percent = 0; // غير خاضع أو معفى
}

// ❸ حساب السعر قبل وبعد الضريبة
if ($price_includes_tax == 1 && $tax_percent > 0) {
    $price_before_tax = $sales_price / (1 + ($tax_percent / 100));
    $price_after_tax  = $sales_price;
} elseif ($price_includes_tax == 0 && $tax_percent > 0) {
    $price_before_tax = $sales_price;
    $price_after_tax  = $sales_price * (1 + ($tax_percent / 100));
} else {
    $price_before_tax = $sales_price;
    $price_after_tax  = $sales_price;
}



// ❹ حساب قيمة الضريبة الفعلية
$tax_value = $price_after_tax - $price_before_tax;

// ❺ تنفيذ عملية الإضافة
if (isset($_POST['add'])) {
    $add = "INSERT INTO `items` (
                `barcode`, `name`, `category_id`, `unit_id`,`stock_alert`,
                `price_before_tax`, `price_includes_tax`, `tax_rate`, `tax_value`, `price_after_tax`,
                `currency_id`, `user_id`
            ) VALUES (
                '$barcode', '$item_name', '$item_category', '$item_unit','$warehouse_alert',
                '$price_before_tax', '$price_includes_tax', '$tax_rate_f', '$tax_value', '$price_after_tax',
                '1', '$userId'
            )";
    if ($add) {
        insert_query($add);
        $_SESSION['SUCCESS_ADD'] = 1;
    }



 

// ❻ تنفيذ عملية التعديل
} elseif (isset($_POST['edit'])) {
    $update = "UPDATE `items` SET
                `barcode` = '$barcode',
                `name` = '$item_name',
                `category_id` = '$item_category',
                `unit_id` = '$item_unit',
                `stock_alert` = '$warehouse_alert',
                `price_before_tax` = '$price_before_tax',
                `price_includes_tax` = '$price_includes_tax',
                `tax_rate` = '$tax_rate_f',
                `tax_value` = '$tax_value',
                `price_after_tax` = '$price_after_tax',
                `currency_id` = '1',
                `user_id` = '$userId'
            WHERE `id` = '$primary_id'";

    if ($update) {
        update_query($update);
        $_SESSION['SUCCESS_EDIT'] = 1;
    }
}

// ❼ إعادة التوجيه إلى الصفحة السابقة
header('Location: ' . $_SERVER['HTTP_REFERER']);
exit;
?>
