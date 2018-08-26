<?php
//require_once("../../../../../wp-load.php");
require_once("../../../../wp-load.php");

//global $thunder;

	//Get the proper uploads dir	
	/*function get_uploads_dir($user_id=0){
		$upload_dir = wp_upload_dir();
		$upload_base_dir = $upload_dir['basedir'] . 'thunder/';
		
		if ($user_id > 0) {
			return $upload_base_dir . $user_id . '/';
		}
		return $upload_base_dir;
	}
	
	//Return the uploads URL
	function get_uploads_url($user_id=0){
		$upload_dir = wp_upload_dir();		
		$upload_base_url = $upload_dir['baseurl'] . '/thunder/';

		if ($user_id > 0) {
			return $upload_base_url . $user_id . '/';
		}
		return $upload_base_url;
	}*/

// Secure file uploads
if( isset($_FILES["thunder_file"]) ) {
	if (empty($_FILES["thunder_file"]["name"])){
		die();
	} else {
		if ($_FILES["thunder_file"]["error"] > 0){
			die();
		} else {
			if(!is_uploaded_file($_FILES["thunder_file"]["tmp_name"])){
				die();
			} elseif( $_FILES["thunder_file"]["size"]>8388608 ){ //8 mb
				die();
			} else {
                $file_extension = strtolower(strrchr($_FILES["thunder_file"]["name"], "."));
                if( !in_array($file_extension, array( '.gif','.jpg','.png','.pdf','.txt','.zip','.doc','.jpeg'  )  ) ){
					die();
                }else{
					if(!is_array($_FILES["thunder_file"]["name"])) {
						$unique_id = uniqid();
						$ret = array();
						$target_file = get_uploads_dir() . $unique_id . $file_extension;
						move_uploaded_file( $_FILES["thunder_file"]["tmp_name"], $target_file );
						$ret['target_file'] = $target_file;
						$ret['target_file_uri'] = get_uploads_url() . basename($target_file);
						echo json_encode($ret);
					}
				}
			}
		}
	}
}

/*if(isset($_FILES["myfile"])){
	$ret = array();
	
//	This is for custom errors;	

	$error =$_FILES["myfile"]["error"];
	//You need to handle  both cases
	//If Any browser does not support serializing of multiple files using FormData() 
	if(!is_array($_FILES["myfile"]["name"])) //single file
		{
 	 	$fileName = $_FILES["myfile"]["name"];
 		move_uploaded_file($_FILES["myfile"]["tmp_name"],$output_dir.$fileName);
    	$ret[]= $fileName;
	}
	else  //Multiple files, file[]
	{
	  $fileCount = count($_FILES["myfile"]["name"]);
	  for($i=0; $i < $fileCount; $i++)
	  {
	  	$fileName = $_FILES["myfile"]["name"][$i];
		move_uploaded_file($_FILES["myfile"]["tmp_name"][$i],$output_dir.$fileName);
	  	$ret[]= $fileName;
	  }
	
	}
    echo json_encode($ret);
 }*/