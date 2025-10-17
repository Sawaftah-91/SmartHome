<?php
// ============================
// 🔹 تهيئة الصفحة والملفات الأساسية
// ============================
include('includes/navbar.php');
include('../includes/functions.php');

// ============================
// 🔹 عرض رسائل النظام (نجاح / خطأ)
// ============================
show_messages();

// ============================
// 🔹 جلب البيانات الأساسية
// ============================
$suppliers = get_suppliers();   // جميع الزبائن
$payments  = get_payments();    // جميع سندات القبض
$employees = get_users();
?>

<div class="row g-2 mb-2">
  <div class="card mb-1">
    <div class="card-body position-relative">

      <!-- العنوان + زر الإضافة -->
      <div class="d-flex align-items-center justify-content-between mb-3">
        <h4 class="mb-0"></h4>
        <button class="btn btn-primary btn-sm add_click" data-bs-toggle="modal" data-bs-target="#payment_voucher_modal">
          <i class="bi bi-plus-circle"></i> إضافة  
        </button>
      </div>

      <!-- جدول عرض السندات -->
      <div class="table-responsive scrollbar">
        <table class="table table-sm table-hover datatable">
  <thead>
    <tr>
      <th>#</th>
      <th>النوع</th>
      <th>المستفيد</th>
      <th>المبلغ</th>
      <th>طريقة الدفع</th>
      <th>تاريخ السند</th>
      <th>تفاصيل</th>
    </tr>
  </thead>

  <tbody>
    <?php 
    $methods = [
      'cash'   => 'نقداً',
      'cheque' => 'شيك',
      'click'  => 'كليك',
      'visa'   => 'فيزا'
    ];

    $types = [
      'supplier' => 'دفعة لمورد',
      'employee' => 'راتب موظف',
      'expense'  => 'مصروف عام'
    ];

    foreach ($payments as $index => $p): 
      // تحديد اسم المستفيد حسب النوع
      $beneficiary = '—';
      if ($p->voucher_type === 'supplier') {
        $beneficiary = htmlspecialchars($p->supplier_name ?? '—');
      } elseif ($p->voucher_type === 'employee') {
        $beneficiary = htmlspecialchars($p->employee_name ?? '—');
      } elseif ($p->voucher_type === 'expense') {
        $beneficiary = htmlspecialchars($p->expense_category_name ?? $p->expense_description ?? '—');
      }
    ?>
      <tr>
        <td><?= $index + 1 ?></td>
        <td><?= htmlspecialchars($types[$p->voucher_type] ?? $p->voucher_type) ?></td>
        <td><?= $beneficiary ?></td>
        <td><?= number_format($p->total_amount, 2) ?> د.أ</td>
        <td><?= htmlspecialchars($methods[$p->payment_method] ?? $p->payment_method) ?></td>
        <td><?= htmlspecialchars(date('Y-m-d', strtotime($p->payment_date))) ?></td>
        <td>
          <div class="gap-2 d-flex justify-content-center">
            <!-- 🖨️ طباعة السند -->
            <a href="print_payment?id=<?= $p->id ?>" 
               class="btn p-0" 
               title="طباعة السند" 
               target="_blank">
              <span class="text-success fas fa-print"></span>
            </a>
          </div>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>

  <tfoot>
    <tr>
      <th>#</th>
      <th>النوع</th>
      <th>المستفيد</th>
      <th>المبلغ</th>
      <th>طريقة الدفع</th>
      <th>تاريخ السند</th>
      <th>تفاصيل</th>
    </tr>
  </tfoot>
</table>

      </div>

    </div>
  </div>
</div>
<script src="js-moudels/payment_voucher.js"></script>
<?php include('includes/footer.php'); ?>
