<?php
include("connect.php");
function select_query($sql){
	  global $conn ;
	  $result = mysqli_query($conn,$sql);
	  if($result)
		return $result ;
	  else
		error();
	return 0 ;
}

function check_existance($sql){
    global $conn ;
    $result = mysqli_query($conn,$sql);
    $num_rows = mysqli_num_rows($result);
   	if ( $num_rows == 0 ){
  	  return false ;
   	}
    else{
      return true ;
    }
}


function check_verified($sql){
	global $conn ;
	$result = mysqli_query($conn,$sql);
	$row = mysqli_fetch_assoc($result);
  return $row['verified'] ;
}

function get_record($sql){
	global $conn;
	$result = mysqli_query($conn,$sql);
	$row = mysqli_fetch_assoc($result);
  return $row ;
}

function get_language($id){
	global $conn ;
	$sql = "select language from app_user where user_id = " . $id ;
	$result = mysqli_query($conn,$sql);
	$row = mysqli_fetch_assoc($result);
	return $row['language'] ;
}

function get_user_email($id){
	global $conn ;
	$sql = "select email from user where id = " . $id ;
	$result = mysqli_query($conn,$sql);
	$row = mysqli_fetch_assoc($result);
	return $row['email'] ;
}


function get_count($sql){
	global $conn ;
	$result = mysqli_query($conn,$sql);
	if($result)
	return $result->num_rows ;
	else
	return 0 ;
}

function insert_query($sql){
	  global $conn ;
	  $result = mysqli_query($conn,$sql);
		if($result){
		return mysqli_insert_id($conn) ;
	  }else{
		error();
	 return 0 ;
	}
}

function update_query($sql){
	  global $conn ;
	  $result = mysqli_query($conn,$sql);
	  if($result)
	  return $result ;
	   else
			error();
	return 0 ;
}
function update_query_multi($query) {
    global $conn; // تأكد من استخدام الاتصال الصحيح بقاعدة البيانات
    if (mysqli_multi_query($conn, $query)) {
        do {
            // للتحقق من استكمال جميع الاستعلامات بنجاح
            if ($result = mysqli_store_result($conn)) {
                mysqli_free_result($result);
            }
        } while (mysqli_next_result($conn));
        
        echo "تم تحديث السجلات بنجاح";
    } else {
        echo "خطأ في التحديث: " . mysqli_error($conn);
    }
}


function delete_query($sql){
	  global $conn ;
	  $result = mysqli_query($conn,$sql);
	  if($result){
		  return $result ;
	  }
	   else{
		error();
	return 0 ;
}
}

function error(){
	  global $conn ;
	  echo 'Error happened while contacting database..'  ;
	  if(DEBUG)
		 echo 'error code is: ' . $conn->error	   ;
	  die();
}



?>
