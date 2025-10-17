<?php include('includes/navbar.php'); ?>
<?php include('actions/data_customers.php'); ?>
<?php show_messages(); ?>



<!-- جدول المخزون -->
<div class="row g-2 mb-2">
  <div class="card mb-1">
    <div class="card-body position-relative">

      <div class="table-responsive scrollbar">
        <table class="table table-sm table-hover datatable">
          <thead>
                <tr>
        <th>#</th>
        <th>اسم المورد</th>
        <th>إجمالي الفواتير</th>
        <th>الحالة</th>
         <th>تفاصيل</th>
      </tr>
          </thead>
           <tbody>
      <?php if(!empty($customers)): ?>
        <?php foreach($customers as $index => $customer): ?>
          <tr>
            <td><?= $index+1 ?></td>
            <td><?= htmlspecialchars($customer->customer_name) ?></td>
            <td><?= number_format($customer->total_invoices, 2) ?> د.أ</td>
            <td>
              <?php if($customer->status == 1): ?>
                <span class="badge bg-success">مدفوعة</span>
              <?php else: ?>
                <span class="badge bg-danger">غير مدفوعة</span>
              <?php endif; ?>
            </td>
             <td>
              <a href="customer_invoices?customer_id=<?= $customer->id ?>" 
                 class="btn btn-primary btn-sm" target="_blank">
كشف حساب              </a>
            </td>
          </tr>
        <?php endforeach; ?>
      <?php else: ?>
        <tr>
          <td colspan="5" class="text-center">لا يوجد بيانات</td>
        </tr>
      <?php endif; ?>
    </tbody>
          <tfoot>
             <tr>
        <th>#</th>
        <th>اسم المورد</th>
        <th>إجمالي الفواتير</th>
        <th>الحالة</th>
         <th>تفاصيل</th>
      </tr>
          </tfoot>
        </table>
      </div>
    </div>
  </div>
</div>
<?php include('includes/footer.php'); ?>
