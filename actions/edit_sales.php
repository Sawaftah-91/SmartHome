<?php
session_start();
include "../includes/functions.php";

// -------------------------------------------------------------
// 🟩 1. استقبال بيانات الفاتورة
// -------------------------------------------------------------
$invoice_id = $_POST['invoice_id'];
$customerName = $_POST['customerName'];
$invoiceDate = $_POST['invoiceDate'];
$isPaid = $_POST['isPaid']; // 1 نقداً - 0 ذمم - 2 شيكات - 3 كليك - 4 فيزا
$userId = $_SESSION["user_id"];
$quantities = $_POST['totalQuantity'];
$totalWithoutTax = $_POST['totalPriceWithoutTax'];
$totalOnePriceWithTax = $_POST['totalOnePriceWithTax'];
$totalWithTax = $_POST['totalWithTax'];
$discount = $_POST['totalDiscount'];

// 🧾 بيانات الدفع
$chequeNumber = $_POST['cheque_number'] ?? null;
$chequeDate   = $_POST['cheque_date'] ?? null;
$chequeValue  = $_POST['cheque_value'] ?? null;
$chequeBank   = $_POST['cheque_bank'] ?? null;
$clickRef     = $_POST['click_ref'] ?? null;
$visaRef      = $_POST['visa_ref'] ?? null;

// -------------------------------------------------------------
// 🟩 2. جلب بيانات الفاتورة القديمة لمعرفة نوع الدفع السابق
// -------------------------------------------------------------
$old_invoice = get_record("SELECT status FROM sales_invoices WHERE id='$invoice_id'");
$old_status = $old_invoice ? $old_invoice['status'] : null;

// -------------------------------------------------------------
// 🟩 3. جلب الأصناف القديمة من قاعدة البيانات
// -------------------------------------------------------------
$old_items = get_invoice_items($invoice_id);
$old_items_map = [];
foreach ($old_items as $oi) {
    $old_items_map[strval($oi['item_id'])] = floatval($oi['quantity']);
}

// -------------------------------------------------------------
// 🟩 4. تحديد الأصناف الجديدة من الفورم
// -------------------------------------------------------------
$new_item_ids = $_POST['name'] ?? [];

// -------------------------------------------------------------
// 🟩 5. حذف الأصناف التي تم إزالتها من الفورم
// -------------------------------------------------------------
$deleted_items = array_diff(array_keys($old_items_map), $new_item_ids);
foreach ($deleted_items as $item_id) {
    $quantity_old = $old_items_map[$item_id];
    if ($quantity_old > 0) {
        update_query("UPDATE warehouse SET quantity = quantity + $quantity_old WHERE item_id='$item_id'");
    }
    delete_query("DELETE FROM sales_invoice_items WHERE invoice_id='$invoice_id' AND item_id='$item_id'");
    unset($old_items_map[$item_id]);
}

