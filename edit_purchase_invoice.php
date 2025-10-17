<?php include('includes/navbar.php'); ?>

<!-- عناصر مخفية لنسخ الخيارات باستخدام JS -->
<select id="itemOptions" class="d-none form-select">
  <option value="">اختر اسمًا</option>
  <?php foreach (get_items() as $item): ?>
    <option value="<?= htmlspecialchars($item->id) ?>"><?= htmlspecialchars($item->name." - ".$item->barcode) ?></option>
  <?php endforeach; ?>
</select>

<select id="unitsOptions" class="d-none form-select">
  <?php 
  $units = get_units(); 
  foreach ($units as $index => $unit): 
  ?>
    <option value="<?= htmlspecialchars($unit->id) ?>" <?= $index === 0 ? 'selected' : '' ?>>
      <?= htmlspecialchars($unit->short_name) ?>
    </option>
  <?php endforeach; ?>
</select>

<?php show_messages(); ?>

<?php
// 🟩 جلب بيانات الفاتورة والمواد
$invoice_id = $_GET['id'];
$invoice = get_purchase_invoices($invoice_id); // كائن واحد
$invoice_items = get_purchase_invoice_items($invoice_id); // مصفوفة

// 🟩 جلب بيانات الشيك إن وُجدت
$cheque = null;
if ($invoice->status == 2) {
  $cheque = get_cheque_by_invoice($invoice_id); // دالة تجلب صف الشيك الواحد
}
?>

