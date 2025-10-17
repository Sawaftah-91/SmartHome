<?php
// تحميل autoload و الدوال
require_once __DIR__ . '/vendor/autoload.php';
include_once(__DIR__ . '/includes/functions.php');

// =================== استقبال وإعداد البيانات ===================
$receipt_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($receipt_id <= 0) {
    exit('<div style="text-align:center; margin-top:50px;">⚠️ رقم السند غير صالح</div>');
}

// جلب بيانات السند
$receipt_info = get_receipt_info($receipt_id);
if (!$receipt_info) {
    exit('<div style="text-align:center; margin-top:50px;">⚠️ لم يتم العثور على السند المطلوب</div>');
}

// جلب الفواتير المرتبطة بالسند
$invoices = get_receipt_invoices($receipt_id);

// جلب بيانات المُستخدم الذي أنشأ السند
$user_data = get_users((int)$receipt_info->created_by);
$user_info = !empty($user_data) ? $user_data[0] : null;

// معلومات الشركة (ثوابت أو بدائل)
$companyLogo    = defined('COMPANY_LOGO') ? COMPANY_LOGO : '';
$companyName    = defined('COMPANY_NAME') ? COMPANY_NAME : '';
$companyAddress = defined('COMPANY_ADDRESS') ? COMPANY_ADDRESS : '';

// مسار لوجو محلي إن وُجد (مهم لـ mPDF في بعض الاستضافات)
$logo_html = '';
if (!empty($companyLogo)) {
    // إذا المسار نسبي داخل المشروع: حاول بناء مسار مطلق
    if (file_exists(__DIR__ . '/' . $companyLogo)) {
        $logo_path = realpath(__DIR__ . '/' . $companyLogo);
        // mPDF v6 يدعم تحميل الصور المحلية عبر المسار الكامل
        $logo_html = '<img src="' . $logo_path . '" alt="logo" style="max-height:80px;">';
    } elseif (file_exists($companyLogo)) {
        // مسار مطلق أو بالفعل موجود
        $logo_html = '<img src="' . $companyLogo . '" alt="logo" style="max-height:80px;">';
    } else {
        // إذا كان رابط خارجي
        $logo_html = '<img src="' . htmlspecialchars($companyLogo) . '" alt="logo" style="max-height:80px;">';
    }
}

// خريطة طرق الدفع (لإظهار نص قابل للقراءة)
$methods = [
  'cash'   => 'نقداً',
  'cheque' => 'شيك',
  'click'  => 'كليك',
  'visa'   => 'فيزا'
];

// =================== إعداد mPDF (v6) ===================
try {
    // مُنشئ mPDF للإصدار 6
    $mpdf = new \mPDF('utf-8', 'A4', '', '', 10, 10, 10, 10);
    $mpdf->SetDirectionality('rtl');
} catch (Exception $e) {
    exit('<div style="text-align:center; margin-top:50px;">خطأ في تهيئة mPDF: ' . htmlspecialchars($e->getMessage()) . '</div>');
}

// =================== بناء HTML للسند ===================
$created_at = date('Y-m-d', strtotime($receipt_info->created_at));
$payment_method_label = $methods[$receipt_info->payment_method] ?? $receipt_info->payment_method;

