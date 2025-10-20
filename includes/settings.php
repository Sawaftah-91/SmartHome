<?php
$settings = [];
$query = mysqli_query($conn, "SELECT `key`, `value`, `value_ar` FROM settings");
while ($row = mysqli_fetch_assoc($query)) {
    // لكل مفتاح إلا system_name اختار حسب اللغة
    if ($row['key'] !== 'system_name' && $lang === 'ar' && !empty($row['value_ar'])) {
        $settings[$row['key']] = $row['value_ar'];
    } else {
        $settings[$row['key']] = $row['value'];
    }
}

// system_name دائماً بالإنجليزي (value) بدون شرط لغة
if (!defined('SYSTEM_NAME')) define('SYSTEM_NAME', $settings['system_name'] ?? 'Default System');

if (!defined('from_mail')) define('from_mail', $settings['from_mail'] ?? 'default@example.com');
if (!defined('COMPANY_NAME')) define('COMPANY_NAME', $settings['company_name'] ?? 'Default Company');
if (!defined('COMPANY_LAND_PHONE')) define('COMPANY_LAND_PHONE', $settings['company_land_phone'] ?? '');
if (!defined('COMPANY_EMAIL_FINANCE')) define('COMPANY_EMAIL_FINANCE', $settings['company_email_finance'] ?? '');
if (!defined('COMPANY_ADDRESS')) define('COMPANY_ADDRESS', $settings['company_address'] ?? '');
if (!defined('header')) define('header', $settings['header'] ?? 'favicon.png');
if (!defined('default_img')) define('default_img', $settings['default_img'] ?? 'avatar.png');
if (!defined('default_files')) define('default_files', $settings['default_files'] ?? 'no_file.png');
if (!defined('COMPANY_LOGO')) define('COMPANY_LOGO', IMAGES .'logos/'. ($settings['company_logo'] ?? 'default_logo.png'));

