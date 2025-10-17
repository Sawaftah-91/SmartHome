<?php
// =============================================================
// 🟩 1. التهيئة العامة
// =============================================================
ob_start();
session_start();
include('includes/navbar.php');
show_messages(); // عرض رسائل النظام

// =============================================================
// 🟩 2. فلترة حسب التاريخ
// =============================================================
$where = "";
$filterText = "";

if (!empty($_GET['from']) && !empty($_GET['to'])) {
  $from = $_GET['from'];
  $to   = $_GET['to'];
  $where = "WHERE si.invoice_date BETWEEN '$from' AND '$to'";
  $filterText = "من $from إلى $to";
} elseif (!empty($_GET['from'])) {
  $from = $_GET['from'];
  $where = "WHERE si.invoice_date >= '$from'";
  $filterText = "من تاريخ $from";
} elseif (!empty($_GET['to'])) {
  $to = $_GET['to'];
  $where = "WHERE si.invoice_date <= '$to'";
  $filterText = "حتى تاريخ $to";
}

// =============================================================
// 🟩 3. جلب بيانات الفواتير
// =============================================================
global $conn;
$query = "
  SELECT 
    si.*, 
    c.customer_name, 
    c.phone_number
  FROM sales_invoices si
  LEFT JOIN customers c ON si.customer_id = c.id
  $where
  ORDER BY si.created_at DESC
";
$result = mysqli_query($conn, $query);
$rows = [];
while ($row = mysqli_fetch_object($result)) $rows[] = $row;

// =============================================================
// 🟩 4. حساب الإحصائيات الذكية
// =============================================================
$totalPaidFull = 0;
$countPaidFull = 0;

$totalUnpaidPartial = 0;
$countUnpaidPartial = 0;

foreach ($rows as $r) {
  if ($r->payment_status === 'paid') {
    $countPaidFull++;
    $totalPaidFull += floatval($r->paid_amount);
  } else {
    $countUnpaidPartial++;
    $totalUnpaidPartial += floatval($r->remaining_amount);
  }
}
?>

