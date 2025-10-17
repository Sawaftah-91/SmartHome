<?php
include("db_function.php");
function can($perm) {
    return in_array($perm, $_SESSION['permissions']);
}

function loadPermissions($conn, $role_id) {
    $perms = [];
    $sql = "SELECT p.name FROM role_permissions rp
            JOIN permissions p ON rp.permission_id = p.id
            WHERE rp.role_id = $role_id";
    $res = mysqli_query($conn, $sql);
    while ($row = mysqli_fetch_assoc($res)) {
        $perms[] = $row['name'];
    }
    return $perms;
}

function get_users($id = null) {
  global $conn;
  $array = array();

  if ($id !== null) {
      // استرجاع مستخدم واحد فقط بناءً على الـ $id
      $query = "SELECT users.*, lp1.name AS role_name, lp1.name_ar AS role_name_ar FROM users LEFT JOIN roles lp1 ON users.role_id = lp1.id WHERE users.id = $id";
  } else {
      // استرجاع كل المستخدمين
      $query = "SELECT users.*, lp1.name AS role_name, lp1.name_ar AS role_name_ar FROM users LEFT JOIN roles lp1 ON users.role_id = lp1.id ORDER BY users.name ASC";
  }
  $result = mysqli_query($conn, $query);

  while ($row = mysqli_fetch_object($result)) {
      $array[] = $row;
  }

  return $array;
}
function get_roles($id = null) {
    global $conn;
    $array = array();

    if ($id !== null) {
        $id = mysqli_real_escape_string($conn, $id);
        $query = "SELECT * FROM `roles` WHERE id = '$id' ORDER BY name ASC";
    } else {
        $query = "SELECT * FROM `roles` ORDER BY name ASC";
    }

    $result = mysqli_query($conn, $query);
    while ($row = mysqli_fetch_object($result)) {
        $array[] = $row;
    }

    return $array;
}
function get_permissions($id = null) {
    global $conn;
    $array = array();

    if ($id !== null) {
        $id = mysqli_real_escape_string($conn, $id);
        $query = "SELECT * FROM `permissions` WHERE id = '$id' ORDER BY name ASC";
    } else {
        $query = "SELECT * FROM `permissions` ORDER BY name ASC";
    }

    $result = mysqli_query($conn, $query);
    while ($row = mysqli_fetch_object($result)) {
        $array[] = $row;
    }

    return $array;
}
function get_item_tax($id = null) {
  global $conn;
  $array = array();

  if ($id !== null) {
      // استرجاع مستخدم واحد فقط بناءً على الـ $id
      $query = "SELECT * FROM `item_tax_rates` WHERE id = $id";
  } else {
      // استرجاع كل المستخدمين
      $query = "SELECT * FROM `item_tax_rates` ";
  }

  $result = mysqli_query($conn, $query);

  while ($row = mysqli_fetch_object($result)) {
      $array[] = $row;
  }

  return $array;
}

