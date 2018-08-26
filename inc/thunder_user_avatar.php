<?php 
require_once("../../../../../wp-load.php");
/*if ( false !== strpos( $_FILES['thunder_file_avatar']['name'], '.php' ) ) {
            //add_action( 'user_profile_update_errors', 'thunder_user_avatars_file_extension_error' );
            return;
        }

        // front end (theme my profile etc) support
        if ( ! function_exists( 'wp_handle_upload' ) ) {
            require_once( ABSPATH . 'wp-admin/includes/file.php' );
        }*/

        // Override avatar file-size
        //add_filter( 'upload_img_limit', 'thunder_user_avatars_upload_size_limit' );

        // Handle upload
        $rec = extract($_POST);
        
        echo $rec;

 ?>