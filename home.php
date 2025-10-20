<?php 
include('includes/navbar.php'); 
include('actions/data_home.php'); 
show_messages();
if (isset($_SESSION['role_id']) && $_SESSION['role_id'] == 1): ?>
  <!-- ================= الكروت الرئيسية ================= -->
  <div class="row g-2 mb-2">
    <!-- الصف الأول: كروت المبيعات -->
    <div class="col-6 col-md-4">
      <div class="card overflow-hidden shadow-sm h-100">
        <div class="bg-holder bg-card" style="background-image:url(assets/img/icons/spot-illustrations/corner-1.png);"></div>
        <div class="card-body position-relative">
          <h6>إجمالي المبيعات</h6>
          <div class="display-4 fs-4 mb-2 fw-normal text-success"
              data-countup='{"endValue":<?= (float)$totalSales ?>,"suffix":" د.أ"}'>0</div>
        </div>
      </div>
    </div>

    <div class="col-6 col-md-4">
      <div class="card overflow-hidden shadow-sm h-100">
        <div class="bg-holder bg-card" style="background-image:url(assets/img/icons/spot-illustrations/corner-2.png);"></div>
        <div class="card-body position-relative">
          <h6>مبيعات اليوم</h6>
          <div class="display-4 fs-4 mb-2 fw-normal text-primary"
              data-countup='{"endValue":<?= (float)$todaySales ?>,"suffix":" د.أ"}'>0</div>
        </div>
      </div>
    </div>

    <div class="col-6 col-md-4">
      <div class="card overflow-hidden shadow-sm h-100">
        <div class="bg-holder bg-card" style="background-image:url(assets/img/icons/spot-illustrations/corner-3.png);"></div>
        <div class="card-body position-relative">
          <h6>مبيعات الشهر الحالي</h6>
          <div class="display-4 fs-4 mb-2 fw-normal text-warning"
              data-countup='{"endValue":<?= (float)$currentMonthSales ?>,"suffix":" د.أ"}'>0</div>
        </div>
      </div>
    </div>
    <!-- الصف الثاني: كروت المشتريات -->
    <div class="col-6 col-md-4">
      <div class="card overflow-hidden shadow-sm h-100">
        <div class="bg-holder bg-card" style="background-image:url(assets/img/icons/spot-illustrations/corner-4.png);"></div>
        <div class="card-body position-relative">
          <h6>إجمالي المشتريات</h6>
          <div class="display-4 fs-4 mb-2 fw-normal text-danger"
              data-countup='{"endValue":<?= (float)$totalPurchases ?>,"suffix":" د.أ"}'>0</div>
        </div>
      </div>
    </div>

    <div class="col-6 col-md-4">
      <div class="card overflow-hidden shadow-sm h-100">
        <div class="bg-holder bg-card" style="background-image:url(assets/img/icons/spot-illustrations/corner-2.png);"></div>
        <div class="card-body position-relative">
          <h6>مشتريات اليوم</h6>
          <div class="display-4 fs-4 mb-2 fw-normal text-info"
              data-countup='{"endValue":<?= (float)$todayPurchases ?>,"suffix":" د.أ"}'>0</div>
        </div>
      </div>
    </div>

    <div class="col-6 col-md-4">
      <div class="card overflow-hidden shadow-sm h-100">
        <div class="bg-holder bg-card" style="background-image:url(assets/img/icons/spot-illustrations/corner-1.png);"></div>
        <div class="card-body position-relative">
          <h6>مشتريات الشهر الحالي</h6>
          <div class="display-4 fs-4 mb-2 fw-normal text-secondary"
              data-countup='{"endValue":<?= (float)$currentMonthPurchases ?>,"suffix":" د.أ"}'>0</div>
        </div>
      </div>
    </div>
  </div>
  <!-- ================= الجداول ================= -->
  <div class="row mt-2">
    <!-- جدول المبيعات الشهرية -->
    <div class="col-md-6 mb-2">
      <div class="card shadow-sm h-100">
        <div class="card-header bg-success text-white">📈 المبيعات الشهرية</div>
        <div class="card-body p-2">
          <table class="table table-sm table-bordered mb-0">
            <thead class="table-light">
              <tr>
                <th>الشهر</th>
                <th>المجموع (د.أ)</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($monthlySales as $month => $sales): ?>
                <tr>
                  <td><?= $month ?></td>
                  <td class="text-success fw-bold"><?= number_format($sales) ?> د.أ</td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- جدول المشتريات الشهرية -->
    <div class="col-md-6 mb-2">
      <div class="card shadow-sm h-100">
        <div class="card-header bg-danger text-white">📉 المشتريات الشهرية</div>
        <div class="card-body p-2">
          <table class="table table-sm table-bordered mb-0">
            <thead class="table-light">
              <tr>
                <th>الشهر</th>
                <th>المجموع (د.أ)</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($monthlyPurchases as $month => $purchases): ?>
                <tr>
                  <td><?= $month ?></td>
                  <td class="text-danger fw-bold"><?= number_format($purchases) ?> د.أ</td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
  <!-- ================= جداول طرق الدفع ================= -->
  <div class="row mt-2">
    <!-- طرق الدفع للمبيعات -->
    <div class="col-md-6 mb-2">
      <div class="card shadow-sm h-100">
        <div class="card-header bg-primary text-white">💰 توزيع طرق الدفع - المبيعات</div>
        <div class="card-body p-2">
          <table class="table table-sm table-bordered mb-0">
            <thead class="table-light">
              <tr>
                <th>طريقة الدفع</th>
                <th>المبلغ (د.أ)</th>
              </tr>
            </thead>
            <tbody>
              <tr><td>ذمم</td><td><?= number_format($paymentSales[0]) ?> د.أ</td></tr>
              <tr><td>نقداً</td><td><?= number_format($paymentSales[1]) ?> د.أ</td></tr>
              <tr><td>شيكات</td><td><?= number_format($paymentSales[2]) ?> د.أ</td></tr>
              <tr><td>كليك</td><td><?= number_format($paymentSales[3]) ?> د.أ</td></tr>
              <tr><td>فيزا</td><td><?= number_format($paymentSales[4]) ?> د.أ</td></tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- طرق الدفع للمشتريات -->
    <div class="col-md-6 mb-2">
      <div class="card shadow-sm h-100">
        <div class="card-header bg-warning">💳 توزيع طرق الدفع - المشتريات</div>
        <div class="card-body p-2">
          <table class="table table-sm table-bordered mb-0">
            <thead class="table-light">
              <tr>
                <th>طريقة الدفع</th>
                <th>المبلغ (د.أ)</th>
              </tr>
            </thead>
            <tbody>
              <tr><td>ذمم</td><td><?= number_format($paymentPurchases[0]) ?> د.أ</td></tr>
              <tr><td>نقداً</td><td><?= number_format($paymentPurchases[1]) ?> د.أ</td></tr>
              <tr><td>شيكات</td><td><?= number_format($paymentPurchases[2]) ?> د.أ</td></tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