<!-- =============================================================
🟦 واجهة الصفحة
============================================================= -->
<div class="row g-2 mb-2">
  <div class="card mb-1 shadow-sm border-0">
    <div class="bg-holder d-none d-lg-block bg-card" 
         style="background-image:url(assets/img/icons/spot-illustrations/corner-4.png);"></div>

    <div class="card-body position-relative">

      <!-- =============================================================
      🟩 شريط علوي (فلترة + زر إضافة)
      ============================================================= -->
      <form method="GET" class="row g-2 align-items-end mb-4">
        <div class="col-md-2">
          <label class="form-label fw-bold mb-0 small">من</label>
          <input type="date" name="from" class="form-control form-control-sm" value="<?= $_GET['from'] ?? '' ?>">
        </div>

        <div class="col-md-2">
          <label class="form-label fw-bold mb-0 small">إلى</label>
          <input type="date" name="to" class="form-control form-control-sm" value="<?= $_GET['to'] ?? '' ?>">
        </div>

        <div class="col-md-2">
          <button type="submit" class="btn btn-primary btn-sm w-100">
            <i class="fas fa-filter me-1"></i> تصفية
          </button>
        </div>

        <div class="col-md-2">
          <a href="sales.php" class="btn btn-secondary btn-sm w-100">
            <i class="fas fa-undo me-1"></i> إعادة تعيين
          </a>
        </div>

        <div class="col-md-4 text-md-end mt-2 mt-md-0">
          <a href="add_sales.php" class="btn btn-success btn-sm">
            <i class="fas fa-plus me-1"></i> إضافة فاتورة جديدة
          </a>
        </div>
      </form>

      <!-- =============================================================
      🟩 قسم الإحصائيات بتصميم لطيف داخل نفس الكارد
      ============================================================= -->
      <div class="p-3 mb-4 rounded" style="background-color:#f9fafb; border:1px solid #e6e6e6;">
        <div class="row text-center gy-3 align-items-center">
          <!-- 🟢 الفواتير المدفوعة بالكامل -->
          <div class="col-md-6 border-end">
            <div class="fw-bold text-success mb-1">
              <i class="fas fa-check-circle me-1"></i> الفواتير المدفوعة بالكامل
            </div>
            <div class="small text-muted">عدد الفواتير: 
              <span class="fw-bold text-dark"><?= $countPaidFull; ?></span>
            </div>
            <div class="small text-muted">إجمالي المدفوع: 
              <span class="fw-bold text-dark"><?= number_format($totalPaidFull, 3); ?> د.أ</span>
            </div>
          </div>

          <!-- 🔴 فواتير الذمم والمدفوعة جزئياً -->
          <div class="col-md-6">
            <div class="fw-bold text-warning mb-1">
              <i class="fas fa-exclamation-circle me-1"></i> فواتير الذمم والمدفوعة جزئياً
            </div>
            <div class="small text-muted">عدد الفواتير: 
              <span class="fw-bold text-dark"><?= $countUnpaidPartial; ?></span>
            </div>
            <div class="small text-muted">إجمالي المتبقي: 
              <span class="fw-bold text-dark"><?= number_format($totalUnpaidPartial, 3); ?> د.أ</span>
            </div>
          </div>
        </div>
      </div>

      <!-- =============================================================
      🟩 جدول الفواتير
      ============================================================= -->
      <div class="table-responsive scrollbar">
        <table class="table table-sm table-hover datatable align-middle text-center">
          <thead class="table-light">
            <tr>
              <th>#</th>
              <th>التاريخ</th>
              <th>رقم الفاتورة</th>
              <th>العميل</th>
              <th>طريقة الدفع</th>
              <th>حالة الدفع</th>
              <th>الإجمالي</th>
              <th>المدفوع</th>
              <th>المتبقي</th>
              <th>الكمية</th>
              <th>الخصم</th>
              <th>إجراء</th>
            </tr>
          </thead>
          <tbody>
            <?php 
            $i = 1;
            foreach ($rows as $value):

              // 🟩 طريقة الدفع
              switch ($value->status) {
                case 0: $paymentType = 'ذمم (آجل)'; break;
                case 1: $paymentType = 'نقداً'; break;
                case 2: $paymentType = 'شيك'; break;
                case 3: $paymentType = 'كليك'; break;
                case 4: $paymentType = 'فيزا'; break;
                case 5: $paymentType = 'دفعة جزئية'; break;
                default: $paymentType = 'غير محدد'; break;
              }

              // 🟩 حالة الدفع
              switch ($value->payment_status) {
                case 'paid':
                  $statusBadge = '<span class="badge bg-success">مدفوع بالكامل</span>';
                  break;
                case 'partial':
                  $statusBadge = '<span class="badge bg-warning text-dark">مدفوع جزئياً</span>';
                  break;
                default:
                  $statusBadge = '<span class="badge bg-danger">غير مدفوع</span>';
                  break;
              }

              $paid     = number_format($value->paid_amount ?? 0, 3);
              $remain   = number_format($value->remaining_amount ?? 0, 3);
              $total    = number_format($value->total ?? 0, 3);
              $discount = number_format($value->discount ?? 0, 3);
            ?>
            <tr>
              <td><?= $i++; ?></td>
              <td><?= htmlspecialchars($value->invoice_date); ?></td>
              <td><?= htmlspecialchars($value->invoice_number); ?></td>
              <td><?= htmlspecialchars($value->customer_name); ?></td>
              <td><?= $paymentType; ?></td>
              <td><?= $statusBadge; ?></td>
              <td><?= $total; ?> د.أ</td>
              <td><?= $paid; ?> د.أ</td>
              <td><?= $remain; ?> د.أ</td>
              <td><?= htmlspecialchars($value->quantity); ?></td>
              <td><?= $discount; ?> د.أ</td>
              <td>
                <div class="d-flex justify-content-center">
                  <a href="edit_sales_invoice?id=<?= $value->id; ?>" class="btn btn-sm text-success" title="تعديل"><i class="fas fa-edit"></i></a>
                  <a href="print_sales_invoice.php?id=<?= $value->id; ?>" class="btn btn-sm text-primary" title="طباعة" target="_blank"><i class="fas fa-print"></i></a>
                  <a class="btn btn-sm text-danger del_click del_section"
                     id="<?= $value->id; ?>" table="sales_invoices"
                     data-bs-toggle="modal" data-bs-target="#delete" title="حذف">
                    <i class="fas fa-trash-alt"></i>
                  </a>
                </div>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>

    </div>
  </div>
</div>

<?php include('includes/footer.php'); ?>
<?php ob_end_flush(); ?>