<div class="row g-2 mb-2">
  <div class="card mb-4">
    <div class="card-header fw-bold">
      تعديل فاتورة شراء
    </div>
    <div class="card-body position-relative">
      <form action="actions/edit_purchase.php" method="post">
        <input type="hidden" name="invoice_id" value="<?= $invoice->id ?>">

        <!-- 🟩 معلومات الفاتورة -->
        <div class="mb-4">
          <h5 class="fw-bold border-bottom pb-2 mb-3">معلومات الفاتورة</h5>
          <div class="row gy-3 gx-3">
            <div class="col-12 col-md-3">
              <label for="supplierName" class="form-label fw-bold">اسم المورد</label>
              <select class="form-select" id="supplierName" name="supplierName" required>
                <option value="">اختر اسمًا</option>
                <?php foreach (get_suppliers() as $value): ?>
                  <option value="<?= $value->id ?>" <?= ($value->id == $invoice->supplier_id ? 'selected' : '') ?>>
                    <?= $value->company_name ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>

            <div class="col-12 col-md-3">
              <label for="invoiceDate" class="form-label fw-bold">تاريخ الفاتورة</label>
              <input type="date" class="form-control" id="invoiceDate" name="invoiceDate" value="<?= $invoice->invoice_date ?>" required>
            </div>

            <div class="col-12 col-md-3">
              <label for="supplier_invoice_number" class="form-label fw-bold">رقم فاتورة المورد</label>
              <input type="text" class="form-control" id="supplier_invoice_number" name="supplier_invoice_number" value="<?= $invoice->supplier_invoice_number ?>">
            </div>

            <div class="col-12 col-md-3">
              <label for="isPaid" class="form-label fw-bold">حساب الفاتورة</label>
              <select class="form-select" id="isPaid" name="isPaid">
                <option value="1" <?= $invoice->status == 1 ? 'selected' : '' ?>>نقداً</option>
                <option value="0" <?= $invoice->status == 0 ? 'selected' : '' ?>>ذمم</option>
                <option value="2" <?= $invoice->status == 2 ? 'selected' : '' ?>>شيكات</option>
              </select>
            </div>
          </div>
        </div>

        <!-- 🟩 تفاصيل الشيك (تظهر فقط إذا كان نوع الدفع شيكات) -->
        <div class="row gy-3 gx-3 mt-3 <?= $invoice->status == 2 ? '' : 'd-none' ?>" id="chequeDetails">
          <div class="col-12 col-md-3">
            <label for="cheque_number" class="form-label fw-bold">رقم الشيك</label>
            <input type="text" class="form-control" id="cheque_number" name="cheque_number"
                   value="<?= $cheque ? htmlspecialchars($cheque->cheque_number) : '' ?>">
          </div>
          <div class="col-12 col-md-3">
            <label for="cheque_value" class="form-label fw-bold">قيمة الشيك</label>
            <input type="number" step="0.01" class="form-control" id="cheque_value" name="cheque_value"
                   value="<?= $cheque ? htmlspecialchars($cheque->cheque_value) : '' ?>">
          </div>
          <div class="col-12 col-md-3">
            <label for="cheque_date" class="form-label fw-bold">تاريخ الشيك</label>
            <input type="date" class="form-control" id="cheque_date" name="cheque_date"
                   value="<?= $cheque ? htmlspecialchars($cheque->cheque_date) : '' ?>">
          </div>
          <div class="col-12 col-md-3">
            <label for="cheque_bank" class="form-label fw-bold">البنك الصادر له</label>
            <input type="text" class="form-control" id="cheque_bank" name="cheque_bank"
                   value="<?= $cheque ? htmlspecialchars($cheque->cheque_bank) : '' ?>">
          </div>
        </div>

        <!-- 🟩 تفاصيل المواد -->
        <div class="mb-4 mt-4">
          <h5 class="fw-bold border-bottom pb-2 mb-3">تفاصيل المواد</h5>

          <div class="table-responsive">
            <table class="table table-bordered table-sm align-middle text-center" id="itemsTable">
              <thead class="table-light">
                <tr>
                  <th>المادة</th>
                  <th>السعر</th>
                  <th>الكمية</th>
                  <th>الوحدة</th>
                  <th>الإجمالي</th>
                  <th>إجراء</th>
                </tr>
              </thead>
              <tbody id="itemsContainer">
                <?php foreach ($invoice_items as $item): ?>
                <tr class="item-row">
                  <td>
                    <select class="form-control select2" name="name[]">
                      <?php foreach (get_items() as $opt): ?>
                        <option value="<?= $opt->id ?>" <?= $opt->id == $item->item_id ? 'selected' : '' ?>>
                          <?= htmlspecialchars($opt->name." - ".$opt->barcode) ?>
                        </option>
                      <?php endforeach; ?>
                    </select>
                  </td>
                  <td><input type="number" step="0.0001" class="form-control" name="price[]" value="<?= $item->price_with_tax ?>"></td>
                  <td><input type="number" step="1" class="form-control" name="quantity[]" value="<?= $item->quantity ?>"></td>
                  <td>
                    <select class="form-control select2" name="units[]">
                      <?php foreach (get_units() as $unit): ?>
                        <option value="<?= $unit->id ?>" <?= $unit->id == $item->unit_id ? 'selected' : '' ?>>
                          <?= $unit->short_name ?>
                        </option>
                      <?php endforeach; ?>
                    </select>
                  </td>
                  <td><input type="number" class="form-control" name="total_price[]" value="<?= $item->total_with_tax ?>" readonly></td>
                  <td>
                    <button type="button" class="btn btn-danger btn-sm removeItemBtn" title="حذف">
                      <span class="fas fa-trash"></span>
                    </button>
                  </td>
                </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>

        <!-- 🟩 الأزرار -->
        <div class="d-flex justify-content-between flex-wrap gap-2 mb-3">
          <button type="button" class="btn btn-info lang" id="addItemBtn">
            <i class="fas fa-plus-circle me-1"></i> إضافة مادة
          </button>
          <button type="submit" class="btn btn-success lang">
            <i class="fas fa-save me-1"></i> حفظ التعديلات
          </button>
        </div>

        <!-- 🟩 ملخص -->
        <div class="mb-3">
          <h5 class="fw-bold border-bottom pb-2 mb-3">ملخص الفاتورة</h5>
          <div class="row gy-3 gx-3">
            <div class="col-12 col-md-4">
              <label for="totalDiscount" class="form-label fw-bold">الخصم %</label>
              <input type="number" step="0.01" class="form-control form-control-sm" id="totalDiscount" name="totalDiscount" value="<?= $invoice->discount ?>">
            </div>
            <div class="col-12 col-md-4">
              <label for="totalQuantity" class="form-label fw-bold">الكمية</label>
              <input type="number" class="form-control form-control-sm" id="totalQuantity" name="totalQuantity" value="<?= $invoice->quantity ?>" readonly>
            </div>
            <div class="col-12 col-md-4">
              <label for="totalWithTax" class="form-label fw-bold">الإجمالي الكلي</label>
              <input type="number" class="form-control form-control-sm" id="totalWithTax" name="totalWithTax" value="<?= $invoice->total ?>" readonly>
            </div>
          </div>
        </div>

      </form>
    </div>
  </div>
</div>

<script src="js-moudels/edit_purchase.js"></script>
<?php include('includes/footer.php'); ?>
