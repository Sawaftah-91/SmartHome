$(document).ready(function () {
  // ============================================================
  // 🟩 1) تفعيل select2 على كل select بحثي
  // ============================================================
  function initSelect2(selector) {
    selector.select2({
      placeholder: "اختر مادة...",
      allowClear: true,
      width: "100%",
    });
  }
  initSelect2($(".searchable-select"));

  // ============================================================
  // 🟩 2) حساب الإجماليات الكلية أسفل الفاتورة
  // ============================================================
  function calculateFooterTotals() {
    let totalQuantity = 0;
    let totalPrice = 0;

    $("#itemsContainer .item-row").each(function () {
      const price =
        parseFloat($(this).find('input[name="price[]"]').val()) || 0;
      const quantity =
        parseFloat($(this).find('input[name="quantity[]"]').val()) || 0;
      const total = price * quantity;

      // تحديث الإجمالي لكل صف
      $(this).find('input[name="total_price[]"]').val(total.toFixed(3));

      totalQuantity += quantity;
      totalPrice += total;
    });

    $("#totalQuantity").val(totalQuantity.toFixed(3));

    let discount = parseFloat($("#totalDiscount").val()) || 0;
    let totalAfterDiscount = totalPrice - discount;
    if (totalAfterDiscount < 0) totalAfterDiscount = 0;

    $("#totalWithTax").val(totalAfterDiscount.toFixed(3));
  }

  // ============================================================
  // 🟩 3) إضافة صف جديد لمادة
  // ============================================================
  $("#addItemBtn").click(function () {
    const itemOptions = $("#itemOptions").html();
    const index = $("#itemsContainer .item-row").length + 1;

    const row = $(`
      <div class="item-row card shadow-sm border rounded-3 mb-3">
        <div class="card-body p-3">
          <div class="row g-3 align-items-center">
            <div class="col-12">
              <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="badge bg-primary px-3 py-2">#${index}</span>
                <button type="button" class="btn btn-outline-danger btn-sm removeItemBtn">
                  <i class="fas fa-trash-alt"></i>
                </button>
              </div>
            </div>

            <div class="col-12 col-md-4">
              <label class="form-label">الصنف</label>
              <select class="form-select searchable-select" name="name[]">
                ${itemOptions}
              </select>
            </div>

            <div class="col-6 col-md-2">
              <label class="form-label">السعر</label>
              <input class="form-control form-control-sm" type="number" step="0.0001" name="price[]">
            </div>

            <div class="col-6 col-md-2">
              <label class="form-label">الكمية</label>
              <input class="form-control form-control-sm quantity-input" type="number" step="0.1" name="quantity[]">
            </div>

            <div class="col-12 col-md-3">
              <label class="form-label">الإجمالي</label>
              <input class="form-control form-control-sm" type="number" name="total_price[]" readonly>
            </div>
          </div>
        </div>
      </div>
    `);

    $("#itemsContainer").append(row);
    initSelect2(row.find(".searchable-select"));
  });

  // ============================================================
  // 🟩 4) حذف صف
  // ============================================================
  $(document).on("click", ".removeItemBtn", function () {
    $(this).closest(".item-row").remove();
    calculateFooterTotals();
  });

  // ============================================================
  // 🟩 5) فحص المخزون عند تغيير الكمية أو المادة
  // ============================================================
  $(document).on(
    "input change",
    'input[name="quantity[]"], select[name="name[]"]',
    function () {
      const row = $(this).closest(".item-row");
      const quantityInput = row.find('input[name="quantity[]"]');
      const selectedOption = row.find('select[name="name[]"] option:selected');

      // ✅ قراءة المخزون سواء كان data-stock أو data-stock_qty
      const stockQty =
        parseFloat(selectedOption.data("stock_qty")) ||
        parseFloat(selectedOption.data("stock")) ||
        0;

      let quantity = parseFloat(quantityInput.val()) || 0;

      // ✅ في حال عدم تحديد مادة، لا تفحص المخزون
      if (!selectedOption.val()) {
        calculateFooterTotals();
        return;
      }

      if (quantity > stockQty) {
        Swal.fire({
          icon: "error",
          title: "كمية غير كافية",
          text: `الكمية المطلوبة (${quantity}) أكبر من المخزون المتاح (${stockQty}) للصنف: ${selectedOption.text()}`,
        });
        quantity = stockQty;
        quantityInput.val(quantity);
      }

      calculateFooterTotals();
    }
  );

  // ============================================================
  // 🟩 6) تحديث الإجماليات عند تغيير الخصم
  // ============================================================
  $("#totalDiscount").on("input", calculateFooterTotals);

  // ============================================================
  // 🟩 7) التحكم في عرض حقول الدفع (شيكات / كليك / فيزا)
  // ============================================================
  const isPaidSelect = document.getElementById("isPaid");
  const chequeFields = document.getElementById("chequeFields");
  const clickFields = document.getElementById("clickFields");
  const visaFields = document.getElementById("visaFields");

  function togglePaymentFields() {
    const value = isPaidSelect.value;
    chequeFields.classList.add("d-none");
    clickFields.classList.add("d-none");
    visaFields.classList.add("d-none");

    if (value === "2") chequeFields.classList.remove("d-none"); // شيكات
    else if (value === "3") clickFields.classList.remove("d-none"); // كليك
    else if (value === "4") visaFields.classList.remove("d-none"); // فيزا
  }

  isPaidSelect.addEventListener("change", togglePaymentFields);
  togglePaymentFields(); // ✅ استدعاء أولي لتحديد الحالة عند تحميل الصفحة

  // ============================================================
  // 🟩 8) حساب الإجماليات عند تحميل الصفحة أول مرة
  // ============================================================
  calculateFooterTotals();
});
