<?php include('includes/navbar.php'); ?>

<?php
// -------------------------------------------------------------
// 🟩 1. التحقق من رقم الفاتورة وجلب بياناتها
// -------------------------------------------------------------
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("رقم الفاتورة غير موجود");
}
$invoice_id = intval($_GET['id']);

// 🟢 جلب بيانات الفاتورة
$invoice = get_sales_invoices($invoice_id);
if (!$invoice) die("الفاتورة غير موجودة");

// 🟢 جلب تفاصيل الأصناف
$items_details = get_sales_invoice_items($invoice_id);
if (!$items_details) $items_details = [];

// 🟢 جلب الأصناف والزبائن
$items = get_items();
$customers = get_customers();

// -------------------------------------------------------------
// 🟩 2. جلب بيانات الشيك/كليك/فيزا (إن وُجدت)
// -------------------------------------------------------------
$chequeData = get_record("SELECT * FROM cheques WHERE sales_invoice_id = '$invoice_id' LIMIT 1");
$clickData  = get_record("SELECT * FROM cash_flow WHERE sale_invoice_id = '$invoice_id' LIMIT 1");

// 🟢 تعبئة القيم إذا كانت موجودة
$cheque_number = $chequeData['cheque_number'] ?? '';
$cheque_date   = $chequeData['cheque_date']   ?? '';
$cheque_value  = $chequeData['cheque_value']  ?? '';
$cheque_bank   = $chequeData['cheque_bank']   ?? '';
$click_ref     = $clickData['click_ref']      ?? '';
$visa_ref      = $clickData['visa_ref']       ?? '';
?>

<!-- قائمة مخفية لاستخدامها في JS -->
<select id="itemOptions" class="d-none form-select">
  <option value="">اختر مادة</option>
  <?php foreach ($items as $item): ?>
    <option value="<?php echo $item->id; ?>"
            data-price_before_tax="<?php echo $item->price_after_tax; ?>"
            data-tax="<?php echo $item->tax_value; ?>"
            data-stock_qty="<?php echo $item->stock_qty; ?>">
      <?php echo $item->name . ' - ' . $item->barcode . ' - ' . $item->branding_name . ' (المخزون: ' . $item->stock_qty . ')'; ?>
    </option>
  <?php endforeach; ?>
</select>

<?php show_messages(); ?>

