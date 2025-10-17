$(document).ready(function () {
  let currentLang = localStorage.getItem("lang") || "en";

  // 🟢 تعديل: أضفت باراميتر resetForm للتحكم هل أعمل reset أو لا
  function setupModal(key, submitName, modalId, resetForm = true) {
    if (resetForm) {
      // إعادة تعيين الفورم (يستعمل فقط في الإضافة)
      $(modalId).find(".form_modal").trigger("reset");
    }

    const translatedText = translations[currentLang][key] || key;

    // تحديث عنوان المودال
    $(modalId)
      .find(".header-modal")
      .html(`<h4 class='mb-1 header-modal'>${translatedText}</h4>`);

    if (submitName) {
      $(modalId).find(".submit_click").attr("name", submitName);
    }
  }

  // تفريغ محتوى الفورم عند إغلاق أي مودال
  $(".modal").on("hidden.bs.modal", function () {
    $(this).find("form").trigger("reset");
  });

  // عند الضغط على أزرار الحذف
  $(document).on("click", ".del_click", function () {
    var id = $(this).attr("id");
    var tbl = $(this).attr("table");
    $("#ids").val(id);
    $("#table_name").val(tbl);
    const modalId = $(this).attr("data-bs-target");
    setupModal("delete", "delete", modalId);
  });

  // زر الإضافة: 🟢 هنا نخلي resetForm = true (حتى الفورم يفتح فاضي)
  $(".add_click").on("click", function () {
    const modalId = $(this).attr("data-bs-target");
    setupModal("add", "add", modalId, true);
  });

  // زر التعديل: 🟢 هنا نخلي resetForm = false (حتى الفورم يحتفظ بالبيانات الموجودة)
  $(".edit_click").on("click", function () {
    const modalId = $(this).attr("data-bs-target");
    setupModal("edit", "edit", modalId, false);
  });

  // زر تعديل الصلاحيات: نفس فكرة التعديل (لا تفضي الفورم)
  $(".edit_permissions").on("click", function () {
    const modalId = $(this).attr("data-bs-target");
    setupModal("edit", "edit_permissions", modalId, false);
  });

  // منع إعادة إرسال الفورم عند الإرسال
  $("body").on("submit", "form", function () {
    $(this).submit(function () {
      return false;
    });
    return true;
  });
});
