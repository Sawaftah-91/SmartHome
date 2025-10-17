<?php
include "functions.php";

$table = $_REQUEST['type'];
$outputType = 'single'; // القيمة الافتراضية

switch ($table) {
    case "users":
        $sql = "Select * From users where id='" . $_POST['id'] . "'";
        break;
    case "items":
    $sql = "Select * From items where id='" . $_POST['id'] . "'";
    break;
    case "suppliers":
        $sql = "Select * From suppliers where id='" . $_POST['id'] . "'";
        break;
    case "customers":
        $sql = "Select * From customers where id='" . $_POST['id'] . "'";
        break;
    case "units":
        $sql = "Select * From units where id='" . $_POST['id'] . "'";
        break;
    case "branding":
        $sql = "Select * From branding where id='" . $_POST['id'] . "'";
        break;
    case "categories":
        $sql = "Select * From categories where id='" . $_POST['id'] . "'";
        break;
    case "roles":
        $sql = "Select * From roles where id='" . $_POST['id'] . "'";
    break;
    case "permissions":
        $sql = "Select * From permissions where id='" . $_POST['id'] . "'";
    break;
    case "role_permissions":
    global $conn;
    $array = array();
    $query = "SELECT * FROM `role_permissions` WHERE role_id ='" . $_POST['id'] . "'";
    $result = mysqli_query($conn, $query);
    while ($row = mysqli_fetch_assoc($result)) {
        $array[] = $row;
    }
    echo json_encode($array);
    exit(); // توقف التنفيذ بعد إرجاع النتيجة في هذه الحالة
    break;
}

$result_sql = select_query($sql);
$row = mysqli_fetch_assoc($result_sql);

if ($outputType === 'permissions') {
    echo json_encode($array);
} else {
    echo json_encode($row);
}
?>
