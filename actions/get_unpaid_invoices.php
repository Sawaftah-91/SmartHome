<?php
// actions/get_unpaid_invoices.php
header('Content-Type: application/json; charset=utf-8');
require_once "../includes/functions.php";

if (!isset($_GET['customer_id']) || !is_numeric($_GET['customer_id'])) {
    echo json_encode(['success' => false, 'data' => [], 'msg' => 'customer_id required']);
    exit;
}

$customer_id = (int) $_GET['customer_id'];

/*
  نجلب فقط الفواتير غير المسددة أو الجزئية لهذا الزبون،
  ونحسب المتبقي = total - مجموع المدفوع من receipt_voucher_invoices
*/
$query = "
  SELECT 
    si.id,
    si.invoice_number,
    si.invoice_date,
    si.total,
    si.payment_status,
    COALESCE(SUM(rvi.paid_amount), 0) AS paid_sum,
    (si.total - COALESCE(SUM(rvi.paid_amount), 0)) AS due_amount
  FROM sales_invoices si
  LEFT JOIN receipt_voucher_invoices rvi ON rvi.invoice_id = si.id
  WHERE si.customer_id = $customer_id
    AND si.payment_status IN ('unpaid','partial')
  GROUP BY si.id, si.invoice_number, si.invoice_date, si.total, si.payment_status
  HAVING due_amount > 0
  ORDER BY si.invoice_date DESC, si.id DESC
";

$result = mysqli_query($conn, $query);
if (!$result) {
    echo json_encode(['success' => false, 'data' => [], 'msg' => mysqli_error($conn)]);
    exit;
}

$data = [];
while ($row = mysqli_fetch_assoc($result)) {
    // إعداد نص لطيف للعرض داخل الـ <option>
    $statusLabel = $row['payment_status'] === 'partial' ? 'دفعة جزئية' : 'غير مسددة';
    $text = sprintf(
        '#%s | %s | متبقي: %0.2f',
        $row['invoice_number'],
        $row['invoice_date'],
        (float)$row['due_amount']
    );

    $data[] = [
        'id'          => (int)$row['id'],
        'text'        => $text,
        'due_amount'  => (float)$row['due_amount'],
        'status'      => $statusLabel
    ];
}

echo json_encode(['success' => true, 'data' => $data]);