<div class="row g-2 mb-2">
  <div class="card mb-4">
    <div class="card-header fw-bold">تعديل فاتورة مبيعات</div>
    <div class="card-body position-relative">

      <form action="actions/edit_sales.php" method="post">
        <input type="hidden" name="invoice_id" value="<?php echo $invoice->id; ?>">

        <!-- 🟩 معلومات الفاتورة -->
        <div class="mb-4">
          <h5 class="fw-bold border-bottom pb-2 mb-3">معلومات الفاتورة</h5>
          <div class="row gy-3 gx-3">
            <div class="col-12 col-md-8">
              <label class="form-label fw-bold">اسم الزبون</label>
              <select class="form-select searchable-select" name="customerName" required>
                <option value="">اختر اسمًا</option>
                <?php foreach ($customers as $customer): 
                  $sel = ($customer->id == $invoice->customer_id) ? "selected" : ""; ?>
                  <option value="<?php echo $customer->id; ?>" <?php echo $sel; ?>>
                    <?php echo $customer->customer_name; ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>

            <div class="col-12 col-md-2">
              <label class="form-label fw-bold">تاريخ الفاتورة</label>
              <input type="date" class="form-control" name="invoiceDate" 
                     value="<?php echo $invoice->invoice_date; ?>" required>
            </div>

            <div class="col-12 col-md-2">
              <label class="form-label fw-bold">حالة الدفع</label>
              <select class="form-select" id="isPaid" name="isPaid">
                <option value="1" <?php echo ($invoice->status==1)?'selected':''; ?>>نقداً</option>
                <option value="0" <?php echo ($invoice->status==0)?'selected':''; ?>>ذمم</option>
                <option value="2" <?php echo ($invoice->status==2)?'selected':''; ?>>شيكات</option>
                <option value="3" <?php echo ($invoice->status==3)?'selected':''; ?>>كليك</option>
                <option value="4" <?php echo ($invoice->status==4)?'selected':''; ?>>فيزا</option>
              </select>
            </div>
          </div>

          <!-- 🟨 معلومات الدفع الإضافية -->
          <div class="col-12">
            <!-- 🧾 شيكات -->
            <div id="chequeFields" class="row g-2 mt-3 border-top pt-2 <?php echo ($invoice->status==2)?'':'d-none'; ?>">
              <div class="col-12"><strong>معلومات الشيك</strong></div>
              <div class="col-md-3">
                <input type="text" class="form-control" name="cheque_number" 
                       value="<?php echo $cheque_number; ?>" placeholder="رقم الشيك">
              </div>
              <div class="col-md-3">
                <input type="date" class="form-control" name="cheque_date" 
                       value="<?php echo $cheque_date; ?>" placeholder="تاريخ الشيك">
              </div>
              <div class="col-md-3">
                <input type="number" class="form-control" name="cheque_value" 
                       value="<?php echo $cheque_value; ?>" placeholder="قيمة الشيك">
              </div>
              <div class="col-md-3">
                <input type="text" class="form-control" name="cheque_bank" 
                       value="<?php echo $cheque_bank; ?>" placeholder="اسم البنك">
              </div>
            </div>

            <!-- 💳 كليك -->
            <div id="clickFields" class="row g-2 mt-3 border-top pt-2 <?php echo ($invoice->status==3)?'':'d-none'; ?>">
              <div class="col-12"><strong>معلومات كليك</strong></div>
              <div class="col">
                <input type="text" class="form-control" name="click_ref" 
                       value="<?php echo $click_ref; ?>" placeholder="رقم مرجعي للدفعة">
              </div>
            </div>

            <!-- 💳 فيزا -->
            <div id="visaFields" class="row g-2 mt-3 border-top pt-2 <?php echo ($invoice->status==4)?'':'d-none'; ?>">
              <div class="col-12"><strong>معلومات فيزا</strong></div>
              <div class="col">
                <input type="text" class="form-control" name="visa_ref" 
                       value="<?php echo $visa_ref; ?>" placeholder="رقم مرجعي للدفعة">
              </div>
            </div>
          </div>
        </div>

        <!-- 🟩 تفاصيل المواد -->
        <div class="mb-4">
          <h5 class="fw-bold border-bottom pb-2 mb-3">تفاصيل المواد</h5>
          <div id="itemsContainer" class="vstack gap-3">
            <?php foreach ($items_details as $index => $detail): ?>
              <div class="item-row card shadow-sm border rounded-3 mb-3">
                <div class="card-body p-3">
                  <div class="row g-3 align-items-center">
                    <div class="col-12">
                      <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="badge bg-primary px-3 py-2">#<?php echo $index + 1; ?></span>
                        <button type="button" class="btn btn-outline-danger btn-sm removeItemBtn">
                          <i class="fas fa-trash-alt"></i>
                        </button>
                      </div>
                    </div>

                    <!-- الصنف -->
                    <div class="col-12 col-md-4">
                      <label class="form-label">الصنف</label>
                      <select class="form-select searchable-select" name="name[]">
                        <option value="">اختر مادة</option>
                        <?php foreach ($items as $item): ?>
                          <option value="<?php echo $item->id; ?>"
                            data-price_before_tax="<?php echo $item->price_after_tax; ?>"
                            data-tax="<?php echo $item->tax_value; ?>"
                            data-stock_qty="<?php echo $item->stock_qty; ?>"
                            <?php echo ($item->id == $detail->item_id) ? 'selected' : ''; ?>>
                            <?php echo $item->name . ' - ' . $item->barcode . ' - ' . $item->branding_name . ' (المخزون: ' . $item->stock_qty . ')'; ?>
                          </option>
                        <?php endforeach; ?>
                      </select>
                    </div>

                    <!-- السعر -->
                    <div class="col-6 col-md-2">
                      <label class="form-label">السعر</label>
                      <input class="form-control form-control-sm" type="number" step="0.0001"
                             name="price[]" value="<?php echo $detail->price_without_tax; ?>">
                    </div>

                    <!-- الكمية -->
                    <div class="col-6 col-md-2">
                      <label class="form-label">الكمية</label>
                      <input class="form-control form-control-sm" type="number" step="0.1"
                             name="quantity[]" value="<?php echo $detail->quantity; ?>">
                    </div>

                    <!-- الإجمالي -->
                    <div class="col-12 col-md-3">
                      <label class="form-label">الإجمالي</label>
                      <input class="form-control form-control-sm" type="number"
                             name="total_price[]" value="<?php echo $detail->total_with_tax * $detail->quantity; ?>" readonly>
                    </div>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          </div>

          <div class="d-flex justify-content-between flex-wrap gap-2 mt-3">
            <button type="button" class="btn btn-info" id="addItemBtn">
              <i class="fas fa-plus-circle me-1"></i> إضافة مادة
            </button>
            <button type="submit" class="btn btn-success">
              <i class="fas fa-save me-1"></i> تحديث الفاتورة
            </button>
          </div>
        </div>

        <!-- 🟩 ملخص الفاتورة -->
        <div class="mb-3">
          <h5 class="fw-bold border-bottom pb-2 mb-3">ملخص الفاتورة</h5>
          <div class="row gy-3 gx-3">
            <div class="col-12 col-md-4">
              <label class="form-label fw-bold">الخصم</label>
              <input type="number" step="0.01" class="form-control form-control-sm"
                     id="totalDiscount" name="totalDiscount" value="<?php echo $invoice->discount; ?>">
            </div>
            <div class="col-12 col-md-4">
              <label class="form-label fw-bold">الكمية</label>
              <input type="number" class="form-control form-control-sm"
                     id="totalQuantity" name="totalQuantity" value="<?php echo $invoice->quantity; ?>" readonly>
            </div>
            <div class="col-12 col-md-4">
              <label class="form-label fw-bold">الإجمالي الكلي</label>
              <input type="number" class="form-control form-control-sm"
                     id="totalWithTax" name="totalWithTax" value="<?php echo $invoice->total; ?>" readonly>
            </div>
          </div>
        </div>
      </form>

      <a href="sales" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-1"></i> العودة إلى الفواتير
      </a>
    </div>
  </div>
</div>

<!-- سكربت -->
<script src="js-moudels/edit_sales.js"></script>
<script>
$(document).ready(function(){
  $(".searchable-select").select2({ width:"100%", allowClear:true });
});
</script>

<?php include('includes/footer.php'); ?>
