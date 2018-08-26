<?php 

	add_action('wp_head','thunder_ajax');
	function thunder_ajax() { ?>
		<script type="text/javascript">
		var thunder_ajax = '<?php echo admin_url('admin-ajax.php'); ?>';
		</script>
	<?php
	}
		/* Get shortcode template */
	add_action('wp_ajax_nopriv_thunder_shortcode_template', 'thunder_shortcode_template');
	add_action('wp_ajax_thunder_shortcode_template', 'thunder_shortcode_template');
	function thunder_shortcode_template(){
		global $wp, $wp_query;
		extract($_POST);
		
		//add to $wp_query.
		ob_start();
		if (isset($_POST['usr'])){
		set_query_var('usr',  $_POST['usr'] );
		}

		echo do_shortcode( stripslashes( $tpl ) );
		$output['response'] = ob_get_contents();
		
		ob_end_clean();
		
		//$output=json_encode($output);
		//if(is_array($output)){ print_r($output); }else{ echo $output; } die;
		$output=json_encode($output);
		echo $output;
		die();		
	}	

	/* Crop user image upload */
	add_action('wp_ajax_nopriv_thunder_crop_picupload', 'thunder_crop_picupload');
	add_action('wp_ajax_thunder_crop_picupload', 'thunder_crop_picupload');
	function thunder_crop_picupload(){
		if (!isset($_POST['src'])) die();
		
		extract($_POST);

		//require_once dirname( __FILE__ ) . '/files/bfi_thumb.php';

		if ($filetype == 'ava_picture') {
		//$crop = thunder_url . "lib/timthumb.php?src=$src&w=$width&h=$height&a=c&amp;q=100";
		$size = thunder_get_option('avatar_size');
		$params = array(
		    'width' => $size,
		    'height' => $size,	
		    'crop' => true	    
		);
		$crop = bfi_thumb( $src, $params );
		
			
		//if (!$width) $crop = $src;
		$output['response'] = $crop;
		}
		
		if ($filetype == 'file'){
		$output['response'] = '<div class="thunder-file-input"><a href="'.$src.'" '.thunder_file_type_icon($src).'>'.basename( $src ).'</a></div>';
		}
		
		$output=json_encode($output);
		if(is_array($output)){ print_r($output); }else{ echo $output; } die;
	}

	add_action('wp_ajax_nopriv_remove_file', 'remove_file');
	add_action('wp_ajax_remove_file', 'remove_file');
	function remove_file() {
		if (!isset($_POST["filename"]) || $_POST['action'] != 'remove_file')
			die();

		extract($_POST);
		$output = '';

		$user_id = get_current_user_id();
		//$fileName =str_replace("..",".",$filename);
		$output_dir1 = get_bfi_dir($user_id);
		$filee1 = $output_dir1 . $thumb;

		//$output = $filee1;
		if (file_exists($filee1)) {
			// you'll have to use the path on your server to delete the image, not the url.			
        	unlink($filee1);
    	}

 		$output_dir2 = get_bfi_dir($user_id);
		$filee2 = $output_dir2 . $filename;

    	//$output .= '  ' + $filee2;
		if (file_exists($filee2)) {
			// you'll have to use the path on your server to delete the image, not the url.			
        	delete_user_meta($user_id, $key );
        	delete_user_meta($user_id, 'hide_'.$key );
        	unlink($filee2);
    	}
    	$output=json_encode($output);
    	echo $output;
    	die();
	}	

	/* Process a form */
	add_action('wp_ajax_nopriv_thunder_process_form', 'thunder_process_form');
	add_action('wp_ajax_thunder_process_form', 'thunder_process_form');
	function thunder_process_form(){	
		//global $thunder;	
		
	/*	if ( !isset($_POST['_mythunder_nonce']) ||
			!wp_verify_nonce($_POST['_mythunder_nonce'], '_mythunder_nonce_'.$_POST['template'].'_'.$_POST['$master_id'] ) ) {
		   die();
		}*/
		
		if (!isset($_POST) || $_POST['action'] != 'thunder_process_form')
			die();
			
		/*if ( !thunder_is_logged_in() && $_POST['tpl'] == 'edit')
			die();*/
		
		extract($_POST);
		foreach($_POST as $key=>$val) {
			$key = explode('-',$key);
			$key = $key[0];
			$form[$key] = $val;
		} extract($form);
		
		/* form action */
		switch($form_data) {
			case 'forgot':
				$output['error'] = '';
				
				if (!$username_or_email){
					$output['error']['username_or_email'] = __('You should provide your email or username.','thunder');
				} else {
				
					if (is_email($username_or_email)) {
						$user = get_user_by_email($username_or_email);
						$username_or_email = $user->user_login;
					}
				
					if (!username_exists($username_or_email)){
						$output['error']['username_or_email'] = __('There is not such user in our system.','thunder');
					} elseif ( !$thunder->can_reset_pass( $username_or_email ) ) {
						$output['error']['username_or_email'] = __('Resetting admin password is not permitted!','thunder');
					}
					
				}
				
				/* Form validation */
				/* Here you can process custom "errors" before proceeding */
				$output['error'] = apply_filters('thunder_form_validation', $output['error'], $form);
				
				/* email user with secret key and update
					his user meta */
				if (empty($output['error'])) {

					$user = get_user_by('login', $username_or_email);
					$uniquekey =  wp_generate_password(20, $include_standard_special_chars=false);
					
					update_user_meta( $user->ID, 'thunder_secret_key', $uniquekey);
					thunder_mail($user->ID, 'secretkey', $uniquekey);
					
					/*add_action('thunder_pre_form_message', 'thunder_msg_secret_key_sent');
					$tpl = stripslashes($tpl);
					$modded = str_replace('tpl="forgot"','tpl="forgot"', $tpl);
					$output['tpl'] = do_shortcode( $modded );*/
				
				}
				
				break;
				
			/* login */
			case 'login':
				
				$output['error'] = '';
				if (!$username_or_email){
					$output['error']['username_or_email'] = __('You should provide your email or username.','thunder');
				}
				if (!$user_password){
					$output['error']['user_password'] = __('You should provide your password.','thunder');
				}
				
				if (email_exists($username_or_email)) {
					$user = get_user_by('email', $username_or_email);
					$username_or_email = $user->user_login;
				}
				
				/* Form validation */
				/* Here you can process custom "errors" before proceeding */
				$output['error'] = apply_filters('thunder_login_validation', $output['error'], $form);
				
				if (empty($output['error']) && $username_or_email && $user_password) {				
					$creds = array();
					$creds['user_login'] = $username_or_email;
					$creds['user_password'] = $user_password;
					$creds['remember'] = true;
					//$output['error'] = 'ajax login ok';/////////////////////////
					$user = wp_signon( $creds, false );
					if ( is_wp_error($user) ) {
						if ( $user->get_error_code() == 'invalid_username') {
						$output['error']['username_or_email'] = __('Invalid email or username entered','thunder');
						} elseif ( $user->get_error_code() == 'incorrect_password') {
						$output['error']['user_password'] = __('The password you entered is incorrect','thunder');
						}
					}else {
						
						/* check the account is active first */
						if (is_pending( $user->ID )) {

							if (thunder_get_option('users_approve') === '2') {
								$output['custom_message'] = '<div class="thunder-message"><p>'.__('Your email is pending verification. Please activate your account.','thunder').'</p></div>';
							} else {
								$output['custom_message'] = '<div class="thunder-message"><p>'.__('Your account is currently being reviewed. Thanks for your patience.','thunder').'</p></div>';
							}
							wp_logout();
								
						} else {
					
							/* a good login */
							thunder_auto_login( $user->user_login, true );
												
						/*	if (isset($force_redirect_uri) && !empty($force_redirect_uri) ) {
							
								$output['redirect_uri'] = 'refresh';
								
							} else {
							
								
								// hook the redirect URI 
								$output['redirect_uri'] = apply_filters('thunder_login_redirect', $output['redirect_uri']);

							}*/
								if ( thunder_get_option('admin_page_after_login') ) {  //default 1 true 
									$output['redirect_uri'] = admin_url();
								} else {
								
									if (isset($redirect_uri) && !empty($redirect_uri) ) {
										$output['redirect_uri'] = $redirect_uri;
									} else {
										if (thunder_get_option('users_login_redirect') == 'no_redirect'){
											$output['redirect_uri'] = 'refresh';
										}
										if (thunder_get_option('users_login_redirect') == 'profile'){
											//$output['redirect_uri'] = $thunder->permalink();
										}
									}
								
								}
							
							/* super redirection */
							/*if (isset($global_redirect)){
								$output['redirect_uri'] = $global_redirect;
							}*/	
							} // active/pending	
						}
					} 	
				break;

	/*function permalink( $user_id=0, $request='profile', $option='userpro_pages' ) {
		$pages = get_option( $option );
		
		if (isset($pages[$request]) && $this->page_exists($pages[$request]) ){
			$page_id = $pages[ $request ];
		} else {
			$default = get_option('userpro_pages');
			$page_id = $default['profile'];
		}
		
		if ($user_id > 0) {
		
			$user = get_userdata( $user_id );
			$nice_url = userpro_get_option('permalink_type');
			if ($nice_url == 'ID') {
				$clean_user_login = $user_id;
			}
			if ($nice_url == 'username') {
				$clean_user_login = $user->user_login;
				$clean_user_login = str_replace(' ','-',$clean_user_login);
			}
			if ($nice_url == 'name'){
				$clean_user_login = $this->get_fullname_by_userid( $user_id );
			}
			if ($nice_url == 'display_name'){
				$clean_user_login = userpro_profile_data('display_name', $user_id);
				$clean_user_login = str_replace(' ','-',$clean_user_login);
			}

			/// append permalink 
			if ( get_option('permalink_structure') == '' ) {
				$link = add_query_arg( 'up_username', $clean_user_login, get_page_link($page_id) );
			} else {
				$link = trailingslashit ( trailingslashit( get_page_link($page_id) ) . $clean_user_login );
			}
		
		} else {
			$link = get_page_link($page_id);
		}

		return $link;
	}*/
				case 'register':

					$output['error'] = '';
					
					/* Form validation */
					/* Here you can process custom "errors" before proceeding */
					//$output['error'] = apply_filters('thunder_register_validation', $output['error'], $form);
				
					if ( isset($output['error']) && ( 
					
						(isset($user_login) && isset($user_email) && isset($user_password) ) || 
						(isset($user_login) && isset($user_email) ) ||
						(isset($user_email))
					
					) ) {
					
					if (isset($user_login) ) {
						$user_exists = username_exists( $user_login );
					} else {
						$user_exists = null;
						$user_login = $user_email;;
					}
					//new_user( $user_login, $user_password, $user_email, $form, $type='standard' );
					if ( $user_exists == null && (email_exists($user_email) == null) ) {
						
						if (!isset($user_password)) {
							$user_password = wp_generate_password( $length=12, $special_chars=false );
						}
							
						/* not auto approved? */
						if ( thunder_get_option('users_approve') !== '1') {
							
							/* require email validation */
							if (thunder_get_option('users_approve') === '2') {
							
								$user_id = new_user( $user_login, $user_password, $user_email, $form, $type='standard', $approved=0 );
								pending_email_approve( $user_id, $user_password, $form );
								
								/*add_action('thunder_pre_form_message', 'thunder_msg_activate_pending');
									function userpro_msg_activate_pending(){
										echo '<div class="userpro-message userpro-message-ajax"><p>'.__('Your email is pending verification. Please activate your account.','userpro').'</p></div>';
									}*/

								$tpl = stripslashes($tpl);
								$tpl = str_replace('tpl="register"','tpl="login"', $tpl);
								$output['tpl'] = do_shortcode( $tpl );
										/*ob_start();		
										$tpl = str_replace('tpl="register"','tpl="login"', $tpl);		
										echo do_shortcode( stripslashes( $tpl ) );
										$output['tpl'] = ob_get_contents();										
										ob_end_clean();	*/
								
							}
								
							 
							//require admin validation 
							if (thunder_get_option('users_approve') === '3') {
							
								$user_id = new_user( $user_login, $user_password, $user_email, $form, $type='standard', $approved=0 );
								pending_admin_approve( $user_id, $user_password, $form );
								
								//add_action('thunder_pre_form_message', 'thunder_msg_activate_pending_admin');
								$tpl = stripslashes($tpl);
								$tpl = str_replace('tpl="register"','tpl="login"', $tpl);
								$output['tpl'] = do_shortcode( $tpl );
								
							} 
							
						} else {
						
							//$user_id = new_user( $user_login, $user_password, $user_email, $form, $type='standard' );
							new_user( $user_login, $user_password, $user_email, $form, $type='standard' );

							/* auto login */
							if (thunder_get_option('after_register_autologin')) {
													
								$creds = array();
								$creds['user_login'] = $user_login;
								$creds['user_password'] = $user_password;
								$creds['remember'] = true;
								$user = wp_signon( $creds, false );
								
								if (isset($user->user_login)){
									
									thunder_auto_login( $user->user_login, true );
								
								}
								
								if ($redirect_uri) {
									$output['redirect_uri'] = $redirect_uri;
								} else {
									if (thunder_get_option('users_login_after_register') == 'no_redirect'){
										$output['redirect_uri'] = 'refresh';
									}
									if (thunder_get_option('users_login_after_register') == 'profile'){
										//$output['redirect_uri'] = $thunder->permalink();
									}
								}
								
								/* hook the redirect URI */
								//$output['redirect_uri'] = apply_filters('thunder_register_redirect', $output['redirect_uri']);
								$output['redirect_uri'] = 'refresh';
							
							/* manual login form */
							} else {
							
								//add_action('thunder_pre_form_message', 'thunder_msg_login_after_reg');
								$tpl = stripslashes($tpl);
								$tpl = str_replace('tpl="register"','tpl="login"', $tpl);
								$output['tpl'] = do_shortcode( $tpl );
							
							}							
						}						
					}					
				}
				
				break;
						/* editing */
			case 'edit':
			
				if ($user_id != get_current_user_id() && !current_user_can('manage_options') )
					die();
			
				thunder_update_user_profile( $user_id, $form, $action='ajax_save' );
				if (thunder_get_option('notify_admin_profile_save') && !current_user_can('manage_options') ){
					thunder_mail( $user_id , 'profileupdate', null, $form );
				}
				
				//add_action('thunder_pre_form_message', 'thunder_msg_profile_saved');
				
				if ($_POST['usr']){
				set_query_var('usr',  $_POST['usr'] );
				}

				
				$output['tpl'] = do_shortcode( stripslashes($tpl) );
				
				break;			
		}

		$output=json_encode($output);
		echo $output;
		die();
		//if(is_array($output)){ print_r($output); }else{ echo $output; } die;
	}


		/*add_filter('thunder_login_validation', 'thunder_antispam_check', 10, 2);
	add_filter('thunder_register_validation', 'thunder_antispam_check', 10, 2);
	add_filter('thunder_form_validation', 'thunder_antispam_check', 10, 2);
	function thunder_antispam_check($errors, $form) {
		extract($form);
		
		if (isset($antispam)) {
			if ( $form['antispam'] != $form['answer'] ){ 
				$errors['antispam'] = __('Incorrect answer. please try again.','userpro');
			}
		}
		
		return $errors;
	}*/






	/*function thunder_register_redirect($arg){
		$user_id = get_current_user_id();
		$user = get_userdata($user_id);
		
		$rules = get_option('userpro_redirects_register');
		if (is_array($rules) ) {
			$rules = array_reverse($rules);
			foreach($rules as $k => $rule){
			
				// Check user 
				if ($rule['user'] != ''){
					if ($user_id == $rule['user']) {
						$arg = $rule['url'];
						return $arg;
						exit;
					}
				}
				
				// Check user 
				if ($rule['field'] != ''){
					$test = userpro_profile_data( $rule['field'] , $user_id);
					if ($test == $rule['field_value'] ) {
						$arg = $rule['url'];
						return $arg;
						exit;
					}
				}
			
				// Check role 
				if ($rule['role'] != ''){
					$user_roles = $user->roles;
					$user_role = array_shift($user_roles);
					if ($user_role == $rule['role']) {
						$arg = $rule['url'];
						return $arg;
						exit;
					}
				}
		
			}
		}
		
		return $arg;

	}*/


