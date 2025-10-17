<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start(); 
}
$lang = $_COOKIE['language'] ?? 'en';
date_default_timezone_set('Asia/Amman');

define("Root", "https://smarthome.ezelsoft.com/");
define("PROJECT_ROOT", $_SERVER['DOCUMENT_ROOT'] . "/");

define("server", "localhost:3306");
define("username", "spideru2");
define("password", "KzMu8Fa!ns4!8>4~l/vSm5lU");
$_SESSION['selected_db'] ='spideru2_ezelsoft_smarthome';
define("db_name", $_SESSION['selected_db']);


define("UPLOAD",Root."uploads/");
define("INCLUDES",Root."includes/");
define("ACTIONS",Root."actions/");
define("PDF",Root."pdf/");
define("ASSETS",Root."assets/");
define("IMAGES",ASSETS."img/");
define("PROJECT_NAME", $lang === 'ar' ? "داوود الغاوي" : "Dawood AlGawi");
define("COMPANY_MOBILE",  "0777444845-0777729000");



define("no_user","اسم المستخدم أو كلمة المرور غير موجودة في النظام");
define("size_image_erorr","Size too large");
define("success_add_post","Post added successfully");
define("success_add_job","Post job added successfully");
define("success_add_wishlist","Wishlist added successfully");
define("success_add_shortlist","Shortlist added successfully");



define("SUCCESS_ADD", $lang === 'ar' ? "تم الاضافة بنجاح" : "Success Add");
define("VALUE_EXISTS", $lang === 'ar' ? "تم العثور على سجل مماثل مسبقًا" : "A similar record already exists"); 
define("SUCCESS_EDIT", $lang === 'ar' ? "تم التحرير بنجاح" : "Edited successfully");
define("SUCCESS_DELETE", $lang === 'ar' ? "تم الحذف بنجاح" : "Deleted successfully");
define("ERROR_ADD", $lang === 'ar' ? "حدث خطأ أثناء عملية الإضافة" : "An error occurred during the add operation");
define("DELETION_CONFIRM", $lang === 'ar' ? "هل أنت متأكد من أنك تريد الحذف؟" : "Are you sure you want to delete?");
define("DELETION_FAILED", $lang === 'ar' ? "فشل الحذف، مرتبط بملف واحد على الأقل" : "Deletion failed, linked to at least one file");
define("WRONG_PASSWORD", $lang === 'ar' ? "خطأ في اسم المستخدم أو كلمة المرور" : "Invalid username or password");
define("INACTIVE_ACCOUNT", $lang === 'ar' ? "حسابك غير مفعل حالياً. يرجى التواصل مع الإدارة" : "Your account is currently inactive. Please contact the administration");
define("GENERAL_ERROR", $lang === 'ar' ? "حدث خطأ لأسباب غير معروفة ، يرجى المحاولة مرة أخرى بعد 5 دقائق." : "An unknown error occurred, please try again in 5 minutes.");
define("confirm_button_text", $lang === 'ar' ? "حسناً" : "Okay");
define("passwords_not_match","Password does not match ");



define("no_activate","المستخدم غير مفعل");

date_default_timezone_set('Asia/Amman');

