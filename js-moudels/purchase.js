$(document).ready(function () {
  function addItemRow() {
    const itemOptions = $("#itemOptions").html();
    const unitsOptions = $("#unitsOptions").html();

    // لا تضف صف جديد إذا الصف الأخير ناقص
    const lastRow = $("#itemsContainer .item-row").last();
    if (lastRow.length) {
      const item = lastRow.find('select[name="name[]"]').val();
      const price =
        parseFloat(lastRow.find('input[name="price[]"]').val()) || 0;
      const quantity =
        parseFloat(lastRow.find('input[name="quantity[]"]').val()) || 0;
      const unit = lastRow.find('select[name="units[]"]').val();

      if (!item || price <= 0 || quantity <= 0 || !unit) {
        Swal.fire({
          icon: "warning",
          title: "يرجى تعبئة جميع الحقول",
          text: "أكمل الصف الحالي قبل إضافة صف جديد.",
        });
        return;
      }
    }

    // صف جديد فارغ
    const row = `
    <tr class="item-row">
      <td style="min-width: 160px;">
        <select class="form-control select2" name="name[]">
          ${itemOptions}
        </select>
      </td>
      <td style="width: 120px;">
        <input type="number" step="0.0001" class="form-control" name="price[]">
      </td>
      <td style="width: 100px;">
        <input type="number" step="1" class="form-control" name="quantity[]">
      </td>
      <td style="width: 120px;">
        <select class="form-control select2" name="units[]">
          ${unitsOptions}
        </select>
      </td>
      <td style="width: 150px;">
        <input type="number" step="any" class="form-control" name="total_price[]" readonly>
      </td>
      <td style="width: 60px;">
        <button type="button" class="btn btn-danger btn-sm removeItemBtn" title="حذف">
          <span class="fas fa-trash" data-fa-transform="shrink-3"></span>
        </button>
      </td>
    </tr>
    `;

    $("#itemsContainer").append(row);
    const $select = $("#itemsContainer .item-row:last .select2");

    if ($select.hasClass("select2-hidden-accessible")) {
      $select.select2("destroy");
    }

    $select.select2({
      placeholder: "ابحث أو اختر...",
      allowClear: true,
      width: "100%",
    });
  }

  // عند اختيار مادة → تحقق من التكرار فقط اعرض رسالة
  $(document).on("change", 'select[name="name[]"]', function () {
    const selectedValue = $(this).val();
    const currentRow = $(this).closest("tr");

    let isDuplicate = false;

    $(".item-row").each(function () {
      const row = $(this);
      if (row.is(currentRow)) return; // تجاهل نفس الصف

      const value = row.find('select[name="name[]"]').val();
      if (value === selectedValue) {
        // وجدنا صف مكرر
        Swal.fire({
          icon: "info",
          title: "مادة مكررة",
          text: "لقد اخترت هذه المادة مسبقًا.",
          timer: 2000,
          showConfirmButton: false,
        });

        // امسح اختيار المادة الحالي اذا تريد، أو احذف السطر التالي لتعطيل التنظيف
        $(this).val("").trigger("change");

        isDuplicate = true;
        return false;
      }
    });

    if (!selectedValue) {
      currentRow
        .find(
          'input[name="price[]"], input[name="quantity[]"], input[name="total_price[]"]'
        )
        .val("");
      currentRow.find('select[name="units[]"]').val("").trigger("change");
    }
  });

  // حساب الإجمالي
  $(document).on(
    "input",
    'input[name="price[]"], input[name="quantity[]"]',
    function () {
      const row = $(this).closest(".item-row");
      const price = parseFloat(row.find('input[name="price[]"]').val()) || 0;
      const quantity =
        parseFloat(row.find('input[name="quantity[]"]').val()) || 0;
      const total = price * quantity;
      row.find('input[name="total_price[]"]').val(total.toFixed(3));
      calculateFooterTotals();
    }
  );

  $(document).on("click", ".removeItemBtn", function () {
    $(this).closest(".item-row").remove();
    calculateFooterTotals();
  });

  function calculateFooterTotals() {
    let totalQuantity = 0;
    let totalPrice = 0;
    $(".item-row").each(function () {
      totalQuantity +=
        parseFloat($(this).find('input[name="quantity[]"]').val()) || 0;
      totalPrice +=
        parseFloat($(this).find('input[name="total_price[]"]').val()) || 0;
    });

    $("#totalQuantity").val(totalQuantity.toFixed(3));

    // هنا الخصم كنسبة مئوية
    const discountPercent = parseFloat($("#totalDiscount").val()) || 0;
    const totalAfterDiscount = totalPrice * (1 - discountPercent / 100);

    $("#totalWithTax").val(totalAfterDiscount.toFixed(3));
  }

  $("#totalDiscount").on("input", calculateFooterTotals);

  // زر الإضافة
  $("#addItemBtn").click(addItemRow);

  // أول صف تلقائيًا
  addItemRow();

  // ✅ إظهار أو إخفاء حقول الشيك حسب نوع الدفع
  $("#isPaid").on("change", function () {
    const value = $(this).val();

    if (value == "2") {
      // إذا اختار المستخدم "شيكات" → أظهر حقول الشيك واجعلها مطلوبة
      $("#chequeDetails").removeClass("d-none");
      $("#chequeDetails input").attr("required", true);
    } else {
      // إذا اختار "نقداً" أو "ذمم" → أخفِ الحقول وأزل خاصية required
      $("#chequeDetails").addClass("d-none");
      $("#chequeDetails input").removeAttr("required");
      $("#chequeDetails input").val(""); // تفريغ الحقول
    }
  });
});