function get_units($id = null) {
  global $conn;
  $array = array();

  if ($id !== null) {
      // استرجاع مستخدم واحد فقط بناءً على الـ $id
      $query = "SELECT * FROM `units` WHERE id = $id";
  } else {
      // استرجاع كل المستخدمين
      $query = "SELECT * FROM `units` ";
  }

  $result = mysqli_query($conn, $query);

  while ($row = mysqli_fetch_object($result)) {
      $array[] = $row;
  }

  return $array;
}
function get_branding($id = null) {
  global $conn;
  $array = array();

  if ($id !== null) {
      // استرجاع مستخدم واحد فقط بناءً على الـ $id
      $query = "SELECT * FROM `branding` WHERE id = $id";
  } else {
      // استرجاع كل المستخدمين
      $query = "SELECT * FROM `branding` ";
  }

  $result = mysqli_query($conn, $query);

  while ($row = mysqli_fetch_object($result)) {
      $array[] = $row;
  }

  return $array;
}
function get_categories($id = null) {
  global $conn;
  $array = array();

  if ($id !== null) {
      // استرجاع مستخدم واحد فقط بناءً على الـ $id
      $query = "SELECT * FROM `categories` WHERE id = $id";
  } else {
      // استرجاع كل المستخدمين
      $query = "SELECT * FROM `categories` ";
  }

  $result = mysqli_query($conn, $query);

  while ($row = mysqli_fetch_object($result)) {
      $array[] = $row;
  }

  return $array;
}
function get_items($id = null) {
    global $conn;
    $array = array();

    // إذا تم تمرير ID، نستخدم شرط WHERE
    if ($id !== null) {
        $id = mysqli_real_escape_string($conn, $id);
        $query = "SELECT 
                      items.*,
                      lp1.name AS category_name,
                      lp2.name AS unit_name,
                      lp3.tax_label,
                      lp4.name AS branding_name,
                      IFNULL(w.quantity, 0) AS stock_qty
                  FROM items
                  LEFT JOIN categories lp1 ON items.category_id = lp1.id
                  LEFT JOIN units lp2 ON items.unit_id = lp2.id
                  LEFT JOIN item_tax_rates lp3 ON items.tax_rate = lp3.id
                  LEFT JOIN branding lp4 ON items.branding_id = lp4.id
                  LEFT JOIN warehouse w ON items.id = w.item_id WHERE id = '$id'";
    } else {
        $query = "SELECT 
                      items.*,
                      lp1.name AS category_name,
                      lp2.name AS unit_name,
                      lp3.tax_label,
                      lp4.name AS branding_name,
                      IFNULL(w.quantity, 0) AS stock_qty
                  FROM items
                  LEFT JOIN categories lp1 ON items.category_id = lp1.id
                  LEFT JOIN units lp2 ON items.unit_id = lp2.id
                  LEFT JOIN item_tax_rates lp3 ON items.tax_rate = lp3.id
                  LEFT JOIN branding lp4 ON items.branding_id = lp4.id
                  LEFT JOIN warehouse w ON items.id = w.item_id";
    }
    $result = mysqli_query($conn, $query);

    while ($row = mysqli_fetch_object($result)) {
        $array[] = $row;
    }

    return $array;
}
function get_item_card($id = null) {
    global $conn;
    $array = array();

    // إذا تم تمرير ID، نستخدم شرط WHERE
    if ($id !== null) {
        $id = mysqli_real_escape_string($conn, $id);
        $query = "SELECT 
                      im.id AS movement_id,
                      im.item_id,
                      i.name AS item_name,
                      i.barcode,
                      im.movement_date,
                      im.type AS movement_type,
                      im.quantity,
                      im.balance,
                      im.reference_type,
                      im.reference_id,
                      im.note
                  FROM item_movements AS im
                  INNER JOIN items AS i
                      ON im.item_id = i.id
                       WHERE im.item_id = $id
                  ORDER BY im.movement_date DESC";
    } else {
        $query = "SELECT 
                      im.id AS movement_id,
                      im.item_id,
                      i.name AS item_name,
                      i.barcode,
                      im.movement_date,
                      im.type AS movement_type,
                      im.quantity,
                      im.balance,
                      im.reference_type,
                      im.reference_id,
                      im.note
                  FROM item_movements AS im
                  INNER JOIN items AS i
                      ON im.item_id = i.id
                  ORDER BY im.movement_date DESC";
    }
    $result = mysqli_query($conn, $query);

    while ($row = mysqli_fetch_object($result)) {
        $array[] = $row;
    }

    return $array;
}
function get_suppliers($id = null) {
    global $conn;
    $array = array();

    if ($id !== null) {
        $id = mysqli_real_escape_string($conn, $id);
        $query = "SELECT * FROM `suppliers` WHERE id = '$id'";
    } else {
        $query = "SELECT * FROM `suppliers`";
    }

    $result = mysqli_query($conn, $query);
    while ($row = mysqli_fetch_object($result)) {
        $array[] = $row;
    }

    return $array;
}
function get_suppliers_accounts($id = null) { 
    global $conn;
    $data = [];

    // بناء شرط WHERE إذا تم تحديد مورد معين
    $condition = "";
    if ($id !== null) {
        $id = mysqli_real_escape_string($conn, $id);
        $condition = "WHERE s.id = '$id'";
    }

    // الاستعلام الرئيسي
    $query = "
        SELECT 
            s.id, 
            s.company_name, 
            COALESCE(SUM(pi.total), 0) AS total_invoices,
            pi.status
        FROM suppliers s
        LEFT JOIN purchase_invoices pi ON pi.supplier_id = s.id
        $condition
        GROUP BY s.id, s.company_name, pi.status
        ORDER BY s.company_name ASC
    ";

    $result = mysqli_query($conn, $query);

    while ($row = mysqli_fetch_object($result)) {
        $data[] = $row;
    }

    return $data;
}
function get_supplier_invoices($supplier_id) {
    global $conn;
    $invoices = [];

    $supplier_id = (int)$supplier_id;

    $query = "
        SELECT 
            id, 
            invoice_number, 
            invoice_date, 
            total, 
            status
        FROM purchase_invoices
        WHERE supplier_id = $supplier_id
        ORDER BY invoice_date DESC
    ";
    $result = mysqli_query($conn, $query);

    while ($row = mysqli_fetch_object($result)) {
        $invoices[] = $row;
    }

    return $invoices;
}

