<?php
include "../includes/functions.php";

// استقبال رقم الزبون
$customer_id = intval($_GET['customer_id_to_delivery'] ?? 0);

if ($customer_id <= 0) {
  echo "<option value=''>❌ لم يتم استقبال رقم الزبون</option>";
  exit;
}

// استعلام الفواتير الخاصة بالزبون
$sql = "SELECT id, total FROM sales_invoices WHERE customer_id = $customer_id ORDER BY id DESC";
$query = mysqli_query($conn, $sql);

if (!$query) {
  echo "<option>⚠️ خطأ في SQL: " . mysqli_error($conn) . "</option>";
  exit;
}

if (mysqli_num_rows($query) === 0) {
  echo "<option value=''>⚠️ لا توجد فواتير لهذا الزبون</option>";
  exit;
}

while ($inv = mysqli_fetch_assoc($query)) {
  echo "<option value='{$inv['id']}'>فاتورة رقم {$inv['id']} - {$inv['total']} د.أ</option>";
}
?>