<?php endif; ?>




<?php if (isset($_SESSION['role_id']) && $_SESSION['role_id'] == 2): 
$user_id = $_SESSION['user_id'];
$invoices = get_user_sales_invoices_today($user_id);
$receipts = get_user_receipt_vouchers_today($user_id);
$payments = get_user_payment_vouchers_today($user_id);
$deliveries = get_user_deliveries_today();
$customer = get_customers();
?>
  <!-- 🌟 الصف الأول: أزرار العمليات -->
<div class="row g-2 mb-2">
  <!-- زر إضافة مبيعة -->
  <div class="col-md">
    <a href="add_sales" class="btn btn-primary text-dark d-flex flex-column justify-content-center align-items-center py-4 w-100 h-100 shadow-sm">
      <i class="fas fa-cart-plus fa-4x mb-2"></i>
      <span class="fw-bold fs-3">إضافة مبيعة</span>
    </a>
  </div>
  <!-- زر سند قبض -->
  <div class="col-md">
    <a href="receipt_voucher" class="btn btn-success text-dark d-flex flex-column justify-content-center align-items-center py-4 w-100 h-100 shadow-sm">
      <i class="fas fa-hand-holding-usd fa-4x mb-2"></i>
      <span class="fw-bold fs-3">سند قبض</span>
    </a>
  </div>
  <!-- زر سند صرف -->
  <div class="col-md">
    <a href="payment_voucher" class="btn btn-warning text-dark d-flex flex-column justify-content-center align-items-center py-4 w-100 h-100 shadow-sm">
      <i class="fas fa-receipt fa-4x mb-2"></i>
      <span class="fw-bold fs-3">سند صرف</span>
    </a>
  </div>
  <!-- زر إضافة زبون -->
  <div class="col-md">
    <button class="btn btn-info text-dark add_click d-flex flex-column justify-content-center align-items-center py-4 w-100 h-100 shadow-sm" data-bs-toggle="modal" data-bs-target="#customer_modal">
      <i class="fas fa-user-plus fa-4x mb-2"></i>
      <span class="fw-bold fs-3">إضافة زبون</span>
    </button>
  </div>

  <!-- زر التوصيل -->
  <div class="col-md">
    <button class="btn btn-secondary text-dark add_click d-flex flex-column justify-content-center align-items-center py-4 w-100 h-100 shadow-sm" data-bs-toggle="modal" data-bs-target="#delivery_modal">
      <i class="fas fa-truck fa-4x mb-2"></i>
      <span class="fw-bold fs-3">توصيل</span>
    </button>
  </div>