function get_customers_accounts($id = null) { 
    global $conn;
    $data = [];

    $condition = "";
    if ($id !== null) {
        $id = mysqli_real_escape_string($conn, $id);
        $condition = "WHERE c.id = '$id'";
    }

    $query = "
        SELECT 
            c.id, 
            c.customer_name, 
            COALESCE(SUM(si.total), 0) AS total_invoices,
            si.status
        FROM customers c
        LEFT JOIN sales_invoices si ON si.customer_id = c.id
        $condition
        GROUP BY c.id, c.customer_name, si.status
        ORDER BY c.customer_name ASC
    ";

    $result = mysqli_query($conn, $query);

    while ($row = mysqli_fetch_object($result)) {
        $data[] = $row;
    }

    return $data;
}
function get_customer_invoices($customer_id) {
    global $conn;
    $invoices = [];

    $customer_id = (int)$customer_id;

    $query = "
        SELECT 
            id, 
            invoice_number, 
            invoice_date, 
            total, 
            status
        FROM sales_invoices
        WHERE customer_id = $customer_id
        ORDER BY invoice_date DESC
    ";

    $result = mysqli_query($conn, $query);

    while ($row = mysqli_fetch_object($result)) {
        $invoices[] = $row;
    }

    return $invoices;
}
function get_receipts($id = null) {
    global $conn;
    $array = array();

    if ($id !== null) {
        $id = mysqli_real_escape_string($conn, $id);
        $query = "
            SELECT 
                rv.id,
                rv.customer_id,
                c.customer_name,
                rv.total_amount,
                rv.payment_method,
                rv.created_at
            FROM receipt_vouchers rv
            LEFT JOIN customers c ON c.id = rv.customer_id
            WHERE rv.id = '$id'
            ORDER BY rv.created_at DESC
        ";
    } else {
        $query = "
            SELECT 
                rv.id,
                rv.customer_id,
                c.customer_name,
                rv.total_amount,
                rv.payment_method,
                rv.created_at
            FROM receipt_vouchers rv
            LEFT JOIN customers c ON c.id = rv.customer_id
            ORDER BY rv.created_at DESC
        ";
    }

    $result = mysqli_query($conn, $query);

    while ($row = mysqli_fetch_object($result)) {
        $array[] = $row;
    }

    return $array;
}
function get_payments($id = null) {
    global $conn;
    $array = [];

    // 🔹 إذا تم تمرير رقم سند محدد
    if ($id !== null) {
        $id = (int)$id;
        $query = "SELECT 
                pv.id,
                pv.voucher_type,
                pv.total_amount,
                pv.payment_method,
                pv.payment_date,
                pv.note,
                pv.created_at,
                u.name AS created_by_name,

                s.company_name AS supplier_name,
                emp.name AS employee_name,
                ge.description AS expense_description,
                ec.name AS expense_category_name
            FROM payment_vouchers pv
            LEFT JOIN users u ON u.id = pv.created_by
            LEFT JOIN suppliers s ON s.id = pv.supplier_id
            LEFT JOIN users emp ON emp.id = pv.employee_id  
            LEFT JOIN general_expenses ge ON ge.voucher_id = pv.id
            LEFT JOIN expense_categories ec ON ec.id = ge.category_id
            WHERE pv.id = $id
            ORDER BY pv.created_at DESC
        ";
    } 
    // 🔹 أو جميع السندات بدون تحديد
    else {
        $query = "SELECT 
                pv.id,
                pv.voucher_type,
                pv.total_amount,
                pv.payment_method,
                pv.payment_date,
                pv.note,
                pv.created_at,
                u.name AS created_by_name,
                s.company_name AS supplier_name,
                emp.name AS employee_name,
                ge.description AS expense_description,
                ec.name AS expense_category_name

            FROM payment_vouchers pv
            LEFT JOIN users u ON u.id = pv.created_by
            LEFT JOIN suppliers s ON s.id = pv.supplier_id
            LEFT JOIN users emp ON emp.id = pv.employee_id  
            LEFT JOIN general_expenses ge ON ge.voucher_id = pv.id
            LEFT JOIN expense_categories ec ON ec.id = ge.category_id
            ORDER BY pv.created_at DESC";
    }
    $result = mysqli_query($conn, $query);

    if ($result) {
        while ($row = mysqli_fetch_object($result)) {
            $array[] = $row;
        }
    }

    return $array;
}







function get_customers($id = null) {
    global $conn;
    $array = array();

    if ($id !== null) {
        $id = mysqli_real_escape_string($conn, $id);
        $query = "SELECT * FROM `customers` WHERE id = '$id'";
    } else {
        $query = "SELECT * FROM `customers`";
    }

    $result = mysqli_query($conn, $query);
    while ($row = mysqli_fetch_object($result)) {
        $array[] = $row;
    }

    return $array;
}
function get_purchase_invoices($id = null) {
    global $conn;

    if ($id !== null) {
        $id = mysqli_real_escape_string($conn, $id);
        $query = "SELECT 
                      purchase_invoices.*,
                      lp1.supplier_name AS suppliers_name,
                      lp1.company_name AS supplier_company,
                      lp1.phone_number AS supplier_phone
                  FROM purchase_invoices
                  LEFT JOIN suppliers lp1 
                      ON purchase_invoices.supplier_id = lp1.id
                  WHERE purchase_invoices.id = '$id'
                  LIMIT 1"; // صف واحد فقط
        $result = mysqli_query($conn, $query);
        return mysqli_fetch_object($result); // ترجع كائن واحد
    } else {
        $query = "SELECT 
                  purchase_invoices.*,
                  lp1.supplier_name AS suppliers_name,
                  lp1.company_name  AS supplier_company
              FROM purchase_invoices
              LEFT JOIN suppliers lp1 
                  ON purchase_invoices.supplier_id = lp1.id
              ORDER BY purchase_invoices.created_at DESC";
        $result = mysqli_query($conn, $query);
        $array = [];
        while ($row = mysqli_fetch_object($result)) {
            $array[] = $row;
        }
        return $array; // ترجع مصفوفة
    }
}
function get_purchase_invoice_items($id = null) {
    global $conn;
    $array = array();

    if ($id !== null) {
        $id = mysqli_real_escape_string($conn, $id);
        $query = "SELECT purchase_invoice_items.*, lp1.name AS item_name, lp1.barcode AS item_barcode FROM purchase_invoice_items
         LEFT JOIN items lp1 ON purchase_invoice_items.item_id = lp1.id 
         WHERE purchase_invoice_items.invoice_id = '$id'";
    } else {
        $query = "SELECT purchase_invoice_items.*, lp1.name AS item_name, lp1.barcode AS item_barcode FROM purchase_invoice_items 
        LEFT JOIN items lp1 ON purchase_invoice_items.item_id = lp1.id 
        WHERE purchase_invoice_items.invoice_id";
    }

    $result = mysqli_query($conn, $query);
    while ($row = mysqli_fetch_object($result)) {
        $array[] = $row;
    }

    return $array;
}

