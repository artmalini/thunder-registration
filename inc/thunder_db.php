<?php 
if ( !defined ( 'ABSPATH' ) ) {
    exit;
} // Exit if accessed directly
class Thunder_Db {
     protected $item = 1;
     private $itemdb = "wp_thunder_reg_users";
/*	    function get_option ($field, $default_value)
    {   
        global $wpdb;        
        if ($this->option_buffered_id == $this->item)
            $value = @$this->option_buffered_item->$field;
        else
        {  
           $myrows = $wpdb->get_results( "SELECT * FROM ".$wpdb->prefix.$this->table_items." WHERE id=".$this->item );
           $value = @$myrows[0]->$field;           
           $this->option_buffered_item = $myrows[0];
           $this->option_buffered_id  = $this->item;
        }
        if ($value == '' && $this->option_buffered_item->form_structure == '')
            $value = $default_value;
        return $value;
    }*/

	function get_dboption( $field, $default ) {
		global $wpdb;
		$option = $wpdb->get_results( "SELECT * FROM {$this->itemdb}" );
		if ( isset($option[0]->$field) ) {
			return $option[0]->$field;
		} else {
			return $default;
		}
	}

        function get_param($key)
    {
        if (isset($_GET[$key]) && $_GET[$key] != '')
            return $_GET[$key];
        else if (isset($_POST[$key]) && $_POST[$key] != '')
            return $_POST[$key];
        else 
            return '';
    }

	    function cleanJSON ($str)
    {
        $str = str_replace('&qquot;','"',$str);
        $str = str_replace('	',' ',$str);
        $str = str_replace("\n",'\n',$str);
        $str = str_replace("\r",'',$str);      
        return $str;        
    }

	function thunder_db_install() {
	    global $wpdb;
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

        // define('THUNDER_DB_SLUG', "wp_thunder_reg_users")
        //$wp_thunder_reg_users = "wp_thunder_reg_users";

        $results = $wpdb->get_results("SHOW TABLES LIKE wp_thunder_reg_users");
        if (!count($results))
        {
            $sql = "CREATE TABLE wp_thunder_reg_users (
	            id mediumint(12) NOT NULL AUTO_INCREMENT,	            
	            form_name VARCHAR(250) DEFAULT '' NOT NULL,
	            form_structure mediumtext,
	            login varchar(250) DEFAULT '' NOT NULL,

	            email varchar(250) DEFAULT '' NOT NULL,
	            emailverified varchar(255) DEFAULT '' NOT NULL,
				profilename varchar(150) DEFAULT '' NOT NULL,
				profilephoto varchar(150) DEFAULT '' NOT NULL,
				profileurl varchar(150) DEFAULT '' NOT NULL,
				gender varchar(7) NOT NULL,
				age varchar(10) NOT NULL,
				birthday int(11) NOT NULL,
				birthmonth int(11) NOT NULL,
				birthyear int(11) NOT NULL,
				country varchar(80) NOT NULL,
				phone varchar(60) NOT NULL,
				address varchar(255) NOT NULL,
				city varchar(50) NOT NULL,
				zip varchar(25) NOT NULL,
				user_registered datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
                UNIQUE KEY id (id)
            );";
            $wpdb->query($sql);
        }
        // insert initial data
        $count = $wpdb->get_var(  "SELECT COUNT(id) FROM wp_thunder_reg_users" );