/*add_action('wp_ajax_nopriv_userpro_fbconnect', 'userpro_fbconnect');
	add_action('wp_ajax_userpro_fbconnect', 'userpro_fbconnect');
	function userpro_fbconnect(){
		global $userpro;
		$output = '';
		
		if (!isset($_POST)) die();
		if ($_POST['action'] != 'userpro_fbconnect') die();
		
		if (!isset($_POST['id'])) die();
		
		extract($_POST);
	
		if (!isset($username) || $username == '' || $username == 'undefined') $username = $email;
		
		// Check if facebook uid exists 
		if (isset($id) && $id != '' && $id != 'undefined'){
			$users = get_users(array(
				'meta_key'     => 'userpro_facebook_id',
				'meta_value'   => $id,
				'meta_compare' => '='
			));
			if (isset($users[0]->ID) && is_numeric($users[0]->ID) ){
				$returning = $users[0]->ID;
				$returning_user_login = $users[0]->user_login;
			} else {
				$returning = '';
			}
		} else {
			$returning = '';
		}
		
		// If facebook uid exists 
		if ( $returning != '' ) {
				
				userpro_auto_login( $returning_user_login, true );
				
				if ($redirect == '') {
				$output['redirect_uri'] = 'refresh';
				} elseif ($redirect != 'profile') {
				$output['redirect_uri'] = $redirect;
				} else {
				$output['redirect_uri'] = $userpro->permalink();
				}
				$output['redirect_uri'] = apply_filters('userpro_login_redirect', $output['redirect_uri']);
			
		// Email is same, connect them together 
		} else if ( $email != '' && email_exists($email)) {
		
				$user_id = email_exists($email);
				$user = get_userdata($user_id);
				
				userpro_auto_login( $user->user_login, true );
				$userpro->update_fb_id($user_id, $id);
				
				if ($redirect == '') {
				$output['redirect_uri'] = 'refresh';
				} elseif ($redirect != 'profile') {
				$output['redirect_uri'] = $redirect;
				} else {
				$output['redirect_uri'] = $userpro->permalink();
				}
				$output['redirect_uri'] = apply_filters('userpro_login_redirect', $output['redirect_uri']);
		
		// This user already exists! connect them together 
		} else if ($username != '' && username_exists($username)) {
		
				$user_id = username_exists($username);
				$user = get_userdata($user_id);
				
				userpro_auto_login( $user->user_login, true );
				$userpro->update_fb_id($user_id, $id);
				
				if ($redirect == '') {
				$output['redirect_uri'] = 'refresh';
				} elseif ($redirect != 'profile') {
				$output['redirect_uri'] = $redirect;
				} else {
				$output['redirect_uri'] = $userpro->permalink();
				}
				$output['redirect_uri'] = apply_filters('userpro_login_redirect', $output['redirect_uri']);
		
		// FBID not found, email/user not found - fresh user 
		} else {

				$user_pass = wp_generate_password( $length=12, $include_standard_special_chars=false );
				$user_id = $userpro->new_user( $username, $user_pass, $email, $_POST, $type='facebook' );
				userpro_auto_login( $username, true );

				if ($redirect == '') {
				$output['redirect_uri'] = 'refresh';
				} elseif ($redirect != 'profile') {
				$output['redirect_uri'] = $redirect;
				} else {
				$output['redirect_uri'] = $userpro->permalink();
				}
				$output['redirect_uri'] = apply_filters('userpro_register_redirect', $output['redirect_uri']);
			
		}
		
		$output=json_encode($output);
		if(is_array($output)){ print_r($output); }else{ echo $output; } die;
	}*/

?>