function get_sales_invoices($id = null) {
    global $conn;

    if ($id !== null) {
        $id = mysqli_real_escape_string($conn, $id);
        $query = "SELECT 
                      sales_invoices.*,
                      c.customer_name,
                      c.phone_number,
                      u.name AS user_name
                  FROM sales_invoices
                  LEFT JOIN customers AS c 
                      ON sales_invoices.customer_id = c.id
                  LEFT JOIN users AS u
                      ON sales_invoices.user_id = u.id
                  WHERE sales_invoices.id = '$id' LIMIT 1"; // صف واحد فقط
        $result = mysqli_query($conn, $query);
        return mysqli_fetch_object($result); // ترجع كائن واحد
    } else {
        $query = "SELECT 
                      sales_invoices.*,
                      c.customer_name,
                      c.phone_number,
                      u.name AS user_name
                  FROM sales_invoices
                  LEFT JOIN customers AS c 
                      ON sales_invoices.customer_id = c.id
                  LEFT JOIN users AS u
                      ON sales_invoices.user_id = u.id
                  ORDER BY sales_invoices.created_at DESC";
        $result = mysqli_query($conn, $query);
        $array = [];
        while ($row = mysqli_fetch_object($result)) {
            $array[] = $row;
        }
        return $array; // ترجع مصفوفة
    }
    
}
function get_invoice_items($invoice_id) {
    global $conn;
    $invoice_id = mysqli_real_escape_string($conn, $invoice_id);
    $query = "SELECT * FROM sales_invoice_items WHERE invoice_id='$invoice_id'";
    $result = mysqli_query($conn, $query);
    $items = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $items[] = $row; // مصفوفة من الصفوف
    }
    return $items;
}

/*function get_sales_invoices($id = null, $from_date = null, $to_date = null) {
    global $conn;
    $array = array();

    $whereClauses = [];

    // شرط أساسي: الفاتورة غير مغلقة
    $whereClauses[] = "sales_invoices.is_closed = 0";

    // فلتر حسب id إذا تم تمريره
    if ($id !== null) {
        $id = mysqli_real_escape_string($conn, $id);
        $whereClauses[] = "sales_invoices.id = '$id'";
    }

    // فلتر حسب تواريخ إذا تم تمريرها
    if ($from_date !== null && $to_date !== null) {
        $from_date = mysqli_real_escape_string($conn, $from_date);
        $to_date = mysqli_real_escape_string($conn, $to_date);
        $whereClauses[] = "sales_invoices.invoice_date BETWEEN '$from_date' AND '$to_date'";
    }

    // بناء جملة WHERE
    $whereSQL = '';
    if (count($whereClauses) > 0) {
        $whereSQL = "WHERE " . implode(" AND ", $whereClauses);
    }

    $query = "SELECT sales_invoices.*,
                     lp1.customer_name AS customer_name
              FROM sales_invoices
              LEFT JOIN customers lp1 ON sales_invoices.customer_id = lp1.id
              $whereSQL 
              ORDER BY sales_invoices.created_at DESC";
    
    $result = mysqli_query($conn, $query);

    while ($row = mysqli_fetch_object($result)) {
        $array[] = $row;
    }

    return $array;
}*/

function get_sales_invoice_items($id = null) {
    global $conn;
    $array = array();

    if ($id !== null) {
        $id = mysqli_real_escape_string($conn, $id);
        $query = "SELECT 
  sii.*, 
  i.name AS item_name,
  i.barcode AS item_barcode
FROM sales_invoice_items AS sii
LEFT JOIN items AS i ON sii.item_id = i.id
                    WHERE sii.invoice_id = '$id'";
    } else {
        $query = "SELECT 
  sii.*, 
  i.name AS item_name,
  i.barcode AS item_barcode
FROM sales_invoice_items AS sii
LEFT JOIN items AS i ON sii.item_id = i.id;
";
    }

    $result = mysqli_query($conn, $query);
    while ($row = mysqli_fetch_object($result)) {
        $array[] = $row;
    }

    return $array;
}
// =============================================
// 🧾 دوال خاصة بسندات القبض (الطباعة)
// =============================================

function get_receipt_info($id) {
    global $conn;
    $id = (int)$id;

    $query = "
        SELECT 
            rv.*, 
            c.customer_name, 
            c.phone_number
        FROM receipt_vouchers rv
        LEFT JOIN customers c ON c.id = rv.customer_id
        WHERE rv.id = $id
        LIMIT 1
    ";

    $result = mysqli_query($conn, $query);
    if (!$result) {
        die('❌ خطأ في get_receipt_info: ' . mysqli_error($conn));
    }

    if (mysqli_num_rows($result) > 0) {
        return mysqli_fetch_object($result);
    }
    return null;
}


