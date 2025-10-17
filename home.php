<?php include('includes/navbar.php'); ?>
<?php include('actions/data_home.php'); ?>

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


<?php include('includes/footer.php'); ?>