$html = '
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="utf-8" />
<style>
  body { font-family: dejavusans, sans-serif; direction: rtl; text-align: right; font-size:14px; color:#222; }
  .container { max-width: 900px; margin: 0 auto; padding: 10px 15px; }
  .header { display:flex; justify-content:space-between; align-items:center; margin-bottom:10px; }
  .company { text-align:center; }
  .company h2 { margin:0; color:#0d6efd; }
  .company p { margin:0; font-size:12px; color:#555; }
  .info { margin-top:10px; }
  .info-table { width:100%; border-collapse: collapse; margin-bottom:10px; }
  .info-table td { padding:6px 4px; border: none; vertical-align: top; }
  .invoices-table { width:100%; border-collapse: collapse; margin-top:10px; }
  .invoices-table th, .invoices-table td { border:1px solid #ddd; padding:8px; text-align:center; }
  .invoices-table th { background:#0d6efd; color:#fff; }
  .totals { width:100%; margin-top:12px; }
  .totals td { padding:8px; border: none; text-align:right; }
  .note-box { margin-top:18px; border:1px solid #eee; padding:10px; background:#fafafa; font-size:13px; }
  .footer { margin-top:24px; font-size:12px; text-align:center; color:#666; }
</style>
</head>
<body>
  <div class="container">
    <div class="header">
      <div class="logo-left">' . $logo_html . '</div>
      <div class="company">
        <h2>' . htmlspecialchars($companyName) . '</h2>
        <p>' . nl2br(htmlspecialchars($companyAddress)) . '</p>
      </div>
      <div style="width:120px; text-align:left;">
        <strong>سند قبض</strong><br>
        <small>رقم: ' . (int)$receipt_info->id . '</small>
      </div>
    </div>

    <table class="info-table">
      <tr>
        <td style="width:50%;">
          <strong>الزبون:</strong> ' . htmlspecialchars($receipt_info->customer_name ?? '---') . '<br>
          ' . (!empty($receipt_info->phone_number) ? '<strong>هاتف:</strong> ' . htmlspecialchars($receipt_info->phone_number) . '<br>' : '') . '
        </td>
        <td style="width:50%; text-align:left;">
          <strong>التاريخ:</strong> ' . htmlspecialchars($created_at) . '<br>
          <strong>طريقة الدفع:</strong> ' . htmlspecialchars($payment_method_label) . '<br>
          <strong>أنشئ بواسطة:</strong> ' . htmlspecialchars($user_info->name ?? '---') . '
        </td>
      </tr>
    </table>

    <table class="invoices-table">
      <thead>
        <tr>
          <th>رقم الفاتورة</th>
          <th>قيمة الفاتورة (د.أ)</th>
          <th>المبلغ المدفوع (د.أ)</th>
          <th>المتبقي (د.أ)</th>
        </tr>
      </thead>
      <tbody>';

if (!empty($invoices)) {
    foreach ($invoices as $inv) {
        // بعض الحقول قد تكون properties أو مفاتيح مصفوفية اعتمادًا على دالتك
        $inv_num = isset($inv->invoice_number) ? $inv->invoice_number : (isset($inv['invoice_number']) ? $inv['invoice_number'] : '');
        $inv_total = (float)(isset($inv->total) ? $inv->total : (isset($inv['total']) ? $inv['total'] : 0));
        $paid = (float)(isset($inv->paid_amount) ? $inv->paid_amount : (isset($inv['paid_amount']) ? $inv['paid_amount'] : 0));
        $remaining = (float)(isset($inv->remaining_amount) ? $inv->remaining_amount : (isset($inv['remaining_amount']) ? $inv['remaining_amount'] : ($inv_total - $paid)));
        $html .= '
        <tr>
          <td>' . htmlspecialchars($inv_num) . '</td>
          <td>' . number_format($inv_total, 2) . '</td>
          <td>' . number_format($paid, 2) . '</td>
          <td>' . number_format($remaining, 2) . '</td>
        </tr>';
    }
} else {
    $html .= '
        <tr>
          <td colspan="4">لا توجد فواتير مرتبطة بهذا السند</td>
        </tr>';
}

$html .= '
      </tbody>
    </table>

    <table class="totals">
      <tr>
        <td style="text-align:right;"><strong>إجمالي المقبوض:</strong> ' . number_format((float)$receipt_info->total_amount, 2) . ' د.أ</td>
      </tr>
    </table>

    <div class="note-box">
      <strong>ملاحظات:</strong><br>
      ' . (!empty($receipt_info->note) ? nl2br(htmlspecialchars($receipt_info->note)) : 'لا يوجد ملاحظات') . '
    </div>

    <div class="footer">
      <p>تم إنشاء هذا السند إلكترونيًا بواسطة نظامك - ' . date('Y') . '.</p>
    </div>
  </div>
</body>
</html>
';

// =================== توليد PDF وعرضه ===================
$mpdf->WriteHTML($html);
$filename = 'receipt_' . (int)$receipt_info->id . '.pdf';

// 'I' = إظهار داخل المتصفح، 'D' = تنزيل، 'F' = حفظ على السيرفر
$mpdf->Output($filename, 'I');
exit;