function get_receipt_invoices($receipt_id) {
    global $conn;
    $receipt_id = (int)$receipt_id;

    $query = "
        SELECT 
            si.id AS invoice_id,
            si.invoice_number,
            si.total,
            rvi.paid_amount,
            (si.total - (
                SELECT COALESCE(SUM(rvi2.paid_amount), 0)
                FROM receipt_voucher_invoices rvi2
                WHERE rvi2.invoice_id = si.id
            )) AS remaining_amount
        FROM receipt_voucher_invoices rvi
        INNER JOIN sales_invoices si ON si.id = rvi.invoice_id
        WHERE rvi.receipt_id = $receipt_id
        GROUP BY si.id, si.invoice_number, si.total, rvi.paid_amount
        ORDER BY si.invoice_number ASC
    ";

    $result = mysqli_query($conn, $query);
    if (!$result) {
        die('❌ خطأ في get_receipt_invoices: ' . mysqli_error($conn));
    }

    $data = [];
    while ($row = mysqli_fetch_object($result)) {
        $data[] = $row;
    }
    return $data;
}

function get_payment_info($id)
{
    global $conn;

    $query = "SELECT 
            pv.id,
            pv.voucher_type,
            pv.total_amount,
            pv.payment_method,
            pv.payment_date,
            pv.note,
            pv.created_at,
            pv.created_by,
            u.name AS created_by_name,
            s.company_name AS supplier_name,
            ge.description AS expense_description,
            ec.name AS expense_category_name,
            us.name AS employee_name
        FROM payment_vouchers pv
        LEFT JOIN users u ON u.id = pv.created_by
        LEFT JOIN suppliers s ON s.id = pv.supplier_id
        LEFT JOIN general_expenses ge ON ge.voucher_id = pv.id
        LEFT JOIN expense_categories ec ON ec.id = ge.category_id
        LEFT JOIN users us ON us.id = pv.employee_id
        WHERE pv.id = $id";

    $res = mysqli_query($conn, $query);
    return $res ? mysqli_fetch_object($res) : null;
}

function get_payment_invoices($voucher_id)
{
    global $conn;

    $query = "SELECT 
            pvi.invoice_id,
            pi.supplier_invoice_number,
            pi.invoice_date,
            pi.total,
            pvi.paid_amount,
            (
                pi.total - COALESCE((
                    SELECT SUM(pvi2.paid_amount)
                    FROM payment_voucher_invoices pvi2
                    WHERE pvi2.invoice_id = pi.id
                ), 0)
            ) AS remaining_amount
        FROM payment_voucher_invoices pvi
        LEFT JOIN purchase_invoices pi ON pi.id = pvi.invoice_id
        WHERE pvi.voucher_id = $voucher_id
        GROUP BY 
            pvi.invoice_id, 
            pi.supplier_invoice_number, 
            pi.invoice_date, 
            pi.total, 
            pvi.paid_amount
        ORDER BY pi.invoice_date DESC, pi.id DESC";
        
    $res = mysqli_query($conn, $query);
    $data = [];
    while ($row = mysqli_fetch_object($res)) {
        $data[] = $row;
    }
    return $data;
}




































function get_sales_invoices_z_report($date) {
    global $conn;
    $array = array();
        $query = "SELECT sales_invoices.*,
                     lp1.customer_name AS customer_name
              FROM sales_invoices
              LEFT JOIN customers lp1 ON sales_invoices.customer_id = lp1.id
                where invoice_date = '$date' AND is_closed=0
              ORDER BY sales_invoices.created_at DESC";
    $result = mysqli_query($conn, $query);
    while ($row = mysqli_fetch_object($result)) {
        $array[] = $row;
    }

    return $array;
}


function get_warehouse($id = null) {
    global $conn;
    $array = array();

    if ($id !== null) {
        $id = mysqli_real_escape_string($conn, $id);
        $query = "SELECT 
                      warehouse.*,
                      items.name AS item_name,
                      items.cost,
                      items.barcode,
                      categories.name AS category_name,
                      branding.name AS branding_name,
                      units.short_name AS unit_short_name,
                      ROUND(items.cost * warehouse.quantity, 3) AS total_cost_formatted
                  FROM warehouse
                  LEFT JOIN items ON warehouse.item_id = items.id
                  LEFT JOIN units ON items.unit_id = units.id
                  LEFT JOIN categories ON items.category_id = categories.id
                  LEFT JOIN branding ON items.branding_id = branding.id
                  WHERE warehouse.id = '$id'";
    } else {
        $query = "SELECT 
                      warehouse.*,
                      items.name AS item_name,
                      items.cost,
                      items.barcode,
                      categories.name AS category_name,
                      branding.name AS branding_name,
                      units.short_name AS unit_short_name,
                      ROUND(items.cost * warehouse.quantity, 3) AS total_cost_formatted
                  FROM warehouse
                  LEFT JOIN items ON warehouse.item_id = items.id
                  LEFT JOIN units ON items.unit_id = units.id
                  LEFT JOIN categories ON items.category_id = categories.id
                  LEFT JOIN branding ON items.branding_id = branding.id";
    }

    $result = mysqli_query($conn, $query);
    while ($row = mysqli_fetch_object($result)) {
        $array[] = $row;
    }

    return $array;
}


