<?php


	/* Get fields as arrays */
	function thunder_fields_group_by_template( $tpl, $group='default' ) {
		$array = get_option("thunder_fields_groups");
		if (isset($array[$tpl][$group]))
			if (count($array[$tpl][$group]) > 0)
				return (array)$array[$tpl][$group];
		return array('');
	}
	
	/* Get specific fields only */
	function thunder_get_fields( $fields=array() ) {
		$array = get_option("thunder_fields_restore");
		return array_intersect_key($array, array_flip($fields));
	}
	
	/* Get all field keys */
	function thunder_retrieve_metakeys() {
		$fields = get_option('thunder_fields');
		$array = array_keys($fields);
		return $array;
	}
	
	/* Retrieves a field */
	function thunder_add_field($field, $zone, $label, $hideable=0, $hidden=0, $required=0) {
		$fields = get_option('thunder_fields');
		$array = $fields[$field];
		$array['zone'] = $zone;
		$array['label'] = $label;
		$array['hideable'] = $hideable;
		$array['hidden'] = $hidden;
		$array['required'] = $required;		
		return $array;
	}
	
	/* Assign a section */
	function thunder_add_section($name, $collapsible=0, $collapsed=0) {
		$array = array(
			'section' => $name,
			'collapsible' => $collapsible,
			'collapsed' => $collapsed
		);
		return $array;
	}

		/******************************************
	Check that page exists
	******************************************/
	function page_exists($id){
		$page_data = get_page($id);
		if (isset($page_data->post_status)){
		if($page_data->post_status == 'publish'){
			return true;
		}
		}
		return false;
	}

	/******************************************
	Get permalink for user
	******************************************/
	function permalink( $user_id=0, $request='user', $option='thunder_links' ) {
		$pages = get_option( $option );
		
		if (isset($pages[$request]) && page_exists($pages[$request]) ){
			$page_id = $pages[ $request ];
		} else {
			$default = get_option('thunder_links');
			$page_id = $default['user'];
		}
		
		if ($user_id > 0) {
		
			$user = get_userdata( $user_id );
			$nice_url = thunder_get_option('permalink_type');
			if ($nice_url == 'ID') {
				$clean_user_login = $user_id;
			}
			if ($nice_url == 'username') {
				$clean_user_login = $user->user_login;
				$clean_user_login = str_replace(' ','-',$clean_user_login);
			}
			if ($nice_url == 'name'){
				$clean_user_login = get_fullname_by_userid( $user_id );
			}
			if ($nice_url == 'display_name'){
				$clean_user_login = thunder_profile_data('display_name', $user_id);
				$clean_user_login = str_replace(' ','-',$clean_user_login);
			}

			/* append permalink */
			if ( get_option('permalink_structure') == '' ) {
				$link = add_query_arg( 'usr', $clean_user_login, get_page_link($page_id) );
			} else {
				$link = trailingslashit ( trailingslashit( get_page_link($page_id) ) . $clean_user_login );
			}
		
		} else {
			$link = get_page_link($page_id);
		}

		return $link;
	}

		/******************************************
	Get full name of user by ID
	******************************************/
	function get_fullname_by_userid( $user_id ) {
		$first_name = get_user_meta($user_id, 'first_name', true);
		$last_name = get_user_meta($user_id, 'last_name', true);
		$first_name = str_replace(' ', '_', $first_name);
		$last_name = str_replace(' ', '_', $last_name);
		$name = $first_name . '-' . $last_name;
		return $name;
	}

		/******************************************
	from ID to member arg
	******************************************/
	function id_to_member( $user_id ) {
		$res = '';
		$nice_url = thunder_get_option('permalink_type');
		$user = get_userdata( $user_id );
		if ($nice_url == 'ID') $res = $user_id;
		if ($nice_url == 'username') $res = $user->user_login;
		if ($nice_url == 'name') {
			$res = get_fullname_by_userid( $user_id );
		}
		if ($nice_url == 'display_name'){
			$res = thunder_profile_data('display_name', $user_id);
		}
		if ($res != '')
			return $res;
	}
	/******************************************
	Create a new user
	******************************************/

	/*<div class="userpro userpro-672 userpro-float" data-template="register" data-max_width="480px" data-uploads_dir="http://resora/wp-content/uploads/userpro/" data-default_avatar_male="http://resora/wp-content/plugins/userpro-custom-master/img/default_avatar_male.jpg" data-default_avatar_female="http://resora/wp-content/plugins/userpro-custom-master/img/default_avatar_female.jpg" data-layout="float" data-margin_top="0" data-margin_bottom="30px" data-align="center" data-skin="default" data-required_text="This field is required" data-password_too_short="Your password is too short" data-passwords_do_not_match="Passwords do not match" data-password_not_strong="Password is not strong enough" data-keep_one_section_open="1" data-allow_sections="1" data-permalink="http://resora/" data-field_icons="1" data-profile_thumb_size="80" data-register_heading="Register an Account" data-register_side="Already a member?" data-register_side_action="login" data-register_button_action="login" data-register_button_primary="Register" data-register_button_secondary="Login" data-register_group="default" data-register_redirect="0" data-type="0" data-login_heading="Login" data-login_side="Forgot your password?" data-login_side_action="reset" data-login_button_action="register" data-login_button_primary="Login" data-login_button_secondary="Create an Account" data-login_group="default" data-login_redirect="0" data-delete_heading="Delete Profile" data-delete_side="Undo, back to profile" data-delete_side_action="view" data-delete_button_action="view" data-delete_button_primary="Confirm Deletion" data-delete_button_secondary="Back to Profile" data-delete_group="default" data-reset_heading="Reset Password" data-reset_side="Back to Login" data-reset_side_action="login" data-reset_button_action="change" data-reset_button_primary="Request Secret Key" data-reset_button_secondary="Change your Password" data-reset_group="default" data-change_heading="Change your Password" data-change_side="Request New Key" data-change_side_action="reset" data-change_button_action="reset" data-change_button_primary="Change my Password" data-change_button_secondary="Do not have a secret key?" data-change_group="default" data-list_heading="Latest Members" data-list_per_page="5" data-list_sortby="registered" data-list_order="desc" data-list_users="0" data-list_group="default" data-list_thumb="50" data-list_showthumb="1" data-list_showsocial="1" data-list_showbio="0" data-list_verified="0" data-list_relation="or" data-online_heading="Who is online now" data-online_thumb="30" data-online_showthumb="1" data-online_showsocial="0" data-online_showbio="0" data-online_mini="1" data-online_mode="vertical" data-edit_button_primary="Save Changes" data-edit_group="default" data-view_group="default" data-social_target="_blank" data-social_group="default" data-card_width="250px" data-card_img_width="250" data-card_showbio="1" data-card_showsocial="1" data-link_target="_blank" data-error_heading="An error has occured" data-memberlist_v2="1" data-memberlist_v2_pic_size="86" data-memberlist_v2_fields="age,gender,country" data-memberlist_v2_bio="1" data-memberlist_v2_showbadges="1" data-memberlist_v2_showname="1" data-memberlist_v2_showsocial="1" data-memberlist_pic_size="120" data-memberlist_pic_topspace="15" data-memberlist_pic_sidespace="30" data-memberlist_pic_rounded="1" data-memberlist_width="100%" data-memberlist_paginate="1" data-memberlist_paginate_top="1" data-memberlist_paginate_bottom="1" data-memberlist_show_name="1" data-memberlist_popup_view="0" data-memberlist_withavatar="0" data-memberlist_verified="0" data-memberlist_filters="0" data-memberlist_default_search="1" data-per_page="12" data-sortby="registered" data-order="desc" data-relation="and" data-search="1" data-show_social="1" data-registration_closed_side="Existing member? login" data-registration_closed_side_action="login" data-facebook_redirect="profile" data-logout_redirect="0" data-postsbyuser_num="12" data-postsbyuser_types="post" data-postsbyuser_mode="grid" data-postsbyuser_thumb="50" data-postsbyuser_showthumb="1" data-publish_heading="Add a New Post" data-publish_button_primary="Publish" data-following_heading="Following" data-followers_heading="Followers" data-activity_heading="Recent Activity" data-activity_all="0" data-activity_per_page="10" data-activity_side="refresh" data-activity_user="0" data-emd_filters="1" data-emd_thumb="200" data-emd_social="1" data-emd_bio="1" data-emd_fields="first_name,last_name,gender,country" data-emd_layout="masonry" data-emd_per_page="20" data-emd_col_width="22%" data-emd_col_margin="2%" data-emd_accountstatus="Search by account status" data-emd_photopreference="Photo Preference" data-emd_country="Search by Country,dropdown" data-emd_gender="Gender,radio" data-emd_paginate="1" data-emd_paginate_top="1" data-id="1">*/
	/******************************************
	Assign default role after registration
	******************************************/	

	/**
	Sends mail
	This function manage the Mail stuff sent by plugin
	to users
	**/

	// pending_admin_approve pending_email_approve 
	//Prepares a user for pending admin verify
	//
	function pending_admin_approve($user_id, $user_pass, $form) {
		$new_account_salt = wp_generate_password( $length=20, $include_standard_special_chars=false );
		update_user_meta($user_id, '_account_status', 'pending_admin');
		update_user_meta($user_id, '_pending_pass', $user_pass);
		update_user_meta($user_id, '_pending_form', $form);
		thunder_mail($user_id, 'pendingapprove', null, $form );
	}


	function pending_email_approve($user_id, $user_pass, $form) {
		$new_account_salt = wp_generate_password( $length=20, $include_standard_special_chars=false );
		update_user_meta($user_id, '_account_verify', $new_account_salt);
		update_user_meta($user_id, '_account_status', 'pending');
		update_user_meta($user_id, '_pending_pass', $user_pass);
		update_user_meta($user_id, '_pending_form', $form);
		thunder_mail($user_id, 'verifyemail', null, $form );
	}
		/******************************************
	Create a validation URL automatically
	******************************************/
	function create_validate_url($user_id) {
		$salt = get_user_meta($user_id, '_account_verify', true);
		if ($salt && strlen($salt) == 20) {
		$url = home_url() . '/';
		$url = add_query_arg( 'act', 'verify_account', $url );
		$url = add_query_arg( 'user_id', $user_id, $url );
		$url = add_query_arg( 'user_verification_key', $salt, $url );
		return $url;
		}
	}
		/******************************************
	Prepares a user for pending email verify           
	******************************************/
	add_action('init', 'process_email_approve');    ///////////////////QQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQ

	function process_email_approve(){
		if (isset($_GET['act']) && isset($_GET['user_id']) && isset($_GET['user_verification_key'])) {
			if ($_GET['act'] == 'verify_account' && (int)$_GET['user_id'] && strlen($_GET['user_verification_key']) == 20) {
				
				// valid request, try to validate user
				if ( is_pending($_GET['user_id']) ){
					$salt_check = get_user_meta($_GET['user_id'], '_account_verify', true);
					if ($salt_check == $_GET['user_verification_key']) {
						activate_user( $_GET['user_id'] );
						//wp_redirect( add_query_arg('accountconfirmed', 'true', permalink() ) );
						wp_redirect( add_query_arg('accountconfirmed', 'true', home_url() ) );
						exit();
					}
				}
				
			}
		}
		
		if (isset($_GET['accountconfirmed']) && $_GET['accountconfirmed'] == 'true') {
			//add_action('userpro_pre_form_message', 'userpro_msg_account_validated', 999);
		}
	}
		/******************************************
	Activate a user
	******************************************/
	function activate_user($user_id, $user_login = null) {
		if ($user_login != ''){
			$user = get_user_by('login', $user_login);
			$user_id = $user->ID;
		}
		delete_user_meta($user_id, '_account_verify');
		update_user_meta($user_id, '_account_status', 'active');
		
		$password = get_user_meta($user_id, '_pending_pass', true);
		$form = get_user_meta($user_id, '_pending_form', true);
		thunder_mail($user_id, 'newaccount', $password, $form );
		//do_action('userpro_after_new_registration', $user_id);
		
		delete_user_meta($user_id, '_pending_pass');
		delete_user_meta($user_id, '_pending_form');
	}









		///////////////////////////////
	//Make the link that user has to click to
	///become verified
	
	/*function accept_invite_to_verify($user_id) {
		$salt = get_user_meta($user_id, '_invite_verify', true);
		if ( $salt != '' && strlen($salt) == 20 && user_exists($user_id) ){
			$url = home_url() . '/';
			$url = add_query_arg( 'act', 'verified_invitation', $url );
			$url = add_query_arg( 'user_id', $user_id, $url );
			$url = add_query_arg( 'hash_secret', $salt, $url );
			return $url;
		}
	}	
		
	//Process verification invite
	
	function process_verification_invites(){
		if (isset($_GET['act']) && isset($_GET['user_id']) && isset($_GET['hash_secret'])) {
			if ($_GET['act'] == 'verified_invitation' && (int)$_GET['user_id'] && strlen($_GET['hash_secret']) == 20) {
			
				// valid request, verify user
				$hash = get_user_meta($_GET['user_id'], '_invite_verify', true);
				if ($hash == $_GET['hash_secret']) {
					verify( $_GET['user_id'] );
					//add_action('wp_footer', 'thunder_check_status_verified');
							function thunder_check_status_verified(){
								echo '<div class="userpro-bar-success">'.sprintf(__('Congratulations! Your account is now %s <strong>Verified</strong>. <i class="userpro-icon-remove"></i>','userpro'), userpro_get_badge('verified')).'</div>';
							}
							echo '<div class="bar-success">'.sprintf(__('Congratulations! Your account is now %s <strong>Verified</strong>. <i class="userpro-icon-remove"></i>','thunder'), 'verified' ).'</div>';
				} else {
				// invalid expired
					//add_action('wp_footer', 'thunder_failed_status_verified');
				}
			
			}
		}
	}
	function verify($user_id) {	
		// verify him
		update_user_meta($user_id, 'thunder_verified', 1);
		
		delete_user_meta($user_id, 'thunder_verification');
		delete_user_meta($user_id, '_invite_verify');
		
		// send him a notification
		if (thunder_get_option('notify_user_verified')){
			thunder_mail($user_id, 'accountverified');
		}
		//do_action('userpro_after_user_verify', $user_id);
				function userpro_sc_new_verified_user( $user_id ) {
					//global $userpro_social;
					log_action( 'verified', $user_id );
				}
				//add_action('userpro_after_user_verify', 'userpro_sc_new_verified_user');
	}*/






	/* Get nice name of profile field value */
	function thunder_profile_data_nicename($field, $value) {
		$output = '';
		$get_fields = get_option('thunnder_fields');
		if (is_array($value)){
			foreach($value as $s=>$l){
				$output[] = $l;
			}
			return implode(', ', $output);
		} else {
			if (isset($get_fields[$field]['options'][$value])){
			return $get_fields[$field]['options'][$value];
			} else {
			return $value;
			}
		}
	}
	/******************************************
	Gets a field label
	******************************************/
	function field_label($key){
		/*if (isset($fields[$key]['label'])){
			//return $this->fields[$key]['label'];
			return $fields[$key]['label'];
		}*/
		$get_fields = get_option('thunnder_fields');
		if (isset($get_fields[$key]['label'])){
			//return $this->fields[$key]['label'];
			return $get_fields[$key]['label'];
		}
	}	
	/******************************************
	Get the user profile data
	******************************************/
	function extract_profile_for_mail($user_id, $form) {
		$output = '';
		foreach($form as $k=>$v){
			if (field_label( $k ) != '' && !strstr($k, 'password') ) {
				$val = thunder_profile_data($k, $user_id);
				if ($k == 'gender') {
					$val = thunder_profile_data_nicename( $k, thunder_profile_data($k, $user_id) );
				}
				$output .= field_label($k) . ': '. $val . "\r\n";				
			}
		}
		return $output;
	}


	function thunder_mail($id, $template=null, $var1=null, $form=null) {
		//global $thunder;

		// '{thunder_LOGIN_URL}' => permalink(0, 'login'),
		// '{thunder_PROFILE_LINK}' => permalink( $user->ID ),
		$user = get_userdata($id);
		$builtin = array(
			'{thunder_ADMIN_EMAIL}' => thunder_get_option('mail_from'),
			'{thunder_BLOGNAME}' => thunder_get_option('mail_from_name'),
			'{thunder_LOGIN_URL}' => home_url(),
			'{thunder_USERNAME}' => $user->user_login,
			'{thunder_EMAIL}' => $user->user_email,
			'{thunder_PROFILE_LINK}' => home_url(),
			'{thunder_VALIDATE_URL}' => create_validate_url( $user->ID ),
			'{thunder_PENDING_REQUESTS_URL}' => admin_url() . '?page=thunder&tab=requests',
			//'{thunder_ACCEPT_VERIFY_INVITE}' => accept_invite_to_verify($user->ID),
		);
		
		if (isset($var1) && !empty($var1) ){
			$builtin['{VAR1}'] = $var1;
		}
		
		if (isset($form) && $form != ''){
		$builtin['{thunder_PROFILE_FIELDS}'] = extract_profile_for_mail( $user->ID, $form );
		}
		
		$search = array_keys($builtin);
		$replace = array_values($builtin);

		$headers = 'From: '.thunder_get_option('mail_from_name').' <'.thunder_get_option('mail_from').'>' . "\r\n";

		/////////////////////////////////////////////////////////
		/* verify email/new registration */
		/////////////////////////////////////////////////////////
		if ($template == 'verifyemail'){
			$subject = __('Verify your Account','thunder');
			$message = thunder_get_option('mail_verifyemail');
			$message = str_replace( $search, $replace, $message );
		}
		
		/////////////////////////////////////////////////////////
		/* secret key request */
		/////////////////////////////////////////////////////////
		if ($template == 'secretkey'){
			$subject = __('Reset Your Password','thunder');
			$message = thunder_get_option('mail_secretkey');
			$message = str_replace( $search, $replace, $message );
		}
		
		/////////////////////////////////////////////////////////
		/* account being removed */
		/////////////////////////////////////////////////////////
		if ($template == 'accountdeleted'){
			$subject = __('Your profile has been removed!','thunder');
			$message = thunder_get_option('mail_accountdeleted');
			$message = str_replace( $search, $replace, $message );
		}
		
		/////////////////////////////////////////////////////////
		/* verification invite */
		/////////////////////////////////////////////////////////
		if ($template == 'verifyinvite'){
			$subject = sprintf(__('Get Verified at %s!','thunder'), thunder_get_option('mail_from_name'));
			$message = thunder_get_option('mail_verifyinvite');
			$message = str_replace( $search, $replace, $message );
		}
		
		/////////////////////////////////////////////////////////  BAGES
		/* account being verified */ 
		/////////////////////////////////////////////////////////
		/*if ($template == 'accountverified'){
			$subject = __('Your account is now verified!','thunder');
			$message = thunder_get_option('mail_accountverified');
			$message = str_replace( $search, $replace, $message );
		}*/
		
		///////////////////////////////////////////////////////// BAGES
		/* account being unverified */
		/////////////////////////////////////////////////////////
		/*if ($template == 'accountunverified'){
			$subject = __('Your account is no longer verified!','thunder');
			$message = thunder_get_option('mail_accountunverified');
			$message = str_replace( $search, $replace, $message );
		}*/
		
		/////////////////////////////////////////////////////////
		/* new user's account */
		/////////////////////////////////////////////////////////
		if ($template == 'newaccount' && !is_pending($user->ID) ) {
			$subject = sprintf(__('Welcome to %s!','thunder'), thunder_get_option('mail_from_name'));
			$message = thunder_get_option('mail_newaccount');
			$message = str_replace( $search, $replace, $message );
		}
		
		/////////////////////////////////////////////////////////
		/* email user except: profileupdate */
		/////////////////////////////////////////////////////////
		if ($template != 'profileupdate' && $template != 'pendingapprove') {
			wp_mail( $user->user_email, $subject, $message, $headers );
		}
		




		/////////////////////////////////////////////////////////
		/* admin emails notifications */
		/////////////////////////////////////////////////////////
	
		if ($template == 'pendingapprove'){
			$subject = __('[thunder] User awaiting manual review','thunder');
			$message = thunder_get_option('mail_admin_pendingapprove');
			$message = str_replace( $search, $replace, $message );
			wp_mail( thunder_get_option('mail_from') , $subject, $message, $headers );
		}
		
		if ($template == 'newaccount') {
			$subject = __('[thunder] New User Registration','thunder');
			$message = thunder_get_option('mail_admin_newaccount');
			$message = str_replace( $search, $replace, $message );
			wp_mail( thunder_get_option('mail_from') , $subject, $message, $headers );
		}
		
		if ($template == 'accountdeleted' && thunder_get_option('notify_admin_profile_remove') ) {
			$subject = __('[thunder] A profile has been removed!','thunder');
			$message = thunder_get_option('mail_admin_accountdeleted');
			$message = str_replace( $search, $replace, $message );
			wp_mail( thunder_get_option('mail_from') , $subject, $message, $headers );
		}
		
		if ($template == 'profileupdate') {
			$subject = __('[thunder] A profile has been updated!','thunder');
			$message = thunder_get_option('mail_admin_profileupdate');
			$message = str_replace( $search, $replace, $message );
			wp_mail( thunder_get_option('mail_from') , $subject, $message, $headers );
		}		
	}


	function default_role($user_id, $form=null){
		if (thunder_get_option('default_role') ){
			if ( thunder_get_option('default_role')  == 'no_role') {
				$role = '';
			} else {
				$role = thunder_get_option('default_role');
			}
			$wp_user_object = new WP_User( $user_id );
			$wp_user_object->set_role( $role );
		}
	}

	/******************************************
	User exists by ID
	******************************************/
	function user_exists( $user_id ) {
		$aux = get_userdata( $user_id );
		if($aux==false){
			return false;
		}
		return true;
	}

		/******************************************
	Save user profile picture from facebook
	******************************************/
	/*function facebook_save_profile_pic($user_id, $profilepicture, $method=null){
		$method = userpro_get_option('picture_save_method');
		$unique_id = uniqid();
		if ($method == 'internal') {
		
			do_uploads_dir( $user_id );
			move_file( $user_id, $profilepicture, $unique_id . '.jpg' );
			update_user_meta($user_id, 'profilepicture', get_uploads_url($user_id) . $unique_id . '.jpg' );
			
		} else {
		
			update_user_meta($user_id, 'profilepicture', $profilepicture );
			
		}
	}*/

	function new_user($username, $password, $email, $form, $type, $approved=1) {
		
		$user_id = wp_create_user( $username, $password, $email );
		
		default_role($user_id, $form);
		
		if ($type == 'facebook') {
			thunder_update_profile_via_facebook($user_id, $form );
			facebook_save_profile_pic( $user_id, $form['ava_picture'] );
		} elseif ($type == 'twitter') {
			thunder_update_profile_via_twitter($user_id, $form );
			twitter_save_profile_pic( $user_id, $form );
		} elseif ($type == 'google') {
			thunder_update_profile_via_google($user_id, $form );
			google_save_profile_pic( $user_id, $form );
		} else {
			thunder_update_user_profile( $user_id, $form, $action='new_user' );
		}
		
		if ($approved==1){
	/*$message = sprintf(__('Username: %s'), $username) . "\r\n\r\n";
    $message .= sprintf( __( 'Password: %s' ), $password ) . "\r\n";
    $message .= sprintf( __( 'Email: %s' ), $email ) . "\r\n";
    $message .= '<' . network_site_url("wp-login.php?action=rp&key=$key&login=" . rawurlencode($user->user_login), 'login') . ">\r\n\r\n";
    $message .= wp_login_url() . "\r\n";
    wp_mail(thunder_get_option('mail_from'), sprintf(__('[%s] Your username and password info!', 'flipper')), $message);*/

			thunder_mail($user_id, 'newaccount', $password, $form );
			
			//do_action('thunder_after_new_registration', $user_id);
				//!!!!!!!!!! thunder_after_new_registration
				//log_action($user_id);
		}
		
		return $user_id;
	}
	function log_action($action, $user_id, $var1=null, $var2=null, $var3=null) {	
		//global $thunder, $thunder_social;
		
		$activity = get_option('thunder_activity');
		
		$timestamp= ( isset($gmt) ) ? time() : time() + ( get_option( 'gmt_offset' ) * 3600 );
		
		$status = '';
		
		switch($action){
		
			case 'verified':
		
				$status .= '<div class="thunder-sc-img" data-key="profilepicture"><a href="'.$thunder->permalink( $user_id ).'">'.get_avatar( $user_id, '50' ).'</a></div><div class="thunder-sc-i"><div class="thunder-sc-i-name"><a href="'. $thunder->permalink( $user_id ) .'" title="'. __('View Profile','thunder'). '">'. thunder_profile_data('display_name', $user_id).'</a>'. thunder_show_badges( $user_id );
				$status .= '<span class="thunder-sc-i-info">';
				$status .= __('is now a verified account.','thunder');
				$status .= '</span>';
				$status .= '</div><div class="thunder-sc-i-time">'.gmdate("d M Y H:i:s", $timestamp).'</div></div><div class="thunder-clear"></div>';
				$activity[$user_id][$timestamp] = array('user_id' => $user_id, 'status' => $status );
				break;
		
			case 'new_post':
		
				$status .= '<div class="thunder-sc-img" data-key="profilepicture"><a href="'. $thunder->permalink( $user_id ).'">'.get_avatar( $user_id, '50' ).'</a></div><div class="thunder-sc-i"><div class="thunder-sc-i-name"><a href="'. $thunder->permalink( $user_id ) .'" title="'. __('View Profile','thunder'). '">'. thunder_profile_data('display_name', $user_id).'</a>'. thunder_show_badges( $user_id );
				$status .= '<span class="thunder-sc-i-info">';
				
				$status .= sprintf(__('has published a <a href="%s">new %s</a>.','thunder'), get_permalink($var1), $var3);
				
				if ($var2 != '') {
				$status .= '<span class="thunder-sc-i-sp">"'.$var2.'"</span>';
				}
				
				$status .= '</span>';
				$status .= '</div><div class="thunder-sc-i-time">'.gmdate("d M Y H:i:s", $timestamp).'</div></div><div class="thunder-clear"></div>';
				$activity[$user_id][$timestamp] = array('user_id' => $user_id, 'status' => $status );
				break;
			
			case 'update_post':
			
				$status .= '<div class="thunder-sc-img" data-key="profilepicture"><a href="'.$thunder->permalink( $user_id ).'">'.get_avatar( $user_id, '50' ).'</a></div><div class="thunder-sc-i"><div class="thunder-sc-i-name"><a href="'. $thunder->permalink( $user_id ) .'" title="'. __('View Profile','thunder'). '">'. thunder_profile_data('display_name', $user_id).'</a>'. thunder_show_badges( $user_id );
				$status .= '<span class="thunder-sc-i-info">';
				
				$status .= sprintf(__('has updated a <a href="%s">%s</a>.','thunder'), get_permalink($var1), $var3);
				
				if ($var2 != '') {
				$status .= '<span class="thunder-sc-i-sp">"'.$var2.'"</span>';
				}
				
				$status .= '</span>';
				$status .= '</div><div class="thunder-sc-i-time">'.gmdate("d M Y H:i:s", $timestamp).'</div></div><div class="thunder-clear"></div>';
				$activity[$user_id][$timestamp] = array('user_id' => $user_id, 'status' => $status );
				break;
				
			case 'new_comment':
			
				$status .= '<div class="thunder-sc-img" data-key="profilepicture"><a href="'.$thunder->permalink( $user_id ).'">'.get_avatar( $user_id, '50' ).'</a></div><div class="thunder-sc-i"><div class="thunder-sc-i-name"><a href="'. $thunder->permalink( $user_id ) .'" title="'. __('View Profile','thunder'). '">'. thunder_profile_data('display_name', $user_id).'</a>'. thunder_show_badges( $user_id );
				$status .= '<span class="thunder-sc-i-info">';
				$status .= __('has posted a new comment on:','thunder');
				$status .= '<span class="thunder-sc-i-sp">"<a href="'.get_permalink($var1).'">'.$var2.'</a>"</span>';
				$status .= '</span>';
				$status .= '</div><div class="thunder-sc-i-time">'.gmdate("d M Y H:i:s", $timestamp).'</div></div><div class="thunder-clear"></div>';
				$activity[$user_id][$timestamp] = array('user_id' => $user_id, 'status' => $status );
				break;
				
			case 'new_follow':
			
				$dest = get_userdata($var1);
			
				$status .= '<div class="thunder-sc-img" data-key="profilepicture"><a href="'.$thunder->permalink( $user_id ).'">'.get_avatar( $user_id, '50' ).'</a></div><div class="thunder-sc-i"><div class="thunder-sc-i-name"><a href="'. $thunder->permalink( $user_id ) .'" title="'. __('View Profile','thunder'). '">'. thunder_profile_data('display_name', $user_id).'</a>'. thunder_show_badges( $user_id );
				$status .= '<span class="thunder-sc-i-info">';
				$status .= sprintf(__('has started following <a href="%s">%s</a>','thunder'), $thunder->permalink( $dest->ID ), thunder_profile_data('display_name', $dest->ID) );
				$status .= '</span>';
				$status .= '</div><div class="thunder-sc-i-time">'.gmdate("d M Y H:i:s", $timestamp).'</div></div><div class="thunder-clear"></div>';
				$activity[$user_id][$timestamp] = array('user_id' => $user_id, 'status' => $status );

				/* notification */
				if (thunder_sc_get_option('notification_on_follow')){
					$this->new_notification( $dest, $user_id, 'new_follow' );
				}
		
				break;
				
			case 'stop_follow':
			
				$dest = get_userdata($var1);
			
				$status .= '<div class="thunder-sc-img" data-key="profilepicture"><a href="'.$thunder->permalink( $user_id ).'">'.get_avatar( $user_id, '50' ).'</a></div><div class="thunder-sc-i"><div class="thunder-sc-i-name"><a href="'. $thunder->permalink( $user_id ) .'" title="'. __('View Profile','thunder'). '">'. thunder_profile_data('display_name', $user_id).'</a>'. thunder_show_badges( $user_id );
				$status .= '<span class="thunder-sc-i-info">';
				$status .= sprintf(__('has stopped following <a href="%s">%s</a>','thunder'), $thunder->permalink( $dest->ID ), thunder_profile_data('display_name', $dest->ID) );
				$status .= '</span>';
				$status .= '</div><div class="thunder-sc-i-time">'.gmdate("d M Y H:i:s", $timestamp).'</div></div><div class="thunder-clear"></div>';
				$activity[$user_id][$timestamp] = array('user_id' => $user_id, 'status' => $status );
				break;
				
			case 'new_user' :
			
				$status .= '<div class="thunder-sc-img" data-key="profilepicture"><a href="'.$thunder->permalink( $user_id ).'">'.get_avatar( $user_id, '50' ).'</a></div><div class="thunder-sc-i"><div class="thunder-sc-i-name"><a href="'. $thunder->permalink( $user_id ) .'" title="'. __('View Profile','thunder'). '">'. thunder_profile_data('display_name', $user_id).'</a>'. thunder_show_badges( $user_id );
				$status .= '<span class="thunder-sc-i-info">';
				$status .= __('has just registered!','thunder');
				$status .= '</span>';
				$status .= '</div><div class="thunder-sc-i-time">'.gmdate("d M Y H:i:s", $timestamp).'</div></div><div class="thunder-clear"></div>';
				$activity[$user_id][$timestamp] = array('user_id' => $user_id, 'status' => $status );
				break;
			

		}
		
		update_option('thunder_activity', $activity);
		
	}	
	/*	add_action('userpro_pre_profile_update', 'userpro_unverify_verified_account', 10, 2);
	function userpro_unverify_verified_account($form, $user_id){
		global $userpro;
		
		// validate display name change
		if (!userpro_is_admin($user_id) && userpro_get_option('unverify_on_namechange') && $userpro->get_verified_status($user_id) == 1 && !current_user_can('manage_options') ) {
			if (isset($form['display_name'])){
				$old_displayname = userpro_profile_data('display_name', $user_id);
				$new_displayname = $form['display_name'];
				if ($new_displayname != $old_displayname){
					$userpro->unverify($user_id);
				}
			}	
		}
	
	}*/
		/* filter hooks before profile is updated */
	/*add_filter('userpro_pre_profile_update_filters', 'userpro_prevent_duplicate_display_names', 10, 2);
	function userpro_prevent_duplicate_display_names($form, $user_id){
		global $userpro;
		
		// validate display name
		if (isset($form['display_name'])){
			$form['display_name'] = $userpro->remove_denied_chars($form['display_name'], 'display_name');
			if ($userpro->display_name_exists( $form['display_name'] )){
				$user = get_userdata($user_id);
				$form['display_name'] = $user->user_login;
			}
		}
		
		return $form;
	}

	function display_name_exists($display_name) {
		$users = get_users(array(
			'meta_key'     => 'display_name',
			'meta_value'   => $display_name,
			'meta_compare' => '='
		));
		if ( isset($users[0]->ID) && ( $users[0]->ID == get_current_user_id()) ) {
			return false;
		} elseif ( current_user_can('manage_options') ) {
			return false;
		} elseif ( isset($users[0]->ID) ) {
			return true;
		}
		return false;
	}
		/******************************************
	Create uploads dir if does not exist
	******************************************/
	function do_upload_dir($user_id=0) {
		$upload_dir = wp_upload_dir();
		$upload_base_dir = $upload_dir['basedir'] . '/bfi_thumb/';

		if (!file_exists( $upload_base_dir )) {
			@mkdir( $upload_base_dir, 0777, true);
		}
		
		/*if ($user_id > 0) { // upload dir for a user
			if (!file_exists( $upload_base_dir . $user_id . '/' )) {
				@mkdir( $upload_base_dir . $user_id . '/', 0777, true);
			}
		}*/
	}

	/******************************************
	Get the proper uploads dir
	******************************************/
	function get_uploads_dir($user_id=0){
		$upload_dir = wp_upload_dir();
		$upload_base_dir = $upload_dir['basedir'] . '/bfi_thumb/';
		
		/*if ($user_id > 0) {
			return $upload_base_dir . $user_id . '/';
		}*/
		return $upload_base_dir;
	}
		/******************************************
	Get the proper uploads dir
	******************************************/
	function get_bfi_dir($user_id=0){
		$upload_dir = wp_upload_dir();
		$upload_base_dir = $upload_dir['basedir'] . '/bfi_thumb/';

		/*if ($user_id > 0) {
			return $upload_base_dir . $user_id . '/';
		}*/
		return $upload_base_dir;
	}
	/******************************************
	Return the uploads URL
	******************************************/
	function get_uploads_url($user_id=0){
		$upload_dir = wp_upload_dir();		
		$upload_base_url = $upload_dir['baseurl'] . '/bfi_thumb/';

		/*if ($user_id > 0) {
			return $upload_base_url . $user_id . '/';
		}*/
		return $upload_base_url;
	}

	
		/* Update user profile data */
	function thunder_update_user_profile($user_id, $form, $action=null) {
		//global $thunder;
		extract($form);

		if ($action == 'new_user' && !user_exists($user_id) )
			die();
			
		if (!user_exists($user_id))
			die();
		
		if ( $action == 'ajax_save' && $user_id != get_current_user_id() && !current_user_can('manage_options') )
			die();
			
		if (!$tpl) die();
		
		/* hooks before saving profile fields */
		//do_action('thunder_pre_profile_update', $form, $user_id);
		//$form = apply_filters('thunder_pre_profile_update_filters', $form, $user_id);
		
		//!!!!!!!!!!!!!!!!!!!!44444444444444444444444444444444444444444444
		$upload_dir = wp_upload_dir();
		$upload_base_dir = $upload_dir['basedir'] . '/bfi_thumb/';
		$upload_base_url = $upload_dir['baseurl'] . '/bfi_thumb/';

		$fields = thunder_fields_group_by_template( $tpl, $group );
		foreach($form as $key => $form_value) {
			
			/* hidden from public */
			if (!isset($form["hide_$key"])) {
				update_user_meta( $user_id, 'hide_'.$key, 0 );
			} elseif (isset($form["hide_$key"])){
				update_user_meta( $user_id, 'hide_'.$key, 1 );
			}
			
			/* UPDATE PRIMARY META */
			if ( isset($key) && in_array($key, array('user_url', 'display_name', 'role', 'user_login', 'user_password', 'user_pass_confirm', 'user_email')) ) {
				
				/* Save passwords */
				if ($key == 'user_password') {
					if (!empty($form_value)) {
						wp_update_user( array ( 'ID' => $user_id, $key => $form_value ) ) ;
					}
				} else {
					wp_update_user( array ( 'ID' => $user_id, $key => $form_value ) ) ;
				}
				
			}
			
			/* UPDATE USER META TABLE */
			if (isset($key) && !strstr($key, 'password')){
				update_user_meta( $user_id, $key, $form_value );
			} else {
				delete_user_meta( $user_id, $key );
			}
			



		
			//move user pics to his folder 
			if ( ( $key == 'ava_picture' || $key == 'file'  ) && isset($form_value) && !empty($form_value) ) {			
				
				if (!file_exists( $upload_base_dir )) {
					@mkdir( $upload_base_dir, 0777, true);
				}
				
				/*if ($user_id > 0) { // upload dir for a user
					if (!file_exists( $upload_base_dir . $user_id . '/' )) {
						@mkdir( $upload_base_dir . $user_id . '/', 0777, true);
					}
				}
				
				
				if ( file_exists( get_uploads_dir() . basename( thunder_profile_data( $key, $user_id ) ) ) ) {
					rename( get_uploads_dir() . basename( thunder_profile_data( $key, $user_id ) ),  get_uploads_dir($user_id) . basename( thunder_profile_data( $key, $user_id ) ) );
					//update_user_meta($user_id, $key, get_uploads_url($user_id) . basename( thunder_profile_data( $key, $user_id ) ) );
				}*/
				update_user_meta($user_id, $key, get_uploads_url() . basename( thunder_profile_data( $key, $user_id ) ) );				
			}
			
			
			
			/* MailChimp Integration */
			if ( ( isset($fields[$key]['type']) && $fields[$key]['type'] == 'mailchimp') ) {
				if ($form[$key] == 'unsubscribed'){
				//$thunder->mailchimp_subscribe( $user_id, $fields[$key]['list_id'] );
				} elseif ($form[$key] == 'subscribed') {
				//$thunder->mailchimp_unsubscribe( $user_id, $fields[$key]['list_id'] );
				}
			}
			
		}
		
		/* do action while updating profile (use $form) */
		//do_action('thunder_profile_update', $form, $user_id);
		
		/* after profile update no args */
		//do_action('thunder_after_profile_updated');
		//clear_cache();				
	}

	
	/* Get file type icon */
	function thunder_file_type_icon( $file ) {
		$ext = pathinfo($file, PATHINFO_EXTENSION);
		switch($ext){
			default:
				$type = 'file';
				break;
			case 'txt':
				$type = 'txt';
				break;
			case 'pdf':
				$type = 'pdf';
				break;
			case 'zip':
				$type = 'zip';
				break;
		}
		return 'class="'.$type.'"';
	}
	
	/* If field has special roles */
	function thunder_field_by_role( $key, $user_id ) {
		$test = thunder_get_option( $key . '_roles' );

		if ( $user_id > 0 && is_array($test) && ! current_user_can( 'manage_options' )  ){
			
			$user = get_userdata( $user_id );
			$user_role = array_shift( $user->roles );

			if ( ! in_array( $user_role, $test) ) {
				return false;
			};
		};

		return true;
	};


	/******************************************
	Get full name (user friendly)
	******************************************/
	function get_full_name( $user_id ) {
		$first_name = get_user_meta($user_id, 'first_name', true);
		$last_name = get_user_meta($user_id, 'last_name', true);
		$name = $first_name . ' ' . $last_name;
		return $name;
	}


	/******************************************
	Check for a pending user
	******************************************/
	function is_pending($user_id) {
		$checkuser = get_user_meta($user_id, '_account_status', true);
		if ($checkuser == 'pending' || $checkuser == 'pending_admin')
			return true;
		return false;
	}


		/* Auto login user */
	function thunder_auto_login( $username, $remember=true ) {
		ob_start();
		if ( !is_user_logged_in() ) {
			$user = get_user_by('login', $username );
			$user_id = $user->ID;
			wp_set_current_user( $user_id, $username );
			nocache_headers();
			wp_clear_auth_cookie();
			wp_set_auth_cookie( $user_id, $remember );
			do_action( 'wp_login', $username );
		} else {
			wp_logout();
			$user = get_user_by('login', $username );
			$user_id = $user->ID;
			wp_set_current_user( $user_id, $username );
			nocache_headers();
			wp_clear_auth_cookie();			
			wp_set_auth_cookie( $user_id, $remember );
			do_action( 'wp_login', $username );
		}
		ob_end_clean();
	}



	/* Get a profile data for user id */
	function thunder_profile_data( $field, $user_id ) {
		
		$user = get_userdata( $user_id );
		$output = '';
		if ($user != false) {
			switch($field){
				case 'display_name':
					$output = $user->display_name;
					if (thunder_get_option('user_display_name') == 'name') $output = get_full_name($user_id);
					if (thunder_get_option('user_display_name_key')) $output = thunder_profile_data( thunder_get_option('user_display_name_key'), $user_id);
					break;
				case 'user_url':
					$output = $user->user_url;
					break;
				case 'user_email':
					$output = $user->user_email;
					break;
				case 'user_login':
					$output = $user->user_login;
					break;
				case 'role':
					$user_roles = $user->roles;
					$user_role = array_shift($user_roles);
					$output = $user_role;
					break;
				default:
					$output = get_user_meta( $user_id, $field, true );
					break;
			}
		}
		return $output;
	}

	


	function thunder_user_cannot_edit($array){
		global $current_user;
		//if (isset($array['locked']) && $array['locked']==1 && !current_user_can('manage_options') )
	    if (isset($array['locked']) && $array['locked']==1 )
			return true;
		return false;
	}

		/* Privacy of fields */
	function thunder_private_field_class($array){
		global $current_user;
		/*if (isset($array['private']) && $array['private']==1 && !current_user_can('manage_options') )
			return 'thunder-field-private';
		return '';*/
		if (isset($array['private']) && $array['private']==1 && !current_user_can('manage_options') )
			return ' thunder-field-private';
		return '';
	}

	/* Edit a field */
	function thunder_edit_field( $key, $array, $master_id, $user_id=null, $args ) {	
		//global $thunder;
		
		extract($array);
		extract($args);
		$res = null;
		
		/**
		include & exclude
		done by custom shortcode
		params 
		start here 
		**/
		
		/*if (isset($args['exclude_fields']) && $args['exclude_fields'] != '' ){
			if (in_array( $key, explode(',',$args['exclude_fields']) ) ) {
				$res = '';
				return false;
			}
		}
		
		if (isset($args['exclude_fields_by_name']) && $args['exclude_fields_by_name'] != '' ){
			if (in_array( $array['label'], explode(',',$args['exclude_fields_by_name']) ) ) {
				$res = '';
				return false;
			}
		}
		
		if (isset($args['exclude_fields_by_type']) && $args['exclude_fields_by_type'] != '' ){
			if (isset($array['type']) && in_array( $array['type'], explode(',',$args['exclude_fields_by_type']) ) ) {
				$res = '';
				return false;
			}
		}
		
		if (isset($args['include_fields']) && $args['include_fields'] != '' ){
			if (!in_array( $key, explode(',',$args['include_fields']) ) ) {
				$res = '';
				return false;
			}
		}
		
		if (isset($args['include_fields_by_name']) && $args['include_fields_by_name'] != '' ){
			if (!in_array( $array['label'], explode(',',$args['include_fields_by_name']) ) ) {
				$res = '';
				return false;
			}
		}
		
		if (isset($args['include_fields_by_type']) && $args['include_fields_by_type'] != '' ){
			if (isset($array['type']) && !in_array( $array['type'], explode(',',$args['include_fields_by_type']) ) || !isset($array['type']) ) {
				$res = '';
				return false;
			}
		}*/
		
		/**
		end here
		thanks please do not edit 
		here unless you know what you do
		**/
		
		/* get field data */
		$data = null;
		
		/* default ajax callbacks/checks */
		if ($key == 'user_login' && $args['tpl'] == 'register') {
			if (!isset($array['ajaxcheck']) || $array['ajaxcheck'] == ''){
				$array['ajaxcheck'] = 'username_exists';
			}
		}
		if ($key == 'user_email' && $args['tpl'] == 'register') {
			if (!isset($array['ajaxcheck']) || $array['ajaxcheck'] == ''){
				$array['ajaxcheck'] = 'email_exists';
			}
		}
		if ($key == 'display_name' && $args['tpl'] == 'edit') {
			if (!isset($array['ajaxcheck']) || $array['ajaxcheck'] == ''){
				$array['ajaxcheck'] = 'display_name_exists';
			}
		}
		if ($key == 'display_name' && $args['tpl'] == 'register') {
			if (!isset($array['ajaxcheck']) || $array['ajaxcheck'] == ''){
				$array['ajaxcheck'] = 'display_name_exists';
			}
		}
		
		foreach($array as $data_option=>$data_value){
			if (!is_array($data_value)){
				$data .= " data-$data_option='$data_value'";
			}
		}
		
		/* disable editing */
		if (thunder_user_cannot_edit($array)){
			$data .= ' disabled="disabled"';
		}

		/* if editing an already user */
		if ($user_id){
			$is_hidden = thunder_profile_data('hide_'.$key, $user_id);
			$value = thunder_profile_data( $key, $user_id );
			if (isset($array['type']) && $array['type'] == 'ava_picture'){
				if ($key == 'ava_picture') {
					$size = thunder_get_option('avatar_size');
					$value = get_avatar($user_id, $size);
				} else {
					$crop = thunder_profile_data( $key, $user_id );
					if (!$crop){
						$value = '<span class="thunder-pic-none">'.__('No file has been uploaded.','thunder').'</span>';
					} else {
						$value = '';
					}
					
					if (isset($array['width'])){
						$width = $array['width'];
						$height = $array['height'];
					} else {
						$width = '';
						$height = '';
					}
					
					$value .= '<img src="'.$crop.'" width="'.$width.'" height="'.$height.'" alt="" class="modified" />';
				}
			}
			if (isset($array['type']) && $array['type'] == 'file') {
				$value = '<span class="thunder-pic-none">'.__('No file has been uploaded.','thunder').'</span>';
				$file = thunder_profile_data( $key, $user_id );
				if ($file){
					$value = '<div class="thunder-file-input"><a href="'.$file.'" '.thunder_file_type_icon($file).'>'.basename( $file ).'</a></div>';
				}
			}
		} else {
			
			// perhaps in registration
			if (isset($array['type']) && $array['type'] == 'ava_picture'){
				if ($key == 'ava_picture') {
					$size = thunder_get_option('avatar_size');					
					$array['default'] = get_avatar(0, $size);
				}
			}
			
			if (isset($array['hidden'])){
			$is_hidden = $array['hidden'];
			}
			if (isset($array['hideable'])){
			$hideable = $array['hideable'];
			}			
			
			if (isset($array['default'])){
			$value = $array['default'];
			}
			
		}
		
		if (!isset($value)) $value = null;
		
		if (!isset($array['placeholder'])) $array['placeholder'] = null;
		
		/* remove passwords */
		if (isset($array['type']) && $array['type'] == 'password') $value = null;
		
		/* display a section */
		if ($allow_section && isset( $array['section'] ) ) {
		$res .= "<div class='thunder-section thunder-column thunder-collapsible-".$array['collapsible']." thunder-collapsed-".$array['collapsed']."'><span>".$array['section']."</sapan></div>";
		}
		
		/* display a field */
		if (!$user_id) $user_id = 0;
	if (isset( $array['type'] ) && thunder_field_by_role( $key, $user_id ) ) {
		/*
		
		if ( $array['label'] && $array['type'] != 'passwordstrength' ) {
		
		if ($args['field_icons'] == 1) {
		$res .= "<div class='thunder-label iconed'>";
		} else {
		$res .= "<div class='thunder-label'>";
		}
		$res .= "<label for='$key-$master_id'>".$array['label']."</label>";*/
					//
		//
		//
		//
		//
		//Not finish
		//
		//
		//
		//
		//нужно доработать field icon
				/*	if ($args['field_icons'] == 1 && $thunder->field_icon($key)) {
						$res .= '<span class="thunder-field-icon"><i class="thunder-icon-'. $thunder->field_icon($key) .'"></i></span>';
					}*/

					
			/*		if ($args['tpl'] != 'login' && isset( $array['help'] ) && $array['help'] != '' ) {
						$res .= '<span class="thunder-tip" title="'.stripslashes( $array['help'] ).'"></span>';
					}
					
		$res .= "</div>";
		}
		
		$res .= "<div class='thunder-input'>"*/;		
			/* switch field type */

		$res .= gather_field( $key, $array, $master_id, $user_id=null, $args, $value, $options, $is_hidden, $hideable, $data );	

				
		/* add action for each field */
		//$hook = apply_filters("thunder_field_filter", $key, $user_id);
		//$res .= $hook;
		
		//$res .= "<div class='thunder-clear'></div>";
		//$res .= "</div>";
		//$res .= "</div>";
		//$res .= "<div class='thunder-clear'></div>";
		//				
		}
		return $res;
	}
	
	function gather_field( $key, $array, $master_id, $user_id=null, $args, $value, $options, $is_hidden, $hideable, $data ) {
		$res = null;
		$in = null;
		$out = null;
		switch($array['type']) {
		
			case 'ava_picture':
				$url = THUNDER_REG_URL . 'css/img/default_avatar_male.jpg';
				$size = thunder_get_option('avatar_size');
				//$ava = '<img src="'.$url.'" width="'.$size.'" width="'.$height.'" alt="thunder-default" class="default avatar" />';
				if ( $array['row'] == 'newrow' ) {
					$in = '<div class="thunder-row">';
					$out = '</div>';
					$col = '';
				} else {
					$col = '-col';
				}	
				if (!isset($array['button_text']) || $array['button_text'] == '' ) $array['button_text'] = __('Upload photo','thunder');
				$res .= $in."<div class='thunder-field".$col . thunder_private_field_class($array)."' data-key='$key'>";

				if ($args['field_icons'] == 1) {
				$res .= "<div class='thunder-label iconed'>";
				} else {
				$res .= "<div class='thunder-label'>";
				}
				if ($args['tpl'] != 'login' && isset( $array['help'] ) && $array['help'] != '' ) {
					$res .= '<span class="thunder-tip" title="'.stripslashes( $array['help'] ).'"></span>';
				}
				$res .= "<label for='$key-$master_id'>".$array['label']."</label>";
				$res .= "</div>";//end label
				$res .= "<div class='thunder-input'>";
				$res .= "<div class='thunder-pic' data-remove_text='".__('Remove','thunder')."' data-ava='".$url."' data-ava_size='".$size."'>".$value."</div>";
				$res .= "<input type='button' class='thunder-pic-uploaded' data-filetype='ava_picture' data-allowed_extensions='png,gif,jpg,jpeg' value='".$array['button_text']."' />";
				if ($user_id && thunder_profile_data( $key, $user_id ) ){
				$res .= "<input type='button' value='".__('Remove','thunder')."' class='thunder-button ava-remove' />";
				}
				$res .= "<input type='hidden' class='thunder-avathumb' name='$key-$master_id' id='$key-$master_id' value='".thunder_profile_data( $key, $user_id )."' />";
				$res .= "</div>";//end input
				$res .= "</div>".$out;//end field
				break;
				
			case 'file':
				if ( $array['row'] == 'newrow' ) {
					$in = '<div class="thunder-row">';
					$out = '</div>';
					$col = '';
				} else {
					$col = '-col';
				}	
				if (!isset($array['button_text']) || $array['button_text'] == '') $array['button_text'] = __('Upload file','thunder');
				$res .= $in."<div class='thunder-field".$col . thunder_private_field_class($array)."' data-key='$key'>";
				if ($args['field_icons'] == 1) {
				$res .= "<div class='thunder-label iconed'>";
				} else {
				$res .= "<div class='thunder-label'>";
				}
				if ($args['tpl'] != 'login' && isset( $array['help'] ) && $array['help'] != '' ) {
					$res .= '<span class="thunder-tip" title="'.stripslashes( $array['help'] ).'"></span>';
				}
				$res .= "<label for='$key-$master_id'>".$array['label']."</label>";
				$res .= "</div>";//end label
				$res .= "<div class='thunder-input'>";
				$res .= "<div class='thunder-pic' data-remove_text='".__('Remove','thunder')."'>".$value."</div>";
				$res .= "<input type='button'  class='thunder-pic-upload' data-filetype='file' data-allowed_extensions='".$args['file_allowed_extensions']."' value='".$array['button_text']."' />";					
				if ($user_id && thunder_profile_data( $key, $user_id ) ){
				$res .= "<input type='button' value='".__('Remove','thunder')."' class='thunder-button file-remove' />";
				}
				$res .= "<input type='hidden' class='thunder-filethumb' name='$key-$master_id' id='$key-$master_id' value='".thunder_profile_data( $key, $user_id )."' />";
				$res .= "</div>";//end input
				$res .= "</div>".$out;//end field					
				break;
				
			case 'datepicker':
				if ( $array['row'] == 'newrow' ) {
					$in = '<div class="thunder-row">';
					$out = '</div>';
					$col = '';
				} else {
					$col = '-col';
				}					
				$res .= "<input data-fieldtype='datepicker' class='thunder-datepicker' type='text' name='$key-$master_id' id='$key-$master_id' value='".$value."' placeholder='".$array['placeholder']."' $data />";
				
				/* allow user to make it hideable */
				if ( isset($array['hideable']) && $array['hideable'] == 1) {
					$res .= $in."<div class='thunder-field".$col . thunder_private_field_class($array)."' data-key='$key'>";
					if ($args['field_icons'] == 1) {
					$res .= "<div class='thunder-label iconed'>";
					} else {
					$res .= "<div class='thunder-label'>";
					}
					if ($args['tpl'] != 'login' && isset( $array['help'] ) && $array['help'] != '' ) {
						$res .= '<span class="thunder-tip" title="'.stripslashes( $array['help'] ).'"></span>';
					}
					$res .= "<label for='$key-$master_id'>".$array['label']."</label>";
					$res .= "</div>";//end label
					$res .= "<div class='thunder-input'>";
					$res .= "<label class='thunder-checkbox hide-field'><span";
					if (checked( $hideable, $is_hidden, 0 )) { $res .= ' class="checked"'; }
					$res .= "></span><input type='checkbox' value='$hideable' name='hide_$key-$master_id'";
					$res .= checked( $hideable, $is_hidden, 0 );
					$res .= " />".__('Make this field hidden from public','thunder')."</label>";
					$res .= "</div>";//end input
					$res .= "</div>".$out;//end field
				}
				
				break;
				
			case 'text':
				if ( $array['row'] == 'newrow' ) {
					$in = '<div class="thunder-row">';
					$out = '</div>';
					$col = '';
				} else {
					$col = '-col';
				}	
				$res .= $in."<div class='thunder-field".$col . thunder_private_field_class($array)."' data-key='$key'>";
				if ($args['field_icons'] == 1) {
				$res .= "<div class='thunder-label iconed'>";
				} else {
				$res .= "<div class='thunder-label'>";
				}
				if ($args['tpl'] != 'login' && isset( $array['help'] ) && $array['help'] != '' ) {
					$res .= '<span class="thunder-tip" title="'.stripslashes( $array['help'] ).'"></span>';
				}
				$res .= "<label for='$key-$master_id'>".$array['label']."</label>";
				$res .= "</div>";//end label
				$res .= "<div class='thunder-input'>";
			
				$res .= "<input type='text' name='$key-$master_id' id='$key-$master_id' value='".$value."' placeholder='".$array['placeholder']."' $data />";
				
				/* allow user to make it hideable */
				if ( isset($array['hideable']) && $array['hideable'] == 1) {
					$res .= "<label class='thunder-checkbox hide-field'><span";
					if (checked( $hideable, $is_hidden, 0 )) { $res .= ' class="checked"'; }
					$res .= "></span><input type='checkbox' value='$hideable' name='hide_$key-$master_id'";
					$res .= checked( $hideable, $is_hidden, 0 );
					$res .= " />".__('Make this field hidden from public','thunder')."</label>";
				}
				$res .= "</div>";//end input
				$res .= "</div>".$out;//end field
				break;
				
			case 'antispam':
				if ( $array['row'] == 'newrow' ) {
					$in = '<div class="thunder-row">';
					$out = '</div>';
					$col = '';
				} else {
					$col = '-col';
				}					
				$res .= $in."<div class='thunder-field".$col . thunder_private_field_class($array)."' data-key='$key'>";
				if ($args['field_icons'] == 1) {
				$res .= "<div class='thunder-label iconed'>";
				} else {
				$res .= "<div class='thunder-label'>";
				}
				if ($args['tpl'] != 'login' && isset( $array['help'] ) && $array['help'] != '' ) {
					$res .= '<span class="thunder-tip" title="'.stripslashes( $array['help'] ).'"></span>';
				}
				$res .= "<label for='$key-$master_id'>".$array['label']."</label>";
				$res .= "</div>";//end label
				$res .= "<div class='thunder-input'>";
				$rand1 = rand(1, 10);
				$rand2 = rand(1, 10);
				$res .= sprintf(__('Answer: %s + %s','thunder'), $rand1, $rand2);
				$res .= "<input type='text' name='$key-$master_id' id='$key-$master_id' value='' $data />";
				$res .= "<input type='hidden' name='answer-$master_id' id='answer-$master_id' value='".($rand1 + $rand2)."' />";
				$res .= "</div>";//end input
				$res .= "</div>".$out;//end field					
				break;
				
			case 'textarea':
				if ( $array['row'] == 'newrow' ) {
					$in = '<div class="thunder-row">';
					$out = '</div>';
					$col = '';
				} else {
					$col = '-col';
				}					
				$res .= $in."<div class='thunder-field".$col . thunder_private_field_class($array)."' data-key='$key'>";
				if ($args['field_icons'] == 1) {
				$res .= "<div class='thunder-label iconed'>";
				} else {
				$res .= "<div class='thunder-label'>";
				}
				if ($args['tpl'] != 'login' && isset( $array['help'] ) && $array['help'] != '' ) {
					$res .= '<span class="thunder-tip" title="'.stripslashes( $array['help'] ).'"></span>';
				}
				$res .= "<label for='$key-$master_id'>".$array['label']."</label>";
				$res .= "</div>";//end label
				$res .= "<div class='thunder-input'>";
				if (isset($array['size'])) {
					$size = $array['size'];
				} else {
					$size = 'normal';
				}
				$res .= "<textarea class='$size' type='text' name='$key-$master_id' maxlength='1000' id='$key-$master_id' $data >$value</textarea>";
				
				/* allow user to make it hideable */
				if ($array['hideable'] == 1) {
					$res .= "<label class='thunder-checkbox hide-field'><span";
					if (checked( $hideable, $is_hidden, 0 )) { $res .= ' class="checked"'; }
					$res .= "></span><input type='checkbox' value='$hideable' name='hide_$key-$master_id'";
					$res .= checked( $hideable, $is_hidden, 0 );
					$res .= " />".__('Make this field hidden from public','thunder')."</label>";
				}
				$res .= "</div>";//end input
				$res .= "</div>".$out;//end field					
				break;
				
			case 'password':
				if ( $array['row'] == 'newrow' ) {
					$in = '<div class="thunder-row">';
					$out = '</div>';
					$col = '';
				} else {
					$col = '-col';
				}					
				$res .= $in."<div class='thunder-field".$col . thunder_private_field_class($array)."' data-key='$key'>";
				if ($args['field_icons'] == 1) {
				$res .= "<div class='thunder-label iconed'>";
				} else {
				$res .= "<div class='thunder-label'>";
				}
				if ($args['tpl'] != 'login' && isset( $array['help'] ) && $array['help'] != '' ) {
					$res .= '<span class="thunder-tip" title="'.stripslashes( $array['help'] ).'"></span>';
				}
				$res .= "<label for='$key-$master_id'>".$array['label']."</label>";
				$res .= "</div>";//end label
				$res .= "<div class='thunder-input'>";
				$res .= "<input type='password' name='$key-$master_id' id='$key-$master_id' value='".$value."' placeholder='".$array['placeholder']."' autocomplete='off' $data />";
				$res .= "</div>";//end input
				$res .= "</div>".$out;//end field					
				break;

			case 'passwordstrength':
				if ( $array['row'] == 'newrow' ) {
					$in = '<div class="thunder-row">';           
					$out = '</div>';
					$col = '';
				} else {
					$col = '-col';
				}					
				$res .= $in."<div class='thunder-field".$col . thunder_private_field_class($array)."' data-key='$key'>";
				if ($args['field_icons'] == 1) {
				$res .= "<div class='thunder-label iconed'>";
				} else {
				$res .= "<div class='thunder-label'>";
				}
				if ($args['tpl'] != 'login' && isset( $array['help'] ) && $array['help'] != '' ) {
					$res .= '<span class="thunder-tip" title="'.stripslashes( $array['help'] ).'"></span>';
				}
				$res .= "<label for='$key-$master_id'>".$array['label']."</label>";
				$res .= "</div>";//end label
				$res .= "<div class='thunder-input'>";

				$res .= '<div class="password-description">'.__('Password Strength','thunder').'</div>';
				$res .=	'<span class="thunder-strength-lvl" '.$data.' data-very_weak="'.__('Very Weak','thunder').'" data-weak="'.__('Weak','thunder').'"
				data-better="'.__('Better','thunder').'" data-medium="'.__('Medium','thunder').'" data-strong="'.__('Strong','thunder').'" data-strongest="'.__('Strongest','thunder').'" data-to_low="'.__('Your password must be at least 6 characters','thunder').'"></span>';
				$res .=	'<div id="password-strength" class="strength0"></div>';														

				$res .= "</div>";//end input
				$res .= "</div>".$out;//end field					
				break;
				
			case 'select':
				if ( $array['row'] == 'newrow' ) {
					$in = '<div class="thunder-row">';
					$out = '</div>';
					$col = '';
				} else {
					$col = '-col';
				}					
				$res .= $in."<div class='thunder-field".$col . thunder_private_field_class($array)."' data-key='$key'>";
				if ($args['field_icons'] == 1) {
				$res .= "<div class='thunder-label iconed'>";
				} else {
				$res .= "<div class='thunder-label'>";
				}
				if ($args['tpl'] != 'login' && isset( $array['help'] ) && $array['help'] != '' ) {
					$res .= '<span class="thunder-tip" title="'.stripslashes( $array['help'] ).'"></span>';
				}
				$res .= "<label for='$key-$master_id'>".$array['label']."</label>";
				$res .= "</div>";//end label
				$res .= "<div class='thunder-input'>";
				if (isset($options)){

					if (!isset( $value )) $value = 0;
					if (isset($array['default']) && !$value) $value = $array['default'];
					$res .= "<select name='$key-$master_id' id='$key-$master_id' class='chosen-select' data-placeholder='".$array['placeholder']."' $data >";
					if (is_array($options)) {
						if (isset($array['placeholder']) && !empty($array['placeholder'])){
							$res .= '<option value="" '.selected(0, $value, 0).'></option>';
						}
						foreach($options as $k=>$v) {
							$v = stripslashes($v);
							$res .= '<option value="'.$v.'" '.selected($v, $value, 0).'>'.$v.'</option>';
						}
					}
					$res .= "</select>";
				
				/* allow user to make it hideable */
				if ($array['hideable'] == 1) {
					$res .= "<label class='thunder-checkbox hide-field'><span";
					if (checked( $hideable, $is_hidden, 0 )) { $res .= ' class="checked"'; }
					$res .= "></span><input type='checkbox' value='$hideable' name='hide_$key-$master_id'";
					$res .= checked( $hideable, $is_hidden, 0 );
					$res .= " />".__('Make this field hidden from public','thunder')."</label>";
				}
				
				}
				$res .= "</div>";//end input
				$res .= "</div>".$out;//end field					
				break;

			case 'role':
				if ( $array['row'] == 'newrow' ) {
					$in = '<div class="thunder-row">';
					$out = '</div>';
					$col = '';
				} else {
					$col = '-col';
				}					
				$res .= $in."<div class='thunder-field".$col . thunder_private_field_class($array)."' data-key='$key'>";
				if ($args['field_icons'] == 1) {
				$res .= "<div class='thunder-label iconed'>";
				} else {
				$res .= "<div class='thunder-label'>";
				}
				if ($args['tpl'] != 'login' && isset( $array['help'] ) && $array['help'] != '' ) {
					$res .= '<span class="thunder-tip" title="'.stripslashes( $array['help'] ).'"></span>';
				}
				$res .= "<label for='$key-$master_id'>".$array['label']."</label>";
				$res .= "</div>";//end label
				$res .= "<div class='thunder-input'>";
				if (isset($options)){
					
					$options = thunder_get_roles( thunder_get_option('allowed_roles') );
					if (!isset( $value )) $value = 0;
					$res .= "<select name='$key-$master_id' id='$key-$master_id' class='chosen-select' data-placeholder='".$array['placeholder']."' $data >";
					if (is_array($options)) {
						if (isset($array['placeholder']) && !empty($array['placeholder'])){
							$res .= '<option value="" '.selected(0, $value, 0).'></option>';
						}
						foreach($options as $k=>$v) {
							$v = stripslashes($v);
							$res .= '<option value="'.$k.'" '.selected($k, $value, 0).'>'.$v.'</option>';
						}
					}
					$res .= "</select>";

				/* allow user to make it hideable */
				if ($array['hideable'] == 1) {
					$res .= "<label class='thunder-checkbox hide-field'><span";
					if (checked( $hideable, $is_hidden, 0 )) { $res .= ' class="checked"'; }
					$res .= "></span><input type='checkbox' value='$hideable' name='hide_$key-$master_id'";
					$res .= checked( $hideable, $is_hidden, 0 );
					$res .= " />".__('Make this field hidden from public','thunder')."</label>";
				}
				
				}
				$res .= "</div>";//end input
				$res .= "</div>".$out;//end field
				break;
				
			case 'multiselect':
				if ( $array['row'] == 'newrow' ) {
					$in = '<div class="thunder-row">';
					$out = '</div>';
					$col = '';
				} else {
					$col = '-col';
				}					
				$res .= $in."<div class='thunder-field".$col . thunder_private_field_class($array)."' data-key='$key'>";
				if ($args['field_icons'] == 1) {
				$res .= "<div class='thunder-label iconed'>";
				} else {
				$res .= "<div class='thunder-label'>";
				}
				if ($args['tpl'] != 'login' && isset( $array['help'] ) && $array['help'] != '' ) {
					$res .= '<span class="thunder-tip" title="'.stripslashes( $array['help'] ).'"></span>';
				}
				$res .= "<label for='$key-$master_id'>".$array['label']."</label>";
				$res .= "</div>";//end label
				$res .= "<div class='thunder-input'>";
				if (isset($options)){
				$res .= "<select name='".$key.'-'.$i.'[]'."' multiple='multiple' class='chosen-select' data-placeholder='".$array['placeholder']."'>";
				foreach($options as $k=>$v) {
					$v = stripslashes($v);
					if (strstr($k, 'optgroup_b')) {
						$res .= "<optgroup label='$v'>";
					} elseif (strstr($k, 'optgroup_e')) {
						$res .= "</optgroup>";
					} else {
						$res .= '<option value="'.$v.'" ';
						if ( ( is_array( $value ) && in_array($v, $value ) ) || $v == $value ) { $res .= 'selected="selected"'; }
						$res .= '>'.$v.'</option>';
					}
				}
				$res .= "</select>";
				}
				$res .= "</div>";//end input
				$res .= "</div>".$out;//end field					
				break;
				
			case 'checkbox':
				if ( $array['row'] == 'newrow' ) {
					$in = '<div class="thunder-row">';
					$out = '</div>';
					$col = '';
				} else {
					$col = '-col';
				}					
				$res .= $in."<div class='thunder-field".$col . thunder_private_field_class($array)."' data-key='$key'>";
				if ($args['field_icons'] == 1) {
				$res .= "<div class='thunder-label iconed'>";
				} else {
				$res .= "<div class='thunder-label'>";
				}
				if ($args['tpl'] != 'login' && isset( $array['help'] ) && $array['help'] != '' ) {
					$res .= '<span class="thunder-tip" title="'.stripslashes( $array['help'] ).'"></span>';
				}
				$res .= "<label for='$key-$master_id'>".$array['label']."</label>";
				$res .= "</div>";//end label
				$res .= "<div class='thunder-input'>";
				if (isset($options)){
				$res .= "<div class='thunder-checkbox-wrap' data-required='".$array['required']."'>";
				foreach($options as $k=>$v) {
					$v = stripslashes($v);
					$res .= "<label class='thunder-checkbox'><span";
					if ( ( is_array( $value ) && in_array($v, $value ) ) || $v == $value ) { $res .= ' class="checked"'; }
					$res .= '></span><input type="checkbox" value="'.$v.'" name="'.$key.'-'.$i.'[]" ';
					if ( ( is_array( $value ) && in_array($v, $value ) ) || $v == $value ) { $res .= 'checked="checked"'; }
					$res .= " />$v</label>";
				}
				$res .= "</div>";
				}
				$res .= "</div>";//end input
				$res .= "</div>".$out;//end field					
				break;
				
			case 'checkbox-full':
				if ( $array['row'] == 'newrow' ) {
					$in = '<div class="thunder-row">';
					$out = '</div>';
					$col = '';
				} else {
					$col = '-col';
				}					
				$res .= $in."<div class='thunder-field".$col . thunder_private_field_class($array)."' data-key='$key'>";
				if ($args['field_icons'] == 1) {
				$res .= "<div class='thunder-label iconed'>";
				} else {
				$res .= "<div class='thunder-label'>";
				}
				if ($args['tpl'] != 'login' && isset( $array['help'] ) && $array['help'] != '' ) {
					$res .= '<span class="thunder-tip" title="'.stripslashes( $array['help'] ).'"></span>';
				}
				$res .= "<label for='$key-$master_id'>".$array['label']."</label>";
				$res .= "</div>";//end label
				$res .= "<div class='thunder-input'>";
				if (isset($options)){
				$res .= "<div class='thunder-checkbox-wrap' data-required='".$array['required']."'>";
				foreach($options as $k=>$v) {
					$v = stripslashes($v);
					$res .= "<label class='thunder-checkbox full'><span";
					if ( ( is_array( $value ) && in_array($v, $value ) ) || $v == $value ) { $res .= ' class="checked"'; }
					$res .= '></span><input type="checkbox" value="'.$v.'" name="'.$key.'-'.$i.'[]" ';
					if ( ( is_array( $value ) && in_array($v, $value ) ) || $v == $value ) { $res .= 'checked="checked"'; }
					$res .= " />$v</label>";
				}
				$res .= "</div>";
				}
				$res .= "</div>";//end input
				$res .= "</div>".$out;//end field					
				break;
				
			case 'mailchimp':
				if ( $array['row'] == 'newrow' ) {
					$in = '<div class="thunder-row">';
					$out = '</div>';
					$col = '';
				} else {
					$col = '-col';
				}					
				$res .= $in."<div class='thunder-field".$col . thunder_private_field_class($array)."' data-key='$key'>";
				if ($args['field_icons'] == 1) {
				$res .= "<div class='thunder-label iconed'>";
				} else {
				$res .= "<div class='thunder-label'>";
				}
				if ($args['tpl'] != 'login' && isset( $array['help'] ) && $array['help'] != '' ) {
					$res .= '<span class="thunder-tip" title="'.stripslashes( $array['help'] ).'"></span>';
				}
				$res .= "<label for='$key-$master_id'>".$array['label']."</label>";
				$res .= "</div>";//end label
				$res .= "<div class='thunder-input'>";
				if (!isset($array['list_text'])){
					$array['list_text'] = __('Subscribe to our newsletter','thunder');
				}
				
				if ( mailchimp_is_subscriber($user_id, $array['list_id']) ) {
				
				$res .= "<div class='thunder-checkbox-wrap'>";
				$res .= "<div class='thunder-help'><i class='thunder-icon-ok'></i>".__('You are currently subscribed to this newsletter.','thunder')."</div>";
				$res .= "<label class='thunder-checkbox full'><span";
				$res .= '></span><input type="checkbox" value="subscribed" name="'.$key.'-'.$i.'" ';
				$res .= " />".__('Unsubscribe from this newsletter','thunder')."</label>";
				$res .= "</div>";
				
				} else {
				
				$res .= "<div class='thunder-checkbox-wrap'>";
				$res .= "<label class='thunder-checkbox full'><span";
				$res .= '></span><input type="checkbox" value="unsubscribed" name="'.$key.'-'.$i.'" ';
				$res .= " />".$array['list_text']."</label>";
				$res .= "</div>";
				
				}
				$res .= "</div>";//end input
				$res .= "</div>".$out;//end field					
				break;
			
			case 'radio':
				if ( $array['row'] == 'newrow' ) {
					$in = '<div class="thunder-row">';
					$out = '</div>';
					$col = '';
				} else {
					$col = '-col';
				}					
				$res .= $in."<div class='thunder-field".$col . thunder_private_field_class($array)."' data-key='$key'>";
				if ($args['field_icons'] == 1) {
				$res .= "<div class='thunder-label iconed'>";
				} else {
				$res .= "<div class='thunder-label'>";
				}
				if ($args['tpl'] != 'login' && isset( $array['help'] ) && $array['help'] != '' ) {
					$res .= '<span class="thunder-tip" title="'.stripslashes( $array['help'] ).'"></span>';
				}
				$res .= "<label for='$key-$master_id'>".$array['label']."</label>";
				$res .= "</div>";//end label
				$res .= "<div class='thunder-input'>";
				if (isset($options)){
				$res .= "<div class='thunder-radio' data-required='".$array['required']."'>";
				foreach($options as $k=>$v) {
					$v = stripslashes($v);					
					$res .= "<label class='thunder-radio'><span";
					if (checked( $v, $value, 0 )) { $res .= ' class="checked"'; }
					$res .= '></span><input type="radio" value="'.$v.'" name="'.$key.'-'.$i.'" ';
					$res .= checked( $v, $value, 0 );
					$res .= " />$v</label>";
				}
				$res .= "</div>";
				}
				$res .= "</div>";//end input
				$res .= "</div>".$out;//end field					
				break;
				
			case 'radio-full':
				if ( $array['row'] == 'newrow' ) {
					$in = '<div class="thunder-row">';
					$out = '</div>';
					$col = '';
				} else {
					$col = '-col';
				}					
				$res .= $in."<div class='thunder-field".$col . thunder_private_field_class($array)."' data-key='$key'>";
				if ($args['field_icons'] == 1) {
				$res .= "<div class='thunder-label iconed'>";
				} else {
				$res .= "<div class='thunder-label'>";
				}
				if ($args['tpl'] != 'login' && isset( $array['help'] ) && $array['help'] != '' ) {
					$res .= '<span class="thunder-tip" title="'.stripslashes( $array['help'] ).'"></span>';
				}
				$res .= "<label for='$key-$master_id'>".$array['label']."</label>";
				$res .= "</div>";//end label
				$res .= "<div class='thunder-input'>";
				if (isset($options)){
				$res .= "<div class='thunder-radio' data-required='".$array['required']."'>";
				foreach($options as $k=>$v) {
					$v = stripslashes($v);
					$res .= "<label class='thunder-radio full'><span";
					if (checked( $v, $value, 0 )) { $res .= ' class="checked"'; }
					$res .= '></span><input type="radio" value="'.$v.'" name="'.$key.'-'.$i.'" ';
					$res .= checked( $v, $value, 0 );
					$res .= " />$v</label>";
				}
				$res .= "</div>";
				}
				$res .= "</div>";//end input
				$res .= "</div>".$out;//end field					
				break;
			case 'logo_img':
				if ( $array['row'] == 'newrow' ) {
					$in = '<div class="thunder-row">';
					$out = '</div>';
					$col = '';
				} else {
					$col = '-col';
				}
				$res .= $in."<div class='thunder-field".$col . thunder_private_field_class($array)."' data-key='$key'>";
				$res .= '<h1><a href='.home_url().' title="'.$array['help'].'">'.$array['label'].'</a></h1>';
				$res .= "</div>".$out;//end field					
			break;
			case 'form_name':
				if ( $array['row'] == 'newrow' ) {
					$in = '<div class="thunder-row">';
					$out = '</div>';
					$col = '';
				} else {
					$col = '-col';
				}				
				$res .= $in."<div class='thunder-field".$col . thunder_private_field_class($array)."' data-key='$key'>";
				$res .= '<h2>'.$array['label'].'</h2>';
				
				$res .= "</div>".$out;//end field					
			break;	
			case 'user_submit':
				if ( $array['row'] == 'newrow' ) {
					$in = '<div class="thunder-row">';
					$out = '</div>';
					$col = '';
				} else {
					$col = '-col';
				}					
				$res .= $in."<div class='thunder-field".$col . thunder_private_field_class($array)."' data-key='$key'>";
				//$res .= '<input type="submit" value="'.$array['label'].'" class="thunder-login-sub"/>';
				$res .= '<input type="submit" value="'.$array['label'].'" class="thunder-login-sub"/>';

				$res .= "</div>".$out;//end field					
			break;
			case 'fields_trigger':
				if ( $array['row'] == 'newrow' ) {
					$in = '<div class="thunder-row">';
					$out = '</div>';
					$col = '';
				} else {
					$col = '-col';
				}					
				$res .= $in."<div class='thunder-field".$col . thunder_private_field_class($array)."' data-key='$key'>";
				$res .= '<input type="button" value="'.$array['label'].'" class="fields-trigger" data-tpl="'.$args["{$tpl}_fields_trigger_tpl"].'"/>';
				
				$res .= "</div>".$out;//end field					
			break;	
			case 'user_lost':
				if ( $array['row'] == 'newrow' ) {
					$in = '<div class="thunder-row">';
					$out = '</div>';
					$col = '';
				} else {
					$col = '-col';
				}				
				$res .= $in."<div class='thunder-field".$col . thunder_private_field_class($array)."' data-key='$key'>";
				$res .= '<div class="thunder-passremember"><a href="#" data-tpl="reset">'.$array['label'].'</a></div>';
				
				$res .= "</div>".$out;//end field					
			break;
			case 'facebook_sdk':
				$provider_id    = 'Facebook';
				$provider_name  = 'Facebook';
				$request_url = site_url( 'wp-login.php', 'login_post' ) . ( strpos( site_url( 'wp-login.php', 'login_post' ), '?' ) ? '&' : '?' ) . "action=thunder_social_request&mode=login";
                			// build authentication url
				//НУЖНО выбрать из базы!!!
                 $redirect_to = permalink();
                // $thunder_settings_use_popup = 2; // 1 for pop-up
				//$thunder_settings_use_popup = function_exists( 'wp_is_mobile' ) ? wp_is_mobile() ? 2 : $thunder_settings_use_popup : $thunder_settings_use_popup;
				$authenticate_url = $request_url . "&provider=" . $provider_id . "&redirect_to=" . urlencode( $redirect_to );
				/*if( $thunder_settings_use_popup == 1 &&  $auth_mode != 'test' ) {
							$authenticate_url= "javascript:void(0);";
				}*/
				$authenticate_url = esc_url( $authenticate_url );

				if ( $array['row'] == 'newrow' ) {
					$in = '<div class="thunder-row">';
					$out = '</div>';
					$col = '';
				} else {
					$col = '-col';
				}				
				$res .= $in."<div class='thunder-field".$col . thunder_private_field_class($array)."' data-key='$key'>";
				$res .= '<a rel="nofollow" href="' . $authenticate_url . '" title="' . $array['help'] . '" class="thunder-login-provider login-provider-facebook" data-provider="Facebook">Facebook</a>';
				
				$res .= "</div>".$out;//end field					
			break;								
		
		} /* end switch field type */
		return $res;		
	}







	/* check if passed value is URL */
	function thunder_filter_url($value, $target) {
		if(filter_var($value, FILTER_VALIDATE_URL)){
			$value = $value . '<a href="'.$value.'" target="'.$target.'"><i class="thunder-icon-external-link thunder-meta-value"></i></a>';
		} elseif (is_email($value)) {
			$value = $value . '<a href="mailto:'.$value.'"><i class="thunder-icon-envelope thunder-meta-value"></i></a>';
		}
		return $value;
	}
		/******************************************
	hidden fields from profile view
	******************************************/
	function fields_to_hide_from_view(){
		$option = thunder_get_option('hidden_from_view');
		$arr = explode(',',$option);
		return $arr;
	}
		/* Check if field can be viewed */
	function thunder_field_is_viewable( $key, $user_id, $args, $zone ) {

		if (current_user_can('manage_options'))
			return true;
			
		
		// Нужно оставить TRUE
		if ($user_id == get_current_user_id())
			return true;
		
		//if ( isset( $args[ $args['template'] . '_group'] ) && thunder_default_hidden($key, $args['template'], $args[ $args['template'] . '_group']))
		if ( isset( $args[ $args['tpl'] . '_group'] ) && thunder_default_hidden($key, $args['tpl'], $args[ $args['tpl'] . '_group'], $zone ))
			return false;
			
		$test = get_user_meta($user_id, 'hide_'.$key, true);
		if ($test == 1 && $user_id != get_current_user_id() )
			return false;
			
		return true;
	}
	//////////// НУЖНО ZONE
	/* default hidden state for field */
	function thunder_default_hidden( $key, $template, $group, $zone ){
		$groups = get_option('thunder_fields_groups');
		if (isset( $groups[$template][$group][$zone][$key]['hidden'] ) ) {
			$ret = $groups[$template][$group][$zone][$key]['hidden'];
			if ($ret == 1) {
				return true;
			}
		}
		return false;
	}

		/* Check certain value filters (printing on profile) */
	add_filter('thunder_before_value_is_displayed', 'thunder_before_value_is_displayed', 10, 3);
	function thunder_before_value_is_displayed($value, $key, $key_array){
		
		if ($key == 'description'){
			$value = wpautop($value); // auto-p user description
		}
		
		/*if ($key == 'country' && thunder_get_option('show_flag_in_profile') ) {
			$flag_name = str_replace(' ','-',$value);
			$flag_name = iconv("utf-8", "ascii//TRANSLIT//IGNORE", $flag_name);
			$value = '<img src="'.thunder_url.'img/flags/'.strtolower($flag_name).'.png" alt="" title="'.$value.'" class="thunder-flag-normal" />'.$value;
		}*/
		
		return $value;
	}
		/* nicer user role */
	function thunder_user_role($role){
		global $wp_roles;
		$roles = $wp_roles->get_names();
		return $roles[$role];
	}

	/* Show a field */
	function thunder_show_field( $key, $key_array, $i, $args, $user_id=null, $zone ) {
		//global $thunder;
		
		extract($key_array);
		extract($args);
		$res = null;
		
		/**
		include & exclude
		done by custom shortcode
		params 
		start here 
		**/
		
		/*if (isset($args['exclude_fields']) && $args['exclude_fields'] != '' ){
			if (in_array( $key, explode(',',$args['exclude_fields']) ) ) {
				$res = '';
				return false;
			}
		}
		
		if (isset($args['exclude_fields_by_name']) && $args['exclude_fields_by_name'] != '' ){
			if (in_array( $key_array['label'], explode(',',$args['exclude_fields_by_name']) ) ) {
				$res = '';
				return false;
			}
		}
		
		if (isset($args['exclude_fields_by_type']) && $args['exclude_fields_by_type'] != '' ){
			if (isset($key_array['type']) && in_array( $key_array['type'], explode(',',$args['exclude_fields_by_type']) ) ) {
				$res = '';
				return false;
			}
		}
		
		if (isset($args['include_fields']) && $args['include_fields'] != '' ){
			if (!in_array( $key, explode(',',$args['include_fields']) ) ) {
				$res = '';
				return false;
			}
		}
		
		if (isset($args['include_fields_by_name']) && $args['include_fields_by_name'] != '' ){
			if (!in_array( $key_array['label'], explode(',',$args['include_fields_by_name']) ) ) {
				$res = '';
				return false;
			}
		}
		
		if (isset($args['include_fields_by_type']) && $args['include_fields_by_type'] != '' ){
			if (isset($key_array['type']) && !in_array( $key_array['type'], explode(',',$args['include_fields_by_type']) ) || !isset($key_array['type']) ) {
				$res = '';
				return false;
			}
		}*/
		
		/**
		end here
		thanks please do not edit 
		here unless you know what you do
		**/
		
		if ($user_id){
			$value = thunder_profile_data( $key, $user_id );

			if (isset($key_array['type']) && $key != 'role' && in_array($key_array['type'], array('select','multiselect','checkbox','checkbox-full','radio','radio-full') ) ) {
				$value = thunder_profile_data_nicename( $key, thunder_profile_data( $key, $user_id ) );
			}
			if ( ( isset($key_array['html']) && $key_array['html'] == 0 ) ) {
				$value =  wp_strip_all_tags( $value );
			}
			if (isset($key_array['type']) && $key_array['type'] == 'ava_picture'){
				if ($key == 'ava_picture') {
					$value = get_avatar($user_id, $profile_thumb_size);
				} else {
					$crop = thunder_profile_data( $key, $user_id );
					if ($crop){
					if (isset($key_array['width'])){
						$width = $key_array['width'];
						$height = $key_array['height'];
					} else {
						$width = '';
						$height = '';
					}
					$value = '<img src="'.$crop.'" width="'.$width.'" height="'.$height.'" alt="" class="modified" />';
					}
				}
			}
		if (isset($key_array['type']) && $key_array['type'] == 'file'){
			$file = thunder_profile_data( $key, $user_id );
			if ($file){
			$value = '<div class="thunder-file-input"><a href="'.$file.'" '.thunder_file_type_icon($file).'>'.basename( $file ).'</a></div>';
			}
		}
		$value = thunder_filter_url($value, $args['link_target'] );
		}
		
		/* display a section */
		if ($allow_sections && isset($key_array['section']) ) {
		$res .= "<div class='thunder-section thunder-column thunder-collapsible-".$key_array['collapsible']." thunder-collapsed-".$key_array['collapsed']."'><span>".$key_array['section']."</span></div>";
		}
		
		/* display a field */
		if (!$user_id) $user_id = 0;
		if (isset($key_array['type']) && thunder_field_by_role( $key, $user_id ) && !empty($value) && thunder_field_is_viewable( $key, $user_id, $args, $zone )  && !in_array($key, fields_to_hide_from_view() ) && $key_array['type'] != 'mailchimp' ) {
			$res .= "<div class='thunder-field ".thunder_private_field_class($key_array)." thunder-field-$template' data-key='$key'>";
		
			if ( $key_array['label'] && $key_array['type'] != 'passwordstrength' ) {
		
				if ($args['field_icons'] == 1) {
					$res .= "<div class='thunder-label view iconed'>";
				} else {
					$res .= "<div class='thunder-label view'>";
				}
			$res .= "<label for='$key-$master_id'>".$key_array['label']."</label>";
		
			/*if ($args['field_icons'] == 1 && field_icon($key)) {
				$res .= '<span class="thunder-field-icon"><i class="thunder-icon-'. field_icon($key) .'"></i></span>';
			}*/
					
			//$res .= "</div>";
		
			}
		
		$res .= "<div class='thunder-input'>";
		
		/* before value display filter */
		$value = apply_filters('thunder_before_value_is_displayed', $value, $key, $key_array);		
		
			if ($key == 'role'){
				$res .= thunder_user_role($value);
				//$res .= 'fdf';
			} else {
				$res .= $value;
			}
		$res .= "</div>";
		$res .= "</div>";
		}
		/* hidden field notice */
		//if (thunder_field_is_viewable($key, $user_id, $args) && ( thunder_profile_data( 'hide_'.$key, $user_id ) || thunder_default_hidden( $key, $template, $args[ $template . '_group' ] ) ) ) {
		if (thunder_field_is_viewable($key, $user_id, $args, $zone) && ( thunder_profile_data( 'hide_'.$key, $user_id ) || thunder_default_hidden( $key, $tpl, $args[ $tpl . '_group' ], $zone ) ) ) {
			$res .= '<div class="thunder-help">'.sprintf(__('(Your %s will not be visible to public)','thunder'), strtolower($key_array['label'])).'</div>';
		}
		
		/*$res .= "<div class='thunder-clear'></div>";
		$res .= "</div>";
		$res .= "</div><div class='thunder-clear'></div>";*/
		
		//}
		
		return $res;
	}

		/* Get logout url */
	function thunder_logout_url($user_id, $redirect='current', $logout_redirect=null) {
		$current_user = wp_get_current_user();

		if ($user_id ==  $current_user->ID ) {
			if ($redirect == 'current' || !$redirect){
				$url = get_permalink();
			} else {
				$url = $redirect;
			}
			if ($logout_redirect){
				$url = $logout_redirect;
			}
			return wp_logout_url( $url );
		}
	}


	/* Checks if a user is logged in */
	function thunder_is_logged_in(){
		if (is_user_logged_in())
			return true;
		return false;
	}
		/* Logout page */
	function thunder_logout_page(){
		global $thunder;
		if ( is_page() || is_single() ) {
			global $post;
			$pages = get_option('thunder_links');
			if ($post->ID == $pages['logout_page'] ) {
				if (thunder_is_logged_in()){
				
					$logout = thunder_get_option('logout_uri');
					if ($logout == 1) $url = home_url();
					if ($logout == 2) $url = permalink(0, 'login');
					if ($logout == 3) $url = thunder_get_option('logout_uri_custom');
					if (isset($_REQUEST['redirect_to'])){
						$url = $_REQUEST['redirect_to'];
					}
					wp_logout();
					wp_redirect( $url );
					exit;
					
				} else {
				
					wp_redirect( permalink(0, 'login') );
					exit;
					
				}
			}
		}
	}
	add_action('template_redirect', 'thunder_logout_page');



	/******************************************
	Get valid file URI
	******************************************/
	function file_uri($url) {
		if (thunder_get_option('use_relative') == 'relative') {
			$url = parse_url($url, PHP_URL_PATH);
		}
		/*if (thunder_get_option('encode_url') == 1) {
			$url = urlencode($url);
		}*/
		return $url;
	}

	

	function thunder_get_avatar( $avatar, $id_or_email, $size, $default, $alt='' ) {
	$defaults = array(		
		'size'          => 128,
		'height'        => null,
		'width'         => null,			
	);

	if ( empty( $args ) ) {
		$args = array();
	}
	$args['size']    = (int) $size;	
	$args['alt']     = $alt;

	$args = wp_parse_args( $args, $defaults );
	$id_or_email = get_current_user_id();
	/*if ( empty( $args['height'] ) ) {
		$args['height'] = $args['size'];
	}
	if ( empty( $args['width'] ) ) {
		$args['width'] = $args['size'];
	}*/		
		//$size = 32;
		/*$url = get_stylesheet_directory_uri() . '/img/def-avatar.jpg';
		$args[ $url ] = 'Аватар сайта';
		return $args;*/
		//get_avatar();

		/*$id_or_email = get_current_user_id();
		$id = get_userdata( $user_id );		
		if (isset($id)){
			$id_or_email = $id;
		} elseif (is_email($id)){
			$user = get_user_by('email', $id);
			$id_or_email = $user->ID;
		}*/
		
		if ($id_or_email && thunder_profile_data( 'ava_picture', $id_or_email ) ) {
			
			$src = file_uri(  thunder_profile_data( 'ava_picture', $id_or_email ) );
			//$crop = THUNDER_DIR."lib/timthumb.php?src=".$url."&amp;w=$size&amp;h=$size&amp;a=t&amp;q=100";
			//$url = THUNDER_DIR."lib/timthumb.php?src=".$src."&amp;w=$size&amp;h=$size&amp;a=t&amp;q=100";
			//$src = THUNDER_REG_URL . $src;
			/*$params = array(
			    'width' => 128,
			    'height' => 128,
			    'crop' => true
			);
			$url = bfi_thumb( $src, $params );*/
			
			$ava['custom'] = '<img src="'.$src.'" alt="thunder-avatar" class="modified avatar" />';
		
		} else {
		
			if ($id_or_email && thunder_profile_data( 'gender', $id_or_email ) ) {
				$gender = strtolower( thunder_profile_data( 'gender', $id_or_email ) );
			} else {
				$gender = 'male'; // default gender
			}

			//$defava = get_option( 'avatar_default', 'mystery' );
			//	//$size = 40;
				//$alt = 'cc';
				$url = THUNDER_REG_URL . 'css/img/default_avatar_'.$gender.'.jpg';
				$ava['def_ava'] = '<img src="'.$url.'" width="'.$args['size'].'" height="'.$args['size'].'" alt="default-avatar" class="default avatar" />';
			/*if ( ! $ava['default'] || $defava == $ava['default']) {				
			}*/
			//$def = THUNDER_DIR.'img/default_avatar_'.$gender.'.jpg';			
		
		}

		/*if ( thunder_profile_data( 'ava_picture', $id_or_email ) != '') {
			return $ret;
		} else {*/
			if ( thunder_get_option('default_avatars') == 1 && $id_or_email == 0 ) {
				return $ava['def_ava'];
			} else if ( thunder_profile_data( 'ava_picture', $id_or_email ) != '' ) {
				return $ava['custom'];
			} else {
				return $ava['def_ava'];
			}
		//}
	}
	add_filter('get_avatar', 'thunder_get_avatar', 100, 5 );








	/******************************************
	Unique display names
	******************************************/
	function display_name_exists($display_name) {
		$users = get_users(array(
			'meta_key'     => 'display_name',
			'meta_value'   => $display_name,
			'meta_compare' => '='
		));
		if ( isset($users[0]->ID) && ( $users[0]->ID == get_current_user_id()) ) {
			return false;
		} elseif ( current_user_can('manage_options') ) {
			return false;
		} elseif ( isset($users[0]->ID) ) {
			return true;
		}
		return false;
	}

		/******************************************
	Make display_name unique
	******************************************/
	function unique_display_name($display_name){
		$r = str_shuffle("0123456789");
		$r1 = (int) $r[0];
		$r2 = (int) $r[1];
		$display_name = $display_name . $r1 . $r2;
		return $display_name;
	}

		/******************************************
	Save user profile ava_picture from facebook
	******************************************/
	function facebook_save_profile_pic($user_id, $ava_picture, $method=null){
		$method = thunder_get_option('picture_save_method');
		$unique_id = uniqid();
		if ($method == 'internal') {
		
			//do_uploads_dir( $user_id );
			//move_file( $user_id, $profilepicture, $unique_id . '.jpg' );
			//update_user_meta($user_id, 'picture', get_uploads_url($user_id) . $unique_id . '.jpg' );
			
		} else {
		
			update_user_meta($user_id, 'ava_picture', $profilepicture );
			
		}
	}


		/******************************************
	Strip weird chars from value
	******************************************/
	function remove_denied_chars($val, $field=null){
		$val = preg_replace('/(?=\P{Nd})\P{L} /u', '', $val);
		if ($field == 'display_name'){
			if (!userpro_get_option('allow_dash_display_name')){
				$val = str_replace('-','',$val);
			}
		} else {
			$val = str_replace('-','',$val);
		}
		$val = str_replace('&','',$val);
		$val = str_replace('+','',$val);
		$val = str_replace("'",'',$val);
		return $val;
	}

	/* Update user profile from facebook */
	function thunder_update_profile_via_facebook($user_id, $array) {
		//global $thunder;
		extract($array);
		
		if ( thunder_is_logged_in() && ( $user_id != get_current_user_id() ) && !current_user_can('manage_options') )
			die();
		
		//if ($id && $id != 'undefined') { update_user_meta($user_id, 'thunder_facebook_id', $id); }
		
		if ($first_name && $first_name != 'undefined'){ update_user_meta($user_id, 'first_name', $first_name); }
		if ($last_name && $last_name != 'undefined') { update_user_meta($user_id, 'last_name', $last_name); }
		
		if ($gender && $gender != 'undefined') { update_user_meta($user_id, 'gender', $gender); }
		
		if ($link && $link != 'undefined') { update_user_meta($user_id, 'facebook', $link); }
		
		/* begin display name */
		if ($name && $name != 'undefined') {
			$display_name = $name;
		} else if ($first_name && $last_name) {
			$display_name = $first_name . ' ' . $last_name;
		} else if ($email) {
			$display_name = $email;
		}
		
		if ($display_name) {
			if (display_name_exists( $display_name )){
				$display_name = unique_display_name($display_name);
			}
			$display_name = remove_denied_chars($display_name);
			wp_update_user( array('ID' => $user_id, 'display_name' => $display_name ) );
			update_user_meta($user_id, 'display_name', $display_name);
		}
		/* end display name */
		
		//do_action('thunder_after_profile_updated_fb');
		
	}