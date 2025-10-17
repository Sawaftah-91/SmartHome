<?php include('includes/navbar.php'); ?>
<?php include('actions/data_warehouse.php'); ?>
<?php show_messages(); ?>

<!-- بطاقات الإحصائيات -->
<div class="row g-2 mb-2">
  <div class="col-sm-6 col-md-3">
    <div class="card overflow-hidden" style="min-width: 12rem">
      <div class="bg-holder bg-card" style="background-image:url(assets/img/icons/spot-illustrations/corner-1.png);"></div>
      <div class="card-body position-relative">
        <h6>الكمية</h6>
        <div class="display-4 fs-4 mb-2 fw-normal font-sans-serif text-warning"
             data-countup='{"endValue":<?= $totalQuantity ?>}'>0</div>
      </div>
    </div>
  </div>

  <div class="col-sm-6 col-md-3">
    <div class="card overflow-hidden" style="min-width: 12rem">
      <div class="bg-holder bg-card" style="background-image:url(assets/img/icons/spot-illustrations/corner-2.png);"></div>
      <div class="card-body position-relative">
        <h6>القيمة الإجمالية</h6>
        <div class="display-4 fs-4 mb-2 fw-normal font-sans-serif text-info"
             data-countup='{"endValue":<?= $totalCost ?>,"decimalPlaces":2,"suffix":" د.أ"}'>0</div>
      </div>
    </div>
  </div>

  <div class="col-sm-6 col-md-3">
    <div class="card overflow-hidden" style="min-width: 12rem">
      <div class="bg-holder bg-card" style="background-image:url(assets/img/icons/spot-illustrations/corner-3.png);"></div>
      <div class="card-body position-relative">
        <h6>عدد الفئات</h6>
        <div class="display-4 fs-4 mb-2 fw-normal font-sans-serif"
             data-countup='{"endValue":<?= count($uniqueCategories) ?>}'>0</div>
      </div>
    </div>
  </div>

  <div class="col-sm-6 col-md-3">
    <div class="card overflow-hidden" style="min-width: 12rem">
      <div class="bg-holder bg-card" style="background-image:url(assets/img/icons/spot-illustrations/corner-2.png);"></div>
      <div class="card-body position-relative">
        <h6>عدد العلامات التجارية</h6>
        <div class="display-4 fs-4 mb-2 fw-normal font-sans-serif text-info"
             data-countup='{"endValue":<?= count($uniqueBrandings) ?>}'>0</div>
      </div>
    </div>
  </div>
</div>

<!-- جدول المخزون -->
<div class="row g-2 mb-2">
  <div class="card mb-1">
    <div class="card-body position-relative">
      <div class="d-flex justify-content-end mb-3">
        <a href="add_purchases.php" class="btn btn-primary btn-sm me-1 mb-1">
          <span class="fas fa-plus me-1" data-fa-transform="shrink-3"></span>
          <span class="lang" data-key="add"></span>
        </a>
          <!-- ✅ زر الطباعة -->
        <button onclick="printSelected()" class="btn btn-secondary btn-sm me-1 mb-1">
          <span class="fas fa-print me-1"></span>
          <span class="lang" data-key="print">طباعة</span>
        </button>
      </div>

      <div class="table-responsive scrollbar">
        <table id="purchaseTable" class="table table-sm table-hover datatable">
          <thead>
            <tr>
              <th>#</th>
              <th class="lang" data-key="item_name"></th>
              <th class="lang" data-key="model_number"></th>
              <th class="lang" data-key="category"></th>
              <th class="lang" data-key="branding"></th>
              <th class="lang" data-key="date"></th>
              <th class="lang" data-key="inv_quantity"></th>
              <th class="lang" data-key="cost_per_unit"></th>
              <th class="lang" data-key="cost"></th>
              <th class="lang" data-key="action"></th>
            </tr>
          </thead>
          <tbody>
            <?php $i = 1; foreach($row as $item): ?>
            <tr>
              <td><?= $i++ ?></td>
              <td><?= $item->item_name ?></td>
              <td><?= $item->barcode ?></td>
              <td><?= $item->category_name ?></td>
              <td><?= $item->branding_name ?></td>
              <td><?= date("Y-m-d", strtotime($item->created_at)) ?></td>
              <td><?= $item->quantity ?></td>
              <td><?= $item->cost ?> <small>د.أ</small></td>
              <td><?= $item->total_cost_formatted ?> <small>د.أ</small></td>
              <td>
                  <div>
                    <a class="btn p-0" href="item_card?id=<?php echo $item->item_id; ?>" type="button" data-bs-toggle="tooltip" data-bs-placement="top" title="بطاقة مادة"><span class="text-500 fas fa-clipboard-list"></span></a>
                  </div>
                </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
          <tfoot>
            <tr>
              <th>#</th>
              <th class="lang" data-key="item_name"></th>
              <th class="lang" data-key="model_number"></th>
              <th class="lang" data-key="category"></th>
              <th class="lang" data-key="branding"></th>
              <th class="lang" data-key="date"></th>
              <th class="lang" data-key="inv_quantity"></th>
              <th class="lang" data-key="cost_per_unit"></th>
              <th class="lang" data-key="cost"></th>
              <th class="lang" data-key="action"></th>
            </tr>
          </tfoot>
        </table>
      </div>
    </div>
  </div>
</div>
<script src="js-moudels/stock.js"></script>
<?php include('includes/footer.php'); ?>