        if (!$count) {
        	define('TH_DEFAULT_EMAIL', get_the_author_meta('user_email', get_current_user_id()));
        	$wpdb->insert( "wp_thunder_reg_users", array( 
        		'id' => 1,
        		'form_name' => 'Podval',
        		'form_structure' => $this->get_dboption("form_structure", THUNDER_FORM_STRUCTURE)
        	));
        }
	}

    /**
 * Cleans an url
 *
 * @param url to be cleaned
 */
 function clean_url( $url ) {
    $query_args = array(
        'enter',
        'updated',
        'created',
        'sent',
        'restore'
    );
    return esc_url( remove_query_arg( $query_args, $url ) );
}
   
   public function data_management() {
        global $wpdb;
       /* global $form_error;
        $form_error = new WP_error();*/ 
       $nonce = $_POST['ajaxnonce'];

        // проверяем nonce код, если проверка не пройдена прерываем обработку
        if ( ! wp_verify_nonce( $nonce, 'myajax-nonce' ) ) {
            die ( 'Stop!');
        }

        if ( $this->get_param('thunder_pform_psequence') && ! is_user_logged_in() )
        {
            $this->thunder_login();
            return;
        }

      /*  if ( 'POST' != $_SERVER['REQUEST_METHOD'] || ! isset( $_POST['thunder_pform_process'] ) ) {
           return;
        }
          if ( ! session_id() ) {
              session_start();
          }
          $_SESSION['thunder_code'] = '';
          setCookie('thunder_code', '', time()+36000,"/"); */
         
       
    }
    public function thunder_login () {
      global $form_error;

  
  /*    $nonce = $_POST['ajaxnonce'];

     //проверяем nonce код, если проверка не пройдена прерываем обработку
    if ( ! wp_verify_nonce( $nonce, 'myajax-nonce' ) ) {
        die ( 'Stop!');
    }*/

       $verify_nonce = wp_verify_nonce( $_POST['tlogin'], 'thunder_login_actions_post');
        if (!$verify_nonce) {

            echo 'Error: Form cannot be authenticated. Please contact our <a href="http://form2email.dwbooster.com/contact-us">support service</a> for verification and solution. Thank you.';
            return;

        } else {   

        // Sets the redirect url to the current page 
            $url = wp_get_referer();
           // $url = $this->clean_url( wp_get_referer() );

         $user = wp_signon();

            if ( is_wp_error($user) ) {
               // $url = esc_url( add_query_arg( 'enter', 'failed', $url ) );
                $error_string = $user->get_error_message();
                echo '<div id="message" class="thunder-notification error"><p>' . $error_string . '</p></div>';
            /*  if ( $form_error->get_error_code() ) {
                foreach( $form_error->get_error_messages() as $error ){
                  echo '<div><strong>Ошибка</strong>:'. $error .'</div>';
                }
              }*/
            } 

            

            wp_safe_redirect( $url );         
        }

    }

   
      public function save_options() {
        global $wpdb;

              // Enqueue media
    wp_enqueue_media();

    // User ID
            $user_id = ! empty( $_GET['user_id'] )
                ? (int) $_GET['user_id']
                : get_current_user_id();

        if ( $this->get_param('thunder_post_options') && is_admin() ) {

            $verify_nonce = wp_verify_nonce( $_POST['rsave'], 'thunder_update_actions_post');
            if ( ! $verify_nonce ) {
                echo 'Error: Form cannot be authenticated!!!!. Please contact our <a href="http://form2email.dwbooster.com/contact-us">support service</a> for verification and solution. Thank you.';
                return;
            } 
                                
            $this->item = $_POST["thunder_id"];


            if ( ! empty( $_FILES['thunder_file_avatar']['name']) ) {

                $this -> file_form($user_id);
            }

            foreach ($_POST as $item => $value)
                if (!is_array($value))
                    $_POST[$item] = stripcslashes($value);

            /*$this->add_field_verify($wpdb->prefix.$this->table_items, "fp_emailfrommethod", "VARCHAR(10)");
            $this->add_field_verify($wpdb->prefix.$this->table_items, "rep_enable", "VARCHAR(10)");
            $this->add_field_verify($wpdb->prefix.$this->table_items, "rep_days", "VARCHAR(10)");
            $this->add_field_verify($wpdb->prefix.$this->table_items, "rep_hour", "VARCHAR(10)");
            $this->add_field_verify($wpdb->prefix.$this->table_items, "rep_emails", "text");
            $this->add_field_verify($wpdb->prefix.$this->table_items, "rep_subject", "text");
            $this->add_field_verify($wpdb->prefix.$this->table_items, "rep_emailformat", "VARCHAR(10)");
            $this->add_field_verify($wpdb->prefix.$this->table_items, "rep_message", "text");
            $this->add_field_verify($wpdb->prefix.$this->table_items,'vs_text_submitbtn'," varchar(250) NOT NULL default ''");
            $this->add_field_verify($wpdb->prefix.$this->table_items,'vs_text_previousbtn'," varchar(250) NOT NULL default ''");
            $this->add_field_verify($wpdb->prefix.$this->table_items,'vs_text_nextbtn'," varchar(250) NOT NULL default ''");         */

            $data = array(
                    'form_name'  => $_POST['form_name'],
                    'form_structure' => $_POST['form_structure'],
                    'login' => $_POST['login'],
                    'email' => $_POST['email'],
                    'emailverified' => $_POST['emailverified'],
                    'profilename' => $_POST['profilename'],
                    'profilephoto' => $_POST['profilefhoto'],
                    'profileurl' => $_POST['profileurl'],
                    'gender' => $_POST['gender'],
                    'age' => $_POST['age'],
                    'birthday' => $_POST['birthday'],
                    'birthmonth' => $_POST['birthmonth'],
                    'birthyear' => $_POST['birthyear'],
                    'country' => $_POST['country'],
                    'phone' => $_POST['phone'],
                    'address' => $_POST['address'],
                    'city' => $_POST['city'],
                    'zip' => $_POST['zip']                
            );
            $wpdb->update ( 'wp_thunder_reg_users', $data, array( 'id' => $this->item ));
        } else { 
            return;
        }
    }

   /* *
    *
      for REGISTER form
    *
    **/

     public function file_form( $user_id = 0) {   
       // echo('lol');
     //die('xDDDDDDDD'); 
     //extract($_POST);      
            // need to be more secure since low privelege users can upload
        if ( false !== strpos( $_FILES['thunder_file_avatar']['name'], '.php' ) ) {
            //add_action( 'user_profile_update_errors', 'thunder_user_avatars_file_extension_error' );
            return;
        }

        // front end (theme my profile etc) support
        if ( ! function_exists( 'wp_handle_upload' ) ) {
            require_once( ABSPATH . 'wp-admin/includes/file.php' );
        }

        // Override avatar file-size
        //add_filter( 'upload_img_limit', 'thunder_user_avatars_upload_size_limit' );

        // Handle upload
        $avatar = wp_handle_upload( $_FILES['thunder_file_avatar'], array(
            'mimes' => array(
                'jpg|jpeg|jpe' => 'image/jpeg',
                'gif'          => 'image/gif',
                'png'          => 'image/png',
            ),
            'test_form' => false
            
        ) );

        //remove_filter( 'upload_size_limit', 'thunder_user_avatars_upload_size_limit' );

        // Failures
        if ( empty( $avatar['file'] ) ) {

            // Error feedback
            switch ( $avatar['error'] ) {
                case 'File type does not meet security guidelines. Try another.' :
                    //add_action( 'user_profile_update_errors', 'thunder_user_avatars_file_extension_error' );
                    return;
                default :
                    //add_action( 'user_profile_update_errors', 'thunder_user_avatars_generic_error' );
                    return;
            }
        }

        // Update
        $this -> thunder_user_avatars_update_avatar( $user_id, $avatar['url'] );    

        // Rating
      /*  if ( isset( $avatar['url'] ) || $avatar = get_user_meta( $user_id, 'thunder_user_avatars', true ) ) {
            if ( empty( $_POST['thunder_user_avatars_rating'] ) || ! array_key_exists( $_POST['thunder_user_avatars_rating'], thunder_user_avatars_get_ratings() ) ) {
                $_POST['thunder_user_avatars_rating'] = key( thunder_user_avatars_get_ratings() );
            }

            update_user_meta( $user_id, 'thunder_user_avatars_rating', $_POST['thunder_user_avatars_rating'] );
        }*/
    }

    public function thunder_user_avatars_update_avatar( $user_id, $media ) {

        // Delete old avatar
        $this -> thunder_user_avatars_delete_avatar( $user_id );

        // Setup empty meta array
        $meta_value = array();

        /*// Set the attachment URL
        if ( is_int( $media ) ) {
            $meta_value['media_id'] = $media;
            $media                  = wp_get_attachment_url( $media );
        }*/

        // Set full value to media URL
        $meta_value['full'] = esc_url_raw( $media );

        // Update user metadata
        update_user_meta( $user_id, 'thunder_user_avatars', $meta_value );
    }

    public function thunder_user_avatars_delete_avatar( $user_id = 0 ) {
        // Bail if no avatars to delete
        $old_avatars = (array) get_user_meta( $user_id, 'thunder_user_avatars', true );
        if ( empty( $old_avatars ) ) {
            return;
        }
       /* // Don't erase media library files
        if ( array_key_exists( 'media_id', $old_avatars ) ) {
            unset( $old_avatars['media_id'], $old_avatars['full'] );
        }*/

        // Are there files to delete?
        if ( ! empty( $old_avatars ) ) {
            $upload_path = wp_upload_dir();

            // Loop through avatars
            foreach ( $old_avatars as $old_avatar ) {

                // Use the upload directory
                $old_avatar_path = str_replace( $upload_path['baseurl'], $upload_path['basedir'], $old_avatar );

                // Maybe delete the file
                if ( file_exists( $old_avatar_path ) ) {
                    unlink( $old_avatar_path );
                }
            }
        }

        // Remove metadata
        delete_user_meta( $user_id, 'thunder_user_avatars' );        
    }