</div>

  <!-- 📊 الصف الثاني: الجداول -->
<div class="row g-2 mb-2">
  <!-- جدول المبيعات -->
  <div class="col-12 col-md-4">
    <div class="card shadow-sm border-0 h-100">
      <div class="card-header bg-primary text-white fw-bold">
        <i class="fas fa-shopping-cart me-1"></i> المبيعات
      </div>
      <div class="card-body p-0">
        <table class="table table-sm table-striped mb-0 text-center align-middle">
          <thead class="table-light">
            <tr>
              <th>الرقم</th>
              <th>العميل</th>
              <th>المبلغ</th>
              <th></th>
            </tr>
          </thead>
           <?php if (!empty($invoices)): ?>
        <?php foreach ($invoices as $invoice): ?>
          <tr>
            <td><?= htmlspecialchars($invoice->invoice_number) ?></td>
            <td><?= htmlspecialchars($invoice->customer_name ?? '—') ?></td>
            <td><?= number_format((float)$invoice->total, 2) ?> د.أ</td>
            <td class="text-end">
              <div>
                <a class="btn p-0 text-success" href="edit_sales_invoice?id=<?= $invoice->id; ?>" type="button" data-bs-toggle="tooltip" data-bs-placement="top" title="تعديل"><i class="fas fa-edit"></i></a>
                <a class="btn p-0 text-primary" href="print_sales_invoice?id=<?= $invoice->id; ?>" type="button" data-bs-toggle="tooltip" data-bs-placement="top" title="طباعة" target="_blank"><i class="fas fa-print"></i></a>
              </div>
            </td>
          </tr>
        <?php endforeach; ?>
      <?php else: ?>
        <tr>
          <td colspan="3" class="text-muted">لا توجد فواتير لليوم الحالي</td>
        </tr>
      <?php endif; ?>
        </table>
      </div>
    </div>
  </div>
  <!-- جدول سندات القبض -->
  <div class="col-12 col-md-4">
    <div class="card shadow-sm border-0 h-100">
      <div class="card-header bg-success text-white fw-bold">
        <i class="fas fa-hand-holding-usd me-1"></i> سندات القبض
      </div>
      <div class="card-body p-0">
        <table class="table table-sm table-striped mb-0">
          <thead class="table-light">
            <tr>
              <th>الرقم</th>
              <th>العميل</th>
              <th>المبلغ</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($receipts)): ?>
              <?php foreach ($receipts as $r): ?>
                <tr>
                  <td><?= htmlspecialchars($r->id) ?></td>
                  <td><?= htmlspecialchars($r->customer_name ?? '—') ?></td>
                  <td><?= number_format((float)$r->total_amount, 2) ?> د.أ</td>
                  <td><a class="btn p-0 text-primary" href="print_receipt?id=<?= $r->id ?>" type="button" data-bs-toggle="tooltip" data-bs-placement="top" title="طباعة" target="_blank">
                        <span class="fas fa-print"></span>
                      </a>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr>
                <td colspan="3" class="text-muted">لا توجد سندات قبض لهذا اليوم</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
  <!-- جدول سندات الصرف -->
  <div class="col-12 col-md-4">
    <div class="card shadow-sm border-0 h-100">
      <div class="card-header bg-warning text-white fw-bold">
        <i class="fas fa-receipt me-1"></i> سندات الصرف
      </div>
      <div class="card-body p-0">
        <table class="table table-sm table-striped mb-0">
          <thead class="table-light">
            <tr>
              <th>الرقم</th>
              <th>المورد</th>
              <th>المبلغ</th>
            </tr>
          </thead>
           <tbody>
            <?php if (!empty($payments)): ?>
              <?php foreach ($payments as $p): ?>
                <tr>
                  <td><?= htmlspecialchars($p->id) ?></td>
                  <td>
                    <?= htmlspecialchars(
                      $p->supplier_name
                      ?? $p->employee_name
                      ?? $p->expense_category_name
                      ?? '—'
                    ) ?>
                  </td>
                  <td><?= number_format((float)$p->total_amount, 2) ?> د.أ</td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr>
                <td colspan="3" class="text-muted">لا توجد سندات صرف لهذا اليوم</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<!-- 🚚 جدول التوصيل -->
