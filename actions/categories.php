<?php
include "../includes/functions.php";

$categories_name = mb_convert_case(str_replace("'", "\'", $_POST['categories_name']), MB_CASE_TITLE);
$categories_description = mb_convert_case(str_replace("'", "\'", $_POST['categories_description']), MB_CASE_TITLE);

$primary_id          = $_POST['primary_id'];
$userId = $_SESSION["user_id"];


if(isset($_POST['add'])){

$add = "INSERT INTO `categories`(`name`, `description`, `user_id`)
                        VALUES ('$categories_name','$categories_description','$userId')";

if ($add) {
        insert_query($add);
        $_SESSION['SUCCESS_ADD'] = 1;
    }
} elseif(isset($_POST['edit'])){
       $update = "UPDATE `categories` 
           SET `name` = '$categories_name', 
               `description` = '$categories_description', 
               `user_id` = '$userId' 
                 WHERE `id` = '$primary_id'";

if ($update) {
        update_query($update);
        $_SESSION['SUCCESS_EDIT'] = 1;
    }


}

header('location: ' . $_SERVER['HTTP_REFERER']);
?>