public function thunder_user_avatars_filter_get_avatar( $avatar = '', $id_or_email = 0, $size = 250, $default = '', $alt = '' ) {

    // Do some work to figure out the user ID
    if ( is_numeric( $id_or_email ) ) {
        $user_id = (int) $id_or_email;
    } elseif ( is_string( $id_or_email ) && ( $user = get_user_by( 'email', $id_or_email ) ) ) {
        $user_id = $user->ID;
    } elseif ( is_object( $id_or_email ) && ! empty( $id_or_email->user_id ) ) {
        $user_id = (int) $id_or_email->user_id;
    }

    // Bail if no user ID
    if ( empty( $user_id ) ) {
        return $avatar;
    }

    // Fetch avatars from usermeta, bail if no full option
    $user_avatars = get_user_meta( $user_id, 'thunder_user_avatars', true );
    if ( empty( $user_avatars['full'] ) ) {
        return $avatar;
    }


    // Alternate text
    if ( empty( $alt ) ) {
        $alt = get_the_author_meta( 'display_name', $user_id );
    }

    // Generate a new size
    if ( ! array_key_exists( $size, $user_avatars ) ) {

        // Set full size
        $user_avatars[ $size ] = $user_avatars['full'];

        // Allow rescaling to be toggled, usually for performance reasons
        if ( apply_filters( 'thunder_user_avatars_dynamic_resize', true ) ) :

            // Get the upload path (hard to trust this sometimes, though...)
            $upload_path = wp_upload_dir();

            // Get path for image by converting URL
            if ( ! isset( $avatar_full_path ) ) {
                $avatar_full_path = str_replace( $upload_path['baseurl'], $upload_path['basedir'], $user_avatars['full'] );
            }

            // Load image editor (for resizing)
            $editor = wp_get_image_editor( $avatar_full_path );
            if ( ! is_wp_error( $editor ) ) {

                // Attempt to resize
                $resized = $editor->resize( $size, $size, true );
                if ( ! is_wp_error( $resized ) ) {

                    $dest_file = $editor->generate_filename();
                    $saved     = $editor->save( $dest_file );

                    if ( ! is_wp_error( $saved ) ) {
                        $user_avatars[ $size ] = str_replace( $upload_path['basedir'], $upload_path['baseurl'], $dest_file );
                    }
                }
            }

            // Save updated avatar sizes
            update_user_meta( $user_id, 'thunder_user_avatars', $user_avatars );
        endif;
    }

    // URL corrections
    if ( 'http' !== substr( $user_avatars[ $size ], 0, 4 ) ) {
        $user_avatars[ $size ] = home_url( $user_avatars[ $size ] );
    }

    // Current?
    $author_class = is_author( $user_id )
        ? ' current-author'
        : '' ;

    // Setup the markup
    $avatar = "<img alt='" . esc_attr( $alt ) . "' src='" . esc_url( $user_avatars[ $size ] ) . "' class='avatar avatar-{$size}{$author_class} photo' height='{$size}' width='{$size}' />";

    // Filter & return
    return apply_filters( 'thunder_user_avatars', $avatar, $id_or_email, $size, $default, $alt );
}


/**
 * Remove user-avatars filter for the avatar list in options-discussion.php.
 *
 * @since 0.1.0
 */
public function thunder_user_avatars_avatar_defaults( $avatar_defaults = array() ) {
    remove_filter( 'get_avatar', array($this, 'thunder_user_avatars_filter_get_avatar') );
    return $avatar_defaults;
}






}//end class

?>