<div class="row g-2 mb-2">
   <div class="col-md">
    <div class="card shadow-sm border-0 h-100">
      <div class="card-header bg-info text-white fw-bold">
        <i class="fas fa-truck me-1"></i> التوصيل
      </div>

      <div class="card-body p-0">
        <table class="table table-sm table-striped mb-0">
          <thead class="table-light">
            <tr>
              <th>الرقم</th>
              <th>الزبون</th>
              <th>الفاتورة</th>
              <th>العنوان</th>
              <th>الهاتف</th>
              <th>الوقت</th>
              <th>الحالة</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($deliveries)): ?>
              <?php foreach ($deliveries as $d): ?>
                <tr>
                  <td><?= htmlspecialchars($d->id) ?></td>
                  <td><?= htmlspecialchars($d->customer_name ?? '—') ?></td>
                  <td>
                    <?php if (!empty($d->invoice_id)): ?>
                      <a href="print_sales_invoice?id=<?= (int)$d->invoice_id ?>" target="_blank" class="text-decoration-none">
                        #<?= (int)$d->invoice_id ?>
                      </a>
                    <?php else: ?>
                      <span class="text-muted">—</span>
                    <?php endif; ?>
                  </td>
                  <td class="text-truncate" style="max-width:220px;" title="<?= htmlspecialchars($d->delivery_address ?? '') ?>">
                    <?= htmlspecialchars($d->delivery_address ?? '') ?>
                  </td>
                  <td><?= htmlspecialchars($d->delivery_phone ?? '') ?></td>
                  <td>
                    <?php
                      $dt = !empty($d->delivery_time) ? strtotime($d->delivery_time) : null;
                      echo $dt ? date('Y-m-d H:i', $dt) : '<span class="text-muted">—</span>';
                    ?>
                  </td>
                  <td>
                    <?php if (($d->delivery_status ?? '') === 'تم'): ?>
                      <span class="badge bg-success">تم</span>
                    <?php else: ?>
                      <span class="badge bg-secondary">لم يتم</span>
                    <?php endif; ?>
                  </td>
                  <td class="text-end">
                    <div class="d-inline-flex gap-2">
                      <a class="btn p-0 text-success" href="edit_delivery?id=<?= (int)$d->id ?>" data-bs-toggle="tooltip" title="تعديل">
                        <i class="fas fa-edit"></i>
                      </a>
                      <a class="btn p-0 text-info" href="view_delivery?id=<?= (int)$d->id ?>" data-bs-toggle="tooltip" title="عرض">
                        <i class="fas fa-eye"></i>
                      </a>
                      <a class="btn p-0 text-danger" href="actions/delete_delivery.php?id=<?= (int)$d->id ?>"
                        onclick="return confirm('تأكيد حذف عملية التوصيل؟')" data-bs-toggle="tooltip" title="حذف">
                        <i class="fas fa-trash-alt"></i>
                      </a>
                    </div>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr>
                <td colspan="8" class="text-muted">لا توجد عمليات توصيل لليوم الحالي</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<script>
$(document).ready(function() {
  // عند تغيير الزبون
  $('#customer_id_to_delivery').on('change', function() {
    const customerId = $(this).val();

    if (!customerId) {
      $('#invoice_id_to_delivery').html('<option value="">(اختياري) اختر الفاتورة</option>');
      return;
    }

    // استعلام AJAX لجلب الفواتير الخاصة بالزبون
    $.ajax({
      url: 'actions/get_invoices_by_customer.php',
      type: 'GET',
      data: { customer_id_to_delivery: customerId },
      success: function (data) {
        // تعبئة قائمة الفواتير بالنتائج
        $('#invoice_id_to_delivery').html(data);

        // لو تستخدم select2 فعّل هذا
        $('#invoice_id_to_delivery').trigger('change.select2');
      },
      error: function(xhr, status, error) {
        console.error("❌ خطأ في الاتصال:", error);
      }
    });
  });
});
</script>
<?php endif; ?>






<?php include('includes/footer.php'); ?>