function get_daily_close($id = null) {
    global $conn;
    $array = array();

    if ($id !== null) {
        $id = mysqli_real_escape_string($conn, $id);
        $query = "SELECT * FROM `daily_close` WHERE id = '$id'";
    } else {
        $query = "SELECT * FROM `daily_close` ";
    }

    $result = mysqli_query($conn, $query);
    while ($row = mysqli_fetch_object($result)) {
        $array[] = $row;
    }

    return $array;
}
function get_cheque_by_invoice($invoice_id) {
  global $conn;
  $sql = "SELECT * FROM cheques WHERE purchase_invoice_id = '$invoice_id' LIMIT 1";
  $result = mysqli_query($conn, $sql);
  return mysqli_fetch_object($result);
}





















  function upload_img($file_org_name , $file_tmp_name){
    $target_dir =  PROJECT_ROOT . "uploads/";
    $file_name = basename($file_org_name) ;
    $ext = pathinfo($file_name, PATHINFO_EXTENSION);
    $target_file_name = uniqid(PROJECT_NAME, true) . '.' . $ext;
    $target_file = $target_dir .$target_file_name ;
  
    move_uploaded_file($file_tmp_name, $target_file);
       return $target_file_name;
  }

  function get_country() {
    global $conn;
   $array = array();
   $query = "SELECT * FROM `country`  order by id";
   $result=mysqli_query($conn,$query);
   while($row = mysqli_fetch_object($result)) {
         $array[] = $row;
     }
     return $array;
  }
  
  function get_customer() {
    global $conn;
   $array = array();
   $query = "SELECT * FROM `customer`  order by id ASC";
   $result=mysqli_query($conn,$query);
   while($row = mysqli_fetch_object($result)) {
         $array[] = $row;
     }
     return $array;
  }

  




function show_messages(){
    if(isset($_SESSION['check_form'])){
      echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
      <strong>'.check_form.'</strong>
      <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
      </div>';
    }
    unset($_SESSION['check_form']);

if(isset($_SESSION['INACTIVE_ACCOUNT'])){
      $message = addslashes(constant('INACTIVE_ACCOUNT'));
     $confirm_button_text = addslashes(confirm_button_text); 
    echo "<script>
          document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'info', // أو 'warning' إذا أحببت لون أكثر تحذيرًا
                text: '{$message}',
                confirmButtonText: '{$confirm_button_text}',
                didOpen: () => {
                    const audio = new Audio('../assets/sounds/inactive_account.mp3'); // صوت تنبيه خفيف
                    audio.play().catch(err => {
                        console.warn('Audio playback prevented:', err);
                    });
                }
            });
         });
        </script>";
      unset($_SESSION['INACTIVE_ACCOUNT']);
  }

  if(isset($_SESSION['SUCCESS_ADD'])){
      $message = addslashes(constant('SUCCESS_ADD'));
     $confirm_button_text = addslashes(confirm_button_text); 
    echo "
    <script>
     document.addEventListener('DOMContentLoaded', function() {
          Swal.fire({
            icon: 'success',
            text: '{$message}',
            confirmButtonText: '{$confirm_button_text}',
            didOpen: () => {
              const audio = new Audio('../assets/sounds/success.mp3');
              audio.play().catch(err => {
                console.warn('Audio playback prevented:', err);
              });
            }
          });
        });
    </script>";
      unset($_SESSION['SUCCESS_ADD']);
  }

     if(isset($_SESSION['SUCCESS_EDIT'])){
    $message = addslashes(constant('SUCCESS_EDIT'));
$confirm_button_text = addslashes(constant('confirm_button_text'));

    echo "<script>
     document.addEventListener('DOMContentLoaded', function() {
  Swal.fire({
    icon: 'success',
    text: '{$message}',
    confirmButtonText: '{$confirm_button_text}',
    didOpen: () => {
      const audio = new Audio('.../../assets/sounds/success.mp3');
      audio.play().catch(err => {
        console.warn('Audio playback prevented:', err);
      });
    }
  });
});
</script>";
unset($_SESSION['SUCCESS_EDIT']); 
  }
   if(isset($_SESSION['SUCCESS_DELETE'])){
    $message = addslashes(constant('SUCCESS_DELETE'));
$confirm_button_text = addslashes(constant('confirm_button_text'));

    echo "<script>
     document.addEventListener('DOMContentLoaded', function() {
  Swal.fire({
    icon: 'success',
    text: '{$message}',
    confirmButtonText: '{$confirm_button_text}',
    didOpen: () => {
      const audio = new Audio('.../../assets/sounds/success.mp3');
      audio.play().catch(err => {
        console.warn('Audio playback prevented:', err);
      });
    }
  });
});
</script>";
unset($_SESSION['SUCCESS_DELETE']); 
  }

