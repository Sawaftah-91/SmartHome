<?php include('includes/navbar.php'); ?>
<?php include('actions/data_suppliers.php'); ?>
<?php show_messages(); ?>

<div class="row g-2 mb-2">
  <div class="card mb-1">
    <div class="card-body position-relative">

      <h4 class="mb-3">
        فواتير المورد: 
        <span class="text-primary">
          <?= htmlspecialchars($supplier_name) ?>
        </span>
      </h4>

      <div class="table-responsive scrollbar">
        <table class="table table-sm table-hover datatable">
          <thead>
            <tr>
              <th>#</th>
              <th>رقم الفاتورة</th>
              <th>تاريخ الفاتورة</th>
              <th>المبلغ</th>
              <th>الحالة</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($supplier_invoices)): ?>
              <?php foreach ($supplier_invoices as $index => $invoice): ?>
                <tr>
                  <td><?= $index + 1 ?></td>
                  <td><?= htmlspecialchars($invoice->invoice_number) ?></td>
                  <td><?= htmlspecialchars($invoice->invoice_date) ?></td>
                  <td><?= number_format($invoice->total, 2) ?> د.أ</td>
                  <td>
                    <?php if ($invoice->status == 1): ?>
                      <span class="badge bg-success">مدفوعة</span>
                    <?php else: ?>
                      <span class="badge bg-danger">غير مدفوعة</span>
                    <?php endif; ?>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr>
                <td colspan="5" class="text-center">لا يوجد فواتير لهذا المورد</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>

    </div>
  </div>
</div>

<?php include('includes/footer.php'); ?>