// -------------------------------------------------------------
// 🟩 6. معالجة الأصناف الجديدة أو المعدلة
// -------------------------------------------------------------
foreach ($new_item_ids as $i => $item_id) {
    $item_id = strval($item_id);
    $quantity_new = floatval($_POST['quantity'][$i]);
    $quantity_old = isset($old_items_map[$item_id]) ? $old_items_map[$item_id] : 0;

    $diff = $quantity_new - $quantity_old;
    if ($diff > 0) {
        update_query("UPDATE warehouse SET quantity = quantity - $diff WHERE item_id='$item_id'");
    } elseif ($diff < 0) {
        update_query("UPDATE warehouse SET quantity = quantity + " . abs($diff) . " WHERE item_id='$item_id'");
    }

    delete_query("DELETE FROM sales_invoice_items WHERE invoice_id='$invoice_id' AND item_id='$item_id'");
    insert_query("
        INSERT INTO sales_invoice_items
        (invoice_id, item_id, price_without_tax, quantity, tax, tax_per_unit, price_with_tax, total_without_tax, total_with_tax)
        VALUES
        ('$invoice_id','$item_id','{$_POST['price'][$i]}','$quantity_new','{$_POST['tax'][$i]}','{$_POST['tax_one'][$i]}',
        '{$_POST['one_price_with_tax'][$i]}','{$_POST['total_without_tax'][$i]}','{$_POST['total_price'][$i]}')
    ");
}

// -------------------------------------------------------------
// 🟩 7. تحديث بيانات الفاتورة الرئيسية
// -------------------------------------------------------------
update_query("
    UPDATE sales_invoices
    SET customer_id='$customerName', user_id='$userId', invoice_date='$invoiceDate',
        status='$isPaid', discount='$discount', quantity='$quantities',
        subtotal='$totalWithoutTax', tax_total='$totalOnePriceWithTax', total='$totalWithTax'
    WHERE id='$invoice_id'
");

// -------------------------------------------------------------
// 🟩 8. تحديث حساب العميل
// -------------------------------------------------------------
$check_acc = get_record("SELECT id FROM customers_accounts WHERE sale_invoice_id = '$invoice_id'");
if ($check_acc) {
    update_query("UPDATE customers_accounts SET customer_id='$customerName', credit='$totalWithTax' WHERE sale_invoice_id='$invoice_id'");
} else {
    insert_query("INSERT INTO customers_accounts (customer_id, sale_invoice_id, credit) VALUES ('$customerName','$invoice_id','$totalWithTax')");
}

// -------------------------------------------------------------
// 🟩 9. تحديث التدفق النقدي
// -------------------------------------------------------------
$check_cash = get_record("SELECT id FROM cash_flow WHERE sale_invoice_id='$invoice_id'");
if ($check_cash) {
    update_query("
        UPDATE cash_flow
        SET customer_id='$customerName', seller_id='$userId', isPaid='$isPaid', amount='$totalWithTax',
            cheque_number='$chequeNumber', cheque_date='$chequeDate', click_ref='$clickRef', visa_ref='$visaRef'
        WHERE sale_invoice_id='$invoice_id'
    ");
} else {
    insert_query("
        INSERT INTO cash_flow
        (customer_id, seller_id, isPaid, amount, cheque_number, cheque_date, click_ref, visa_ref, sale_invoice_id)
        VALUES ('$customerName','$userId','$isPaid','$totalWithTax','$chequeNumber','$chequeDate','$clickRef','$visaRef','$invoice_id')
    ");
}

// -------------------------------------------------------------
// 🟩 10. إدارة جدول الشيكات (cheques)
// -------------------------------------------------------------
if ($old_status == 2 && $isPaid != 2) {
    // ✅ الحالة 1: كانت شيكات وتم تغييرها → حذف السجل
    delete_query("DELETE FROM cheques WHERE sales_invoice_id='$invoice_id'");
}
elseif ($old_status != 2 && $isPaid == 2) {
    // ✅ الحالة 2: لم تكن شيكات وأصبحت شيكات → إضافة سجل جديد
    insert_query("
        INSERT INTO cheques (invoice_type, sales_invoice_id, cheque_number, cheque_value, cheque_date, cheque_bank)
        VALUES ('sales', '$invoice_id', '$chequeNumber', '$totalWithTax', '$chequeDate', '$chequeBank')
    ");
}
elseif ($old_status == 2 && $isPaid == 2) {
    // ✅ الحالة 3: بقيت شيكات → تحديث السجل
    update_query("
        UPDATE cheques
        SET cheque_number='$chequeNumber', cheque_value='$totalWithTax', cheque_date='$chequeDate', cheque_bank='$chequeBank'
        WHERE sales_invoice_id='$invoice_id'
    ");
}

// -------------------------------------------------------------
// 🟩 11. إنهاء العملية
// -------------------------------------------------------------
$_SESSION['SUCCESS_EDIT'] = 1;
header("location: " . $_SERVER["HTTP_REFERER"]);
exit;
?>