if (isset($_SESSION['VALUE_EXISTS'])) {
   $message = addslashes(constant('VALUE_EXISTS'));
$confirm_button_text = addslashes(constant('confirm_button_text'));
    echo "
    <script>
    document.addEventListener('DOMContentLoaded', function() {
      Swal.fire({
        icon: 'warning',
        text: '{$message}',
        confirmButtonText: '{$confirm_button_text}',
        didOpen: () => {
          const audio = new Audio('.../../assets/sounds/warning.mp3'); // استخدم صوت تحذيري
          audio.play().catch(err => {
            console.warn('Audio playback prevented:', err);
          });
        }
      });
    });
    </script>";
    unset($_SESSION['VALUE_EXISTS']);
}
if (isset($_SESSION['DELETION_FAILED'])) {
   $message = addslashes(constant('DELETION_FAILED'));
$confirm_button_text = addslashes(constant('confirm_button_text'));
    echo "
    <script>
    document.addEventListener('DOMContentLoaded', function() {
      Swal.fire({
        icon: 'warning',
        text: '{$message}',
        confirmButtonText: '{$confirm_button_text}',
        didOpen: () => {
          const audio = new Audio('.../../assets/sounds/warning.mp3'); // استخدم صوت تحذيري
          audio.play().catch(err => {
            console.warn('Audio playback prevented:', err);
          });
        }
      });
    });
    </script>";
    unset($_SESSION['DELETION_FAILED']);
}
if (isset($_SESSION['GENERAL_ERROR'])) {
    $message = addslashes(constant('GENERAL_ERROR'));
$confirm_button_text = addslashes(constant('confirm_button_text'));
    echo "
    <script>
    document.addEventListener('DOMContentLoaded', function() {
      Swal.fire({
        icon: 'error',
        text: '{$message}',
        confirmButtonText: '{$confirm_button_text}',
        didOpen: () => {
          const audio = new Audio('.../../assets/sounds/error.mp3');
          audio.play().catch(err => {
            console.warn('Audio playback prevented:', err);
          });
        }
      });
    });
    </script>";
    unset($_SESSION['GENERAL_ERROR']); // إزالة الجلسة بعد العرض
}
if (isset($_SESSION['ERROR_ADD'])) {
    $message = addslashes(constant('ERROR_ADD'));
$confirm_button_text = addslashes(constant('confirm_button_text'));
    echo "
    <script>
    document.addEventListener('DOMContentLoaded', function() {
      Swal.fire({
        icon: 'error',
        text: '{$message}',
        confirmButtonText: '{$confirm_button_text}',
        didOpen: () => {
          const audio = new Audio('.../../assets/sounds/error.mp3');
          audio.play().catch(err => {
            console.warn('Audio playback prevented:', err);
          });
        }
      });
    });
    </script>";
    unset($_SESSION['ERROR_ADD']); // إزالة الجلسة بعد العرض
}

  
    if(isset($_SESSION['WRONG_PASSWORD'])){
       $message = addslashes(WRONG_PASSWORD);
     $confirm_button_text = addslashes(confirm_button_text); 
      echo "<script>
     document.addEventListener('DOMContentLoaded', function() {
    Swal.fire({
      icon: 'error',
      text: '{$message}',
      confirmButtonText: '{$confirm_button_text}',
      didOpen: () => {
        const audio = new Audio('.../../assets/sounds/wrong_password.mp3');
        audio.play().catch(err => {
          console.warn('Audio playback prevented:', err);
        });
      }
    });
  });
    </script>";
 unset($_SESSION['WRONG_PASSWORD']);
    }
   

    
    if(isset($_SESSION['size_image_erorr'])){
      echo '<div class="alert alert-danger border-2 d-flex align-items-center" role="alert">
      <div class="bg-danger me-3 icon-item"><span class="fas fa-times-circle text-white fs-3"></span></div>
      <p class="mb-0 flex-1">'.size_image_erorr.'</p>
      <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>';
    }
    unset($_SESSION['size_image_erorr']);
  
    if(isset($_SESSION['exist_name'])){
      echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
      <strong>'.exist_name.'</strong>
      <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
      </div>';
    }
    unset($_SESSION['exist_name']);
  
    if(isset($_SESSION['exist_field'])){
      echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
      <strong>'.exist_field.'</strong>
      <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
      </div>';
    }
    unset($_SESSION['exist_field']);
  
  
    if(isset($_SESSION['exist_ssid'])){
      echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
      <strong>'.exist_ssid.'</strong>
      <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
      </div>';
    }
    unset($_SESSION['exist_ssid']);
  
    if(isset($_SESSION['password_changed'])){
      echo '<div class="alert alert-success border-2 d-flex align-items-center" role="alert">
      <div class="bg-success me-3 icon-item"><span class="fas fa-check-circle text-white fs-3"></span></div>
      <p class="mb-0 flex-1">'.password_changed.'</p>
      <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>';
    }
    unset($_SESSION['password_changed']);

    if(isset($_SESSION['password_old_not_match'])){
      echo '<div class="alert alert-warning border-2 d-flex align-items-center" role="alert">
      <div class="bg-warning me-3 icon-item"><span class="fas fa-exclamation-circle text-white fs-3"></span></div>
      <p class="mb-0 flex-1">'.password_old_not_match.'</p>
      <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>';
    }
    unset($_SESSION['password_old_not_match']);
  
     if(isset($_SESSION['exist_user'])){
    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <strong>'.exist_user.'</strong>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
    </div>';
  }
  unset($_SESSION['exist_user']);
  

  if(isset($_SESSION['passwords_not_match'])){
    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <strong>'.passwords_not_match.'</strong>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
    </div>';
  }
  unset($_SESSION['passwords_not_match']);
  
   if(isset($_SESSION['no_user'])){
    echo '<div class="uk-alert uk-alert-danger" data-uk-alert>
    <a href="#" class="uk-alert-close uk-close"></a>
    '.no_user.'
</div>';
  }
  unset($_SESSION['no_user']);
  
  if(isset($_SESSION['exist_email'])){
    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <strong>'.exist_email.'</strong>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
    </div>';
  }
  unset($_SESSION['exist_email']);
  
  if(isset($_SESSION['exist_phone'])){
    echo '<div class="alert alert-info border-2 d-flex align-items-center" role="alert">
    <div class="bg-info me-3 icon-item"><span class="fas fa-info-circle text-white fs-3"></span></div>
    <p class="mb-0 flex-1">'.exist_phone.'</p>
    <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>';
  }
  unset($_SESSION['exist_phone']);

  if(isset($_SESSION['exist_transaction'])){
    echo '<div class="alert alert-info border-2 d-flex align-items-center" role="alert">
    <div class="bg-info me-3 icon-item"><span class="fas fa-info-circle text-white fs-3"></span></div>
    <p class="mb-0 flex-1">'.exist_transaction.'</p>
    <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>';
  }
  unset($_SESSION['exist_transaction']);
  
  if(isset($_SESSION['exist_inq'])){
    echo '<div class="alert alert-danger border-2 d-flex align-items-center" role="alert">
    <div class="bg-danger me-3 icon-item"><span class="fas fa-times-circle text-white fs-3"></span></div>
    <p class="mb-0 flex-1">'.exist_inq.'</p>
    <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>';
  }
  unset($_SESSION['exist_inq']);
  if(isset($_SESSION['exist_priced'])){
    echo '<div class="alert alert-danger border-2 d-flex align-items-center" role="alert">
    <div class="bg-danger me-3 icon-item"><span class="fas fa-times-circle text-white fs-3"></span></div>
    <p class="mb-0 flex-1">'.exist_priced.'</p>
    <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>';
  }
  unset($_SESSION['exist_priced']);

  if(isset($_SESSION['exist_file'])){
    echo '<div class="alert alert-danger border-2 d-flex align-items-center" role="alert">
    <div class="bg-danger me-3 icon-item"><span class="fas fa-times-circle text-white fs-3"></span></div>
    <p class="mb-0 flex-1">'.exist_file.'</p>
    <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>';
  }
  unset($_SESSION['exist_file']);

  if(isset($_SESSION['no_activate'])){
    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <strong>'.no_activate.'</strong>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
    </div>';
  }
  unset($_SESSION['no_activate']);
  
   if(isset($_SESSION['success_user'])){
    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
    <strong>'.success_user.'</strong>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
    </div>';
  }
  unset($_SESSION['success_user']);
  

  if(isset($_SESSION['success_add_post'])){
    echo ' <div class="alert alert-success border-2 d-flex align-items-center" role="alert">
    <div class="bg-success me-3 icon-item"><span class="fas fa-check-circle text-white fs-3"></span></div>
    <p class="mb-0 flex-1">'.success_add_post.'</p>
    <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>';
  }
  unset($_SESSION['success_add_post']);

  if(isset($_SESSION['success_add_wishlist'])){
    echo ' <div class="alert alert-success border-2 d-flex align-items-center" role="alert">
    <div class="bg-success me-3 icon-item"><span class="fas fa-check-circle text-white fs-3"></span></div>
    <p class="mb-0 flex-1">'.success_add_wishlist.'</p>
    <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>';
  }
  unset($_SESSION['success_add_wishlist']);

  if(isset($_SESSION['success_add_shortlist'])){
    echo ' <div class="alert alert-success border-2 d-flex align-items-center" role="alert">
    <div class="bg-success me-3 icon-item"><span class="fas fa-check-circle text-white fs-3"></span></div>
    <p class="mb-0 flex-1">'.success_add_shortlist.'</p>
    <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>';
  }
  unset($_SESSION['success_add_shortlist']);

  if(isset($_SESSION['success_add_job'])){
    echo '<div class="alert alert-success border-2 d-flex align-items-center" role="alert">
    <div class="bg-success me-3 icon-item"><span class="fas fa-check-circle text-white fs-3"></span></div>
    <p class="mb-0 flex-1">'.success_add_job.'</p>
    <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>';
  }
  unset($_SESSION['success_add_job']);
  
   if(isset($_SESSION['success_delete'])){
    echo '<div class="alert alert-success border-2 d-flex align-items-center" role="alert">
    <div class="bg-success me-3 icon-item"><span class="fas fa-check-circle text-white fs-3"></span></div>
    <p class="mb-0 flex-1">'.success_delete.'</p>
    <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>';
  }
  unset($_SESSION['success_delete']);
  
if(isset($_SESSION['deletion_failed'])){
    echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
            '.deletion_failed.'
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>';
  }
  unset($_SESSION['deletion_failed']);


    if(isset($_SESSION['yes_email_user'])){
    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
    <strong>'.yes_email_user.'</strong>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
    </div>';
  }
  unset($_SESSION['yes_email_user']);
   
   if(isset($_SESSION['no_email_user'])){
    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <strong>'.no_email_user.'</strong>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
    </div>';
  }
  unset($_SESSION['no_email_user']);
  
  
   if(isset($_SESSION['no_client'])){
    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <strong>'.no_client.'</strong>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
    </div>';
  }
  unset($_SESSION['no_client']);
  
  // unset($_SESSION['sql_appo']);
  
    if(isset($_SESSION['true'])){
    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
    <strong>'.send_email.'</strong>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
    </div>';
  }
  unset($_SESSION['true']);
  
   
  }
  ?>