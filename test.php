<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/vendor/autoload.php';
include_once(__DIR__ . '/includes/functions.php');

$invoice_id = isset($_GET['id']) ? (int)$_GET['id'] : 8;
$invoice_info = get_sales_invoices($invoice_id);
$items = get_sales_invoice_items($invoice_id);

if (!$invoice_info) {
    exit('❌ لم يتم العثور على الفاتورة');
}

echo "<pre>اختبار PDF...</pre>";
flush();

try {
    $mpdf = new \mPDF('utf-8', 'A4', '', '', 10, 10, 10, 10);
    $mpdf->SetDirectionality('rtl');
    $mpdf->WriteHTML("
        <h3 style='text-align:center;color:#007bff;'>فاتورة مبيعات تجريبية</h3>
        <p><strong>رقم الفاتورة:</strong> {$invoice_info->invoice_number}</p>
    ");
    $mpdf->Output('test.pdf', 'I');
} catch (Throwable $e) {
    echo '<pre>❌ خطأ أثناء إنشاء PDF: ' . htmlspecialchars($e->getMessage()) . '</pre>';
}
exit;
