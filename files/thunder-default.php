<?php


	/* get plugin version */
	function thunder_version() {
		$plugin_data = get_plugin_data( thunder_path . 'index.php' );
		$plugin_version = $plugin_data['Version'];
		$plugin_version = str_replace('.','',$plugin_version);
		return $plugin_version;
	}
	
	/* check if update is installed */
	function thunder_update_installed($ver) {
		if (get_option("thunder_update_{$ver}")) {
			return true;
		}
		return false;
	}

		/* gets a selected value 
	function thunder_is_selected($k, $arr){
		if (isset($arr) && is_array($arr) && in_array($k, $arr)) {
			echo 'selected="selected"';
		} elseif ( $arr == $k ) {
			echo 'selected="selected"';
		}
	}
	
	 //get roles 
	function thunder_get_roles( $filter ) {
		if ( ! isset( $wp_roles ) ) $wp_roles = new WP_Roles();
		$roles = $wp_roles->get_names();
		$allowed_roles[0] = __('&mdash; Select account role &mdash;','thunder');
		foreach($roles as $k=>$v) {
			if (in_array($k, $filter)){
			$allowed_roles[$k] = $v;
			}
		}
		if (current_user_can('manage_options')){
		foreach($roles as $k=>$v) {
			$allowed_roles[$k] = $v;
		}
		}
		return $allowed_roles;
	}*/

	
	/* get a global option */
	function thunder_get_option( $option ) {
		$thunder_default = thunder_default();
		$settings = get_option('thunder-registration');
		switch($option){
		
			default:
				if (isset($settings[$option])){
					return $settings[$option];
				} else {
					if (isset($thunder_default[$option])){
					return $thunder_default[$option];
					}
				}
				break;
	
		}
	}

		 //get roles 
	function thunder_get_roles( $filter ) {
		if ( ! isset( $wp_roles ) ) $wp_roles = new WP_Roles();
		$roles = $wp_roles->get_names();
		$allowed_roles[0] = __('&mdash; Select account role &mdash;','thunder');
		foreach($roles as $k=>$v) {
			if (in_array($k, $filter)){
			$allowed_roles[$k] = $v;
			}
		}
		if (current_user_can('manage_options')){
		foreach($roles as $k=>$v) {
			$allowed_roles[$k] = $v;
		}
		}
		return $allowed_roles;
	}
	
	/* set a global option */
	function thunder_set_option($option, $newvalue){
		$settings = get_option('thunder-registration');
		$settings[$option] = $newvalue;
		update_option('thunder-registration', $settings);
	}
	
	/* default options */
	function thunder_default(){
	
		$mail_secretkey = __('Hi there,') . "\r\n\r\n";
		$mail_secretkey .= __("You or someone else has requested to change password for this account.","thunder") . "\r\n\r\n";
		$mail_secretkey .= __("The following key was generated to you to be able to change your passsword. Login to our site and attempt to Change your Password and use that key to change your password successfully.","thunder") . "\r\n\r\n";
		$mail_secretkey .= __('Secret Key: {VAR1}','thunder') . "\r\n\r\n";
		$mail_secretkey .= __('If you have any problems, please contact us at {thunder_ADMIN_EMAIL}.','thunder') . "\r\n\r\n";
		$mail_secretkey .= __('Best Regards!','thunder');
		
		$mail_verifyemail = __('Hi there,') . "\r\n\r\n";
		$mail_verifyemail .= __("Thanks for signing up at {thunder_BLOGNAME}. You must confirm/validate your account before logging in.","thunder") . "\r\n\r\n";
		$mail_verifyemail .= __("Please click on the following link to successfully activate your account:","thunder") . "\r\n";
		$mail_verifyemail .= "{thunder_VALIDATE_URL}" . "\r\n\r\n";
		$mail_verifyemail .= __('If you have any problems, please contact us at {thunder_ADMIN_EMAIL}.','thunder') . "\r\n\r\n";
		$mail_verifyemail .= __('Best Regards!','thunder');
		
		$mail_newaccount = __('Hi there,') . "\r\n\r\n";
		$mail_newaccount .= __("Thanks for registering. Your account is now active.","thunder") . "\r\n\r\n";
		$mail_newaccount .= __("To login please visit the following URL:","thunder") . "\r\n";
		$mail_newaccount .= "{thunder_LOGIN_URL}" . "\r\n\r\n";
		$mail_newaccount .= __('Your account e-mail: {thunder_EMAIL}','thunder') . "\r\n";
		$mail_newaccount .= __('Your account username: {thunder_USERNAME}','thunder') . "\r\n";
		$mail_newaccount .= __('Your account password: {VAR1}','thunder') . "\r\n\r\n";
		$mail_newaccount .= __('If you have any problems, please contact us at {thunder_ADMIN_EMAIL}.','thunder') . "\r\n\r\n";
		$mail_newaccount .= __('Best Regards!','thunder');
		
		//$mail_verifyinvitе При кликe в админке на users
		$mail_verifyinvite = __('Hi there,') . "\r\n\r\n";
		$mail_verifyinvite .= __("This is an invitation to get verified at {thunder_BLOGNAME}.","thunder") . "\r\n\r\n";
		$mail_verifyinvite .= __("To accept this invitation and get verified instantly, please click the following link:","thunder") . "\r\n";
		$mail_verifyinvite .= "{thunder_ACCEPT_VERIFY_INVITE}" . "\r\n\r\n";
		$mail_verifyinvite .= __('If you do not want to GET VERIFIED, please ignore this email. No further action is required.','thunder') . "\r\n\r\n";
		$mail_verifyinvite .= __('If you have any further questions, please contact us at {thunder_ADMIN_EMAIL}.','thunder') . "\r\n\r\n";
		$mail_verifyinvite .= __('Best Regards!','thunder');
		
		$mail_accountdeleted = __('Hi there,') . "\r\n\r\n";
		$mail_accountdeleted .= __("Your account has been deleted from {thunder_BLOGNAME}.","thunder") . "\r\n\r\n";
		$mail_accountdeleted .= __('If you have any further questions, please contact us at {thunder_ADMIN_EMAIL}.','thunder') . "\r\n\r\n";
		$mail_accountdeleted .= __('Best Regards!','thunder');
		
		$mail_accountverified = __('Hi there,') . "\r\n\r\n";
		$mail_accountverified .= __("Your account is now verified at {thunder_BLOGNAME}.","thunder") . "\r\n\r\n";
		$mail_accountverified .= __('If you have any further questions, please contact us at {thunder_ADMIN_EMAIL}.','thunder') . "\r\n\r\n";
		$mail_accountverified .= __('Best Regards!','thunder');
		
		$mail_accountunverified = __('Hi there,') . "\r\n\r\n";
		$mail_accountunverified .= __("We apologize. Your account is no longer verified at {thunder_BLOGNAME}.","thunder") . "\r\n\r\n";
		$mail_accountunverified .= __('If you have any further questions, please contact us at {thunder_ADMIN_EMAIL}.','thunder') . "\r\n\r\n";
		$mail_accountunverified .= __('Best Regards!','thunder');
		
		$mail_admin_pendingapprove = __('Hi there,') . "\r\n\r\n";
		$mail_admin_pendingapprove .= __("{thunder_USERNAME} has just created a new account at {thunder_BLOGNAME}. The account is pending your manual review.","thunder") . "\r\n\r\n";
		$mail_admin_pendingapprove .= __("To approve/reject new user registrations, please click the following link:","thunder") . "\r\n";
		$mail_admin_pendingapprove .= "{thunder_PENDING_REQUESTS_URL}" . "\r\n\r\n";
		$mail_admin_pendingapprove .= __('This is an automated notification that was sent to you by thunder. No further action is needed.','thunder');
		
		$mail_admin_newaccount = __('Hi there,') . "\r\n\r\n";
		$mail_admin_newaccount .= __("{thunder_USERNAME} has just created a new account at {thunder_BLOGNAME}.","thunder") . "\r\n\r\n";
		$mail_admin_newaccount .= __("You can check his profile via the following link:","thunder") . "\r\n";
		$mail_admin_newaccount .= "{thunder_PROFILE_LINK}" . "\r\n\r\n";
		$mail_admin_newaccount .= __('This is an automated notification that was sent to you by thunder. No further action is needed.','thunder');
		
		$mail_admin_accountdeleted = __('Hi there,') . "\r\n\r\n";
		$mail_admin_accountdeleted .= __("{thunder_USERNAME}'s profile has been just deleted from {thunder_BLOGNAME}.","thunder") . "\r\n\r\n";
		$mail_admin_accountdeleted .= __('This is an automated notification that was sent to you by thunder. No further action is needed.','thunder');
		
		$mail_admin_profileupdate = __('Hi there,') . "\r\n\r\n";
		$mail_admin_profileupdate .= __("{thunder_USERNAME} has just updated their profile at {thunder_BLOGNAME}.","thunder") . "\r\n\r\n";
		$mail_admin_profileupdate .= __("To view his/her profile:","thunder") . "\r\n";
		$mail_admin_profileupdate .= "{thunder_PROFILE_LINK}" . "\r\n\r\n";
		$mail_admin_profileupdate .= __('This is an automated notification that was sent to you by thunder. No further action is needed.','thunder');
			
		$array['thunder_restricted_pages'] = '';
		$array['restricted_page_verified'] = 0;
		
		$array['allowed_roles'] = array('subscriber');
		$array['admin_page_after_login'] = 1;
		$array['users_login_redirect'] = 'profile';
		$array['login_redirect'] = '';	
		$array['register_redirect'] = '';
			
		$array['users_approve'] = '1';
		$array['after_register_autologin'] = 1;
		$array['users_login_after_register'] = 'no_redirect';
		$array['user_display_name'] = 'display_name';
		$array['user_display_name_key'] = '';
		$array['default_role'] = 'subscriber'; //for register
		$array['mail_from'] = get_option('admin_email');
		$array['mail_from_name'] = get_bloginfo('name');
		$array['mail_verifyemail'] = $mail_verifyemail;
		$array['mail_secretkey'] = $mail_secretkey;
		$array['mail_newaccount'] = $mail_newaccount;
		$array['mail_accountdeleted'] = $mail_accountdeleted;
		$array['mail_verifyinvite'] = $mail_verifyinvite;
		$array['mail_accountverified'] = $mail_accountverified;
		$array['mail_accountunverified'] = $mail_accountunverified;
		$array['mail_admin_pendingapprove'] = $mail_admin_pendingapprove;
		$array['mail_admin_newaccount'] = $mail_admin_newaccount;
		$array['mail_admin_accountdeleted'] = $mail_admin_accountdeleted;
		$array['mail_admin_profileupdate'] = $mail_admin_profileupdate;
		$array['permalink_type'] = 'username';
		$array['hidden_from_view'] = 'display_name,picture,facebook,twitter,google_plus,user_email,user_url,phone_number,custom_profile_color,custom_profile_bg';
		$array['slug'] = 'user';
		$array['slug_register'] = 'register';
		$array['slug_edit'] = 'edit';
		$array['slug_login'] = 'login';
		$array['slug_logout'] = 'logout';
		$array['slug_members'] = 'members';
		$array['default_avatars'] = 0; //меняем в настройках админки thunder
		$array['use_relative'] = 'relative';
		//$array['encode_url'] = 1;
		$array['avatar_size'] = 128;
 		$array['notify_admin_profile_save'] = 1; //когда сохраняется поля на edit.php

		$array['facebook_app_id'] = '';
		$array['facebook_connect'] = 1;
		$array['facebook_autopost'] = 0;
		$array['facebook_autopost_name'] = '';
		$array['facebook_autopost_body'] = '';
		$array['facebook_autopost_caption'] = '';
		$array['facebook_autopost_description'] = '';
		$array['facebook_autopost_link'] = '';
		$array['picture_save_method'] = 'internal'; //при сохранении картинки с facebook
		$array['allow_dash_display_name'] = 0; //
		$array['logout_uri'] = 1;
		$array['logout_uri_custom'] = '';

		$array['backend_users_change'] = 1; // страница админки users
		$array['file_allowed_extensions'] = 'pdf,txt,zip,doc';




		
		$array['dashboard_redirect_users'] = 1;
		$array['profile_redirect_users'] = 1;
		$array['login_redirect_users'] = 1;
		$array['register_redirect_users'] = 1;
		$array['dashboard_redirect_users_url'] = '';
		$array['profile_redirect_users_url'] = '';
		$array['login_redirect_users_url'] = '';
		$array['register_redirect_users_url'] = '';
		$array['allow_guests_view_profiles'] = 1;
		$array['allow_users_view_profiles'] = 1;
		$array['notify_user_verified'] = 1;
		$array['notify_user_unverified'] = 1;		
		$array['notify_admin_profile_remove'] = 1;
		$array['user_can_delete_profile'] = 1;
		$array['skin'] = 'default';
		$array['layout'] = 'float';
		$array['twitter_consumer_key'] = '';
		$array['twitter_consumer_secret'] = '';
		$array['twitter_connect'] = 1;
		$array['twitter_autopost'] = 0;
		$array['twitter_autopost_msg'] = '';
		$array['google_client_id'] = '';
		$array['google_client_secret'] = '';
		$array['google_redirect_uri'] = add_query_arg('upslug', 'gplus', trailingslashit( home_url() ) );
		$array['google_connect'] = 1;

		$array['reset_admin_pass'] = 1;
		$array['allow_users_verify_request'] = 1;
		
		$array['admin_user_notices'] = 1;
		$array['show_user_notices'] = 1;
		$array['show_user_notices_him'] = 1;
		$array['users_can_register'] = 1;
		$array['width'] = '480px';
		$array['googlefont'] = 'Roboto';
		$array['customfont'] = '';
		$array['field_icons'] = 1;
		$array['hide_admin_bar'] = 1;
		$array['modstate_social'] = 1;
		$array['terms_agree'] = 1;
		$array['terms_agree_text'] = __('To complete registration, you must read and agree to our <a href="#">terms and conditions</a>. This text can be custom.','thunder');
		$array['verified_link'] = '';
		$array['verified_badge_by_name'] = 1;
		$array['mailchimp_api'] = '';
		$array['modstate_online'] = 0;
		$array['modstate_showoffline'] = 0;
		$array['thumb_style'] = 'default';
		$array['heading_light'] = 'Light';
		$array['unverify_on_namechange'] = 1;		
		$array['homepage_guest_lockout'] = '';
		$array['homepage_member_lockout'] = '';
		$array['site_guest_lockout'] = 0;
		$array['site_guest_lockout_pageid'] = '';
		$array['show_flag_in_profile'] = 1;
		$array['show_flag_in_badges'] = 1;
		
		$array['jquery_ui_style'] = 'pepper-grinder';
		return apply_filters('thunder_default_options_array', $array);
	}
	
	/*	Нужно оставить только массив thunder-body

	function login_styles() {
		    $groups = array(
		        'thunder_login_style' => array(
					'thunder-body' => array(
						'th_css_body_background_color' => '#000',
						'th_css_body_border_number' => 2,
						'th_css_body_border_select' => 'solid',
						'th_css_body_border_color' => '#e4e4e4',
					),
				),
			);		
			return apply_filters('thunder_login_styles_array', $groups);
		}*/













	/* gets a selected value */
/*	function thunder_is_selected($k, $arr){
		if (isset($arr) && is_array($arr) && in_array($k, $arr)) {
			echo 'selected="selected"';
		} elseif ( $arr == $k ) {
			echo 'selected="selected"';
		}
	}*/
	
	/* get roles */
/*	function thunder_get_roles( $filter ) {
		if ( ! isset( $wp_roles ) ) $wp_roles = new WP_Roles();
		$roles = $wp_roles->get_names();
		$allowed_roles[0] = __('&mdash; Select account role &mdash;','thunder');
		foreach($roles as $k=>$v) {
			if (in_array($k, $filter)){
			$allowed_roles[$k] = $v;
			}
		}
		if (current_user_can('manage_options')){
		foreach($roles as $k=>$v) {
			$allowed_roles[$k] = $v;
		}
		}
		return $allowed_roles;
	}


	function thunder_fields_group_by_template( $template, $group='default' ) {
		$array = get_option("thunder_fields_groups");
		if (isset($array[$template][$group])) {			
				return (array)$array[$template][$group];
		}
		return array('');
	}*/

		/* Retrieves a field */
	/*function thunder_add_field($field, $hideable=0, $hidden=0, $required=0, $ajaxcheck=null) {
		$fields = get_option('thunder_fields');
		$array = $fields[$field];
		$array['hideable'] = $hideable;
		$array['hidden'] = $hidden;
		$array['required'] = $required;
		$array['ajaxcheck'] = $ajaxcheck;
		return $array;
	}*/
	
	/* Assign a section */
	/*function thunder_add_section($name, $collapsible=0, $collapsed=0) {
		$array = array(
			'heading' => $name,
			'collapsible' => $collapsible,
			'collapsed' => $collapsed
		);
		return $array;
	}*/



	add_action('init', 'thunder_init_setup', 11);
	function thunder_init_setup() {
		global $thunder;
		
		$newrow = __('New row', 'thunder');

		if (!empty($thunder[$fields]) && !get_option('thunder_pre_icons_setup')){
			update_field_icons();
		}
		if (!get_option('thunder_pre_icons_setup') ) {
			update_field_icons();
		}

		/* Setup Fields */
		if (!get_option('thunder_fields')){
		$thunder_fields = array( 
			'first_name' => array(					
				'flaggroup' => 'input',
				'label' => 'First Name',
				'help' => '',
				'hidden' => 0,
				'hideable' => 0,
				'html' => 0,
				'locked' => 0,				
				'placeholder' => '',
				'private' => 0,
				'required' => 0,
				'row' => 'newrow',
				'type' => 'text',
				'zone' => 'thead',
				),
			'last_name' => array(
				'flaggroup' => 'input',
				'label' => 'Last Name',
				'help' => '',
				'hidden' => 0,
				'hideable' => 0,
				'html' => 0,
				'locked' => 0,				
				'placeholder' => '',
				'private' => 0,
				'required' => 0,
				'row' => 'newrow',
				'type' => 'text',
				'zone' => 'thead',
				),
			'display_name' => array(
				'flaggroup' => 'input',				
				'label' => 'Display Profile Name',
				'help' => 'Your profile name or nickname that is displayed to public.',
				'hidden' => 0,
				'hideable' => 0,
				'html' => 0,
				'locked' => 0,				
				'placeholder' => '',
				'private' => 0,
				'required' => 0,
				'row' => 'newrow',
				'type' => 'text',
				'zone' => 'thead',
				),
			'user_login' => array(
				'flaggroup' => 'input',				
				'label' => 'Username',
				'help' => '',
				'hidden' => 0,
				'hideable' => 0,
				'html' => 0,
				'locked' => 0,				
				'placeholder' => '',
				'private' => 0,
				'required' => 0,
				'row' => 'newrow',
				'type' => 'text',
				'zone' => 'thead',
				),
			'user_email' => array(
				'flaggroup' => 'input',
				'label' => 'Email Address',
				'help' => '',
				'hidden' => 0,
				'hideable' => 0,
				'html' => 0,
				'locked' => 0,				
				'placeholder' => '',
				'private' => 0,
				'required' => 0,
				'row' => 'newrow',
				'type' => 'text',
				'zone' => 'thead',
				),
			'username_or_email' => array(
				'flaggroup' => 'input',
				'label' => 'Username or E-mail',
				'help' => '',
				'hidden' => 0,
				'hideable' => 0,
				'html' => 0,
				'locked' => 0,				
				'placeholder' => '',
				'private' => 0,
				'required' => 0,
				'type' => 'text',				
				'row' => 'newrow',
				'zone' => 'thead',
				),
			'user_password' => array(
				'flaggroup' => 'input',
				'label' => 'Password',
				'help' => 'Your password must be 8 characters long at least.',				
				'hidden' => 0,
				'hideable' => 0,
				'html' => 0,
				'locked' => 0,				
				'placeholder' => '',
				'private' => 0,
				'required' => 0,
				'type' => 'password',
				'row' => 'newrow',
				'zone' => 'thead',				
				),
			'user_password_confirm' => array(
				'flaggroup' => 'input',
				'label' => 'Confirm your Password',
				'help' => '',
				'hidden' => 0,
				'hideable' => 0,
				'html' => 0,
				'locked' => 0,				
				'placeholder' => '',
				'private' => 0,
				'required' => 0,
				'row' => 'newrow',
				'type' => 'password',
				'zone' => 'thead',
				),
			'passwordstrength' => array(
				'flaggroup' => 'passwordstrength',
				'label' => 'Password Strength Meter',
				'too_short' => 'Password too short',
				'very_strong' => 'Very Strong',
				'strong' => 'Strong',
				'good' => 'Good',
				'weak' => 'Weak',
				'very_weak' => 'Very Weak',
				'help' => '',
				'hidden' => 0,
				'hideable' => 0,
				'html' => 0,
				'locked' => 0,				
				'placeholder' => '',
				'private' => 0,
				'required' => 0,
				'row' => 'newrow',
				'type' => 'passwordstrength',
				'zone' => 'thead',
				),
			'country' => array(
				'flaggroup' => 'input',
				'label' => 'Country/Region',
				'options' => thunder_filter_to_array('country'),
				'placeholder' => 'Select your country',
				'help' => '',
				'hidden' => 0,
				'hideable' => 0,
				'html' => 0,
				'locked' => 0,	
				'private' => 0,
				'required' => 0,
				'row' => 'newrow',
				'type' => 'select',
				'zone' => 'thead',
				),
			'role' => array(
				'flaggroup' => 'input',
				'label' => 'Role',
				'options' => thunder_filter_to_array('role'),
				'placeholder' => 'Select your account type',
				'help' => '',
				'hidden' => 0,
				'hideable' => 0,
				'html' => 0,
				'locked' => 0,	
				'private' => 0,
				'required' => 0,
				'row' => 'newrow',
				'type' => 'role',
				'zone' => 'thead',
				),
			'gender' => array(
				'flaggroup' => 'radio',
				'label' => 'Gender',
				'options' => array(
					'male' => 'Male', 
					'female' => 'Female'
					),
				'help' => '',
				'hidden' => 0,
				'hideable' => 0,
				'html' => 0,
				'locked' => 0,				
				'placeholder' => '',
				'private' => 0,
				'required' => 0,
				'row' => 'newrow',
				'type' => 'radio',
				'zone' => 'thead',
				),
			'ava_picture' => array(
				'flaggroup' => 'avatar_picture',
				'label' => 'File Uploder a profile picture',
				'button_text' => 'Upload a profile picture',
				'help' => 'Upload a picture that presents you across the site.',
				'help' => '',
				'hidden' => 0,
				'hideable' => 0,
				'html' => 0,
				'locked' => 0,				
				'placeholder' => '',
				'private' => 0,
				'required' => 0,
				'row' => 'newrow',
				'type' => 'ava_picture',
				'zone' => 'thead',
				),
			'file_doc' => array(
				'flaggroup' => 'file_doc',
				'label' => 'File Uploder',
				'button_text' => 'Upload your files',
				'help' => 'Upload files to our site.',
				'help' => '',
				'hidden' => 0,
				'hideable' => 0,
				'html' => 0,
				'locked' => 0,				
				'placeholder' => '',
				'private' => 0,
				'required' => 0,
				'row' => 'newrow',
				'type' => 'file',
				'zone' => 'thead',
				),			
			'description' => array(
				'flaggroup' => 'textarea',
				'label' => 'Describe yourself',
				'help' => 'Describe yourself.',
				'html' => 1,
				'hidden' => 0,
				'hideable' => 0,
				'html' => 0,
				'locked' => 0,				
				'placeholder' => '',
				'private' => 0,
				'required' => 0,
				'row' => 'newrow',
				'type' => 'textarea',
				'zone' => 'thead',
				),
			'facebook' => array(
				'flaggroup' => 'input',
				'label' => 'Facebook Page',
				'help' => '',
				'hidden' => 0,
				'hideable' => 0,
				'html' => 0,
				'locked' => 0,				
				'placeholder' => '',
				'private' => 0,
				'required' => 0,
				'row' => 'newrow',
				'type' => 'text',
				'zone' => 'thead',
				),
			'twitter' => array(	
				'flaggroup' => 'input',
				'label' => 'Twitter',
				'help' => '',
				'hidden' => 0,
				'hideable' => 0,
				'html' => 0,
				'locked' => 0,				
				'placeholder' => '',
				'private' => 0,
				'required' => 0,
				'row' => 'newrow',
				'type' => 'text',
				'zone' => 'thead',
				),
			'google_plus' => array(
				'flaggroup' => 'input',
				'label' => 'Google+',
				'help' => '',
				'hidden' => 0,
				'hideable' => 0,
				'html' => 0,
				'locked' => 0,				
				'placeholder' => '',
				'private' => 0,
				'required' => 0,
				'row' => 'newrow',
				'type' => 'text',
				'zone' => 'thead',
				),
			'user_url' => array(
				'flaggroup' => 'input',				
				'label' => 'Website (URL)',
				'help' => '',
				'hidden' => 0,
				'hideable' => 0,
				'html' => 0,
				'locked' => 0,				
				'placeholder' => '',
				'private' => 0,
				'required' => 0,
				'row' => 'newrow',
				'type' => 'text',
				'zone' => 'thead',
				),
			'logo_img' => array(
				'flaggroup' => 'logo',
				'label' => 'Logo img',
				'help' => '',
				'hidden' => 0,
				'hideable' => 0,
				'html' => 0,
				'locked' => 0,				
				'placeholder' => '',
				'private' => 0,
				'required' => 0,				
				'zone' => 'thead',				
				'type' => 'logo_img',
				'row' => 'newrow',
				'zone' => 'thead',
				),
			'user_submit' => array(
				'flaggroup' => 'submit',
				'label' => 'Button Submit',
				'help' => '',
				'hidden' => 0,
				'hideable' => 0,
				'html' => 0,
				'locked' => 0,				
				'placeholder' => '',
				'private' => 0,	
				'required' => 0,								
				'zone' => 'bottom',					
				'type' => 'user_submit',
				'row' => 'samerow',
				'zone' => 'thead',
			),
			'fields_trigger' => array(
				'flaggroup' => 'fields_trigger',
				'label' => 'Button trigger for next or previous display',
				'help' => '',
				'hidden' => 0,
				'hideable' => 0,
				'html' => 0,
				'locked' => 0,				
				'placeholder' => '',
				'private' => 0,	
				'required' => 0,								
				'type' => 'fields_trigger',
				'row' => 'samerow',				
				'zone' => 'thead',					
			),
			'user_lost' => array(
				'flaggroup' => 'passremember',
				'label' => 'Forgot your password?',
				'help' => '',
				'hidden' => 0,
				'hideable' => 0,
				'html' => 0,
				'locked' => 0,				
				'placeholder' => '',
				'private' => 0,	
				'required' => 0,								
				'type' => 'user_lost',
				'row' => 'newrow',
				'zone' => 'thead',					
			),
			'form_name' => array(
				'flaggroup' => 'form_name',
				'label' => 'Form Name',
				'help' => '',
				'hidden' => 0,
				'hideable' => 0,
				'html' => 0,
				'locked' => 0,				
				'placeholder' => '',
				'private' => 0,	
				'required' => 0,								
				'type' => 'form_name',
				'row' => 'newrow',
				'zone' => 'thead',					
			),
			'facebook_sdk' => array(
				'flaggroup' => 'social',
				'label' => 'Login with facebook',
				'help' => 'Connect with Facebook',
				'hidden' => 0,
				'hideable' => 0,
				'html' => 0,
				'locked' => 0,				
				'placeholder' => '',
				'private' => 0,	
				'required' => 0,								
				'type' => 'facebook_sdk',
				'row' => 'samerow',
				'zone' => 'thead',					
			),			
		); 

		update_option('thunder_fields', $thunder_fields);
		update_option('thunder_fields_restore', $thunder_fields); //  restore default fields
		}

		/* Setup Field Groups */
		
		if (!get_option('thunder_fields_groups')){
		$thunder_fields_groups['register']['default'] = array(
			'tcontent' => array(
				'accountinfo' => thunder_add_section( __('Account Details','thunder'), 1, 0 ),
				'user_login' => thunder_add_field('user_login', 'tcontent', 'Username', 0, 0, 1),
				'user_email' => thunder_add_field('user_email', 'tcontent', 'Email Address', 1, 0, 1),
				'user_password' => thunder_add_field('user_password', 'tcontent', 'Password', 0, 0, 1),
				'user_password_confirm' => thunder_add_field('user_password_confirm', 'tcontent', 'Confirm your Password', 0, 0, 0),
				'passwordstrength' => thunder_add_field('passwordstrength', 'tcontent', 'Password Strength Meter', 0, 0, 0),
				
				'profile' => thunder_add_section( __('Profile Details','thunder'), 1, 1 ),
				'display_name' => thunder_add_field('display_name', 'tcontent', 'Display Profile Name', 0, 0, 0),
				'ava_picture' => thunder_add_field('ava_picture', 'tcontent', 'Profile Picture', 0, 0, 0),
				'gender' =>  thunder_add_field('gender', 'tcontent', 'Gender', 0, 0, 0),
				'country' =>  thunder_add_field('country', 'tcontent', 'Country/Region', 0, 0, 0),
				
				'social' => thunder_add_section( __('Social Profiles','thunder'), 1, 1 ),
				'facebook' => thunder_add_field('facebook', 'tcontent', 'Facebook Page', 0, 0, 0),
				'twitter' => thunder_add_field('twitter', 'tcontent', 'Twitter Page', 0, 0, 0),
				'google_plus' => thunder_add_field('google_plus', 'tcontent', 'Google+ Page', 0, 0, 0),
				'user_url' => thunder_add_field('user_url', 'tcontent', 'Website (URL)', 0, 0, 0),
			),
			'tbottom' => array(				
				'user_submit' => thunder_add_field('user_submit', 'tbottom', 'Register', 0, 0, 0),				
				'fields_trigger' => thunder_add_field('fields_trigger', 'tbottom', 'Already have account?', 0, 0, 0),
			)	
			
		);
		//
		
		$thunder_fields_groups['login']['default'] = array(
			'thead' => array(
				'logo_img' => thunder_add_field('logo_img', 'thead', 'Logo img', 0, 0, 0),
				),
			'tcontent' => array(
				'form_name' => thunder_add_field(
					'form_name','tcontent', 'Form Name', 0, 0, 1), 				
				'username_or_email' => thunder_add_field(
					'username_or_email', 'tcontent', 'Username or E-mail', 0, 0, 1),
				'user_password' => thunder_add_field(
					'user_password','tcontent', 'Password', 0, 0, 1), 
				),
			'tbottom' => array(
				'user_submit' => thunder_add_field('user_submit', 'tbottom', 'Login', 0, 0, 0),
				'fields_trigger' => thunder_add_field('fields_trigger', 'tbottom', 'Create an account?', 0, 0, 0),
				'user_lost' => thunder_add_field('user_lost', 'tbottom', 'Forgot your password?', 0, 0, 0),
				)				
		);
		
		//В проекте поле 'role' нужно удалить из групы 'edit'!
		$thunder_fields_groups['edit']['default'] = array(
			'thead' => array(
				'ava_picture' => thunder_add_field('ava_picture', 'thead', 'Profile Picture', 0, 0, 0),
				),			
			'tcontent' => array(		
				'profile' => thunder_add_section( __('Profile Details','thunder'), 1, 0 ),				
				'gender' =>  thunder_add_field('gender', 'tcontent', 'Gender', 0, 0, 0),				
				'display_name' => thunder_add_field('display_name', 'tcontent', 'Display Profile Name', 0, 0, 0),				
				'first_name' => thunder_add_field('first_name', 'tcontent', 'First Name', 0, 0, 0),
				'last_name' => thunder_add_field('last_name', 'tcontent', 'Last Name', 0, 0, 0),
				'description' => thunder_add_field('description', 'tcontent', 'Describe yourself', 0, 0, 0),				
				'country' =>  thunder_add_field('country', 'tcontent', 'Country/Region', 0, 0, 0),
				
				'social' => thunder_add_section( __('Social Profiles','thunder'), 1, 0 ),
				'facebook' => thunder_add_field('facebook', 'tcontent', 'Facebook Page', 0, 0, 0),
				'twitter' => thunder_add_field('twitter', 'tcontent', 'Twitter Page', 0, 0, 0),
				'google_plus' => thunder_add_field('google_plus', 'tcontent', 'Google+ Page', 0, 0, 0),
				'user_url' => thunder_add_field('user_url', 'tcontent', 'Website (URL)', 0, 0, 0),
				
				'accountinfo' => thunder_add_section( __('Account Details','thunder'), 1, 0 ),
				'user_email' => thunder_add_field('user_email', 'tcontent', 'Email Address', 1, 0, 0),
				'user_password' => thunder_add_field('user_password', 'tcontent', 'Password', 0, 0, 0),
				'user_password_confirm' => thunder_add_field('user_password_confirm', 'tcontent', 'Confirm your Password', 0, 1, 0),
				'passwordstrength' => thunder_add_field('passwordstrength', 'tcontent', 'Password Strength Meter', 0, 1, 0),
			),
			'tbottom' => array(				
				'user_submit' => thunder_add_field('user_submit', 'tbottom', 'Button Submit', 0, 0, 0),
				'fields_trigger' => thunder_add_field('fields_trigger', 'tbottom', 'Login', 0, 0, 0),
			),				
			
		);
		
		$thunder_fields_groups['view']['default'] = $thunder_fields_groups['edit']['default'];
		
		$thunder_fields_groups['social']['default'] = array(
			'tcontent' => array(	
				'user_email' => thunder_add_field('user_email', 'tcontent', 'Email Address', 0, 0, 0),
				'facebook' => thunder_add_field('facebook', 'tcontent', 'Facebook Page', 0, 0, 0),
				'twitter' => thunder_add_field('twitter', 'tcontent', 'Twitter Page', 0, 0, 0),
				'google_plus' => thunder_add_field('google_plus', 'tcontent', 'Google+ Page', 0, 0, 0),
				'user_url' => thunder_add_field('user_url', 'tcontent', 'Website (URL)', 0, 0, 0)
			),
		);

		$thunder_fields_styles = array(
			'login' => array(
				'thead' => 'ok'
			),
		);
		
		update_option('thunder_fields_groups', $thunder_fields_groups);
		update_option('thunder_fields_groups_default', $thunder_fields_groups);
		update_option('thunder_fields_groups_default_register', $thunder_fields_groups['register']['default']);
		update_option('thunder_fields_groups_default_login', $thunder_fields_groups['login']['default']);
		update_option('thunder_fields_groups_default_edit', $thunder_fields_groups['edit']['default']);
		update_option('thunder_fields_groups_default_view', $thunder_fields_groups['edit']['default']);
		update_option('thunder_fields_groups_default_social', $thunder_fields_groups['social']['default']);
		}
		
	}


		/******************************************
	Update field icons
	******************************************/
	function update_field_icons() {		
		$fields = get_option('thunder_fields');
		if (isset($fields) && is_array($fields)){
		foreach($fields as $field => $arr){
			
			switch($field){
				default: $fields[$field]['icon'] = ''; break;
				case 'country': $fields['country']['icon'] = 'map-marker'; break;
				case 'user_email':  $fields['user_email']['icon'] = 'envelope-alt'; break;
				case 'user_login':  $fields['user_login']['icon'] = 'user'; break;
				case 'username_or_email':  $fields['username_or_email']['icon'] = 'user'; break;
				case 'user_pass':  $fields['user_pass']['icon'] = 'lock'; break;
				case 'facebook':  $fields['facebook']['icon'] = 'facebook'; break;
				case 'twitter':  $fields['twitter']['icon'] = 'twitter'; break;
				case 'google_plus':  $fields['google_plus']['icon'] = 'google-plus'; break;
				case 'ava_picture':  $fields['ava_picture']['icon'] = 'camera'; break;
				case 'user_url':  $fields['user_url']['icon'] = 'home'; break;
			}
			
		}
		update_option('thunder_fields', $fields);
		update_option('thunder_pre_icons_setup',1);
		}
	}







	add_action('init', 'thunder_links', 9);
	function thunder_links($rebuild=0) {
	
		/* Create optional pages */
		if ($rebuild) { delete_option('thunder_links'); }
		
		/* Find pages */
		$pages = get_option('thunder_links');
		
		/* Create pages if they do not exist */
		if (!isset($pages['user'])) {
		
			$slug = thunder_get_option('slug');
			$slug_edit = thunder_get_option('slug_edit');
			$slug_register = thunder_get_option('slug_register');
			$slug_login = thunder_get_option('slug_login');
			$slug_directory = thunder_get_option('slug_directory');
			$slug_logout = thunder_get_option('slug_logout');

			$logout_page = array(
				  'post_title'  		=> __('Logout','thunder'),
				  'post_content' 		=> '',
				  'post_name'			=> $slug_logout,
				  'comment_status' 		=> 'closed',
				  'post_type'     		=> 'page',
				  'post_status'   		=> 'publish',
				  'post_author'   		=> 1,
			);
			$logout_page = wp_insert_post( $logout_page );
			$pages['logout_page'] = $logout_page;
			$post = get_post($logout_page, ARRAY_A);
			thunder_set_option('slug_logout', $post['post_name']);

			$directory_page = array(
				  'post_title'  		=> __('Member Directory','thunder'),
				  'post_content' 		=> '[thunder tpl=memberlist]',
				  'post_name'			=> $slug_directory,
				  'comment_status' 		=> 'closed',
				  'post_type'     		=> 'page',
				  'post_status'   		=> 'publish',
				  'post_author'   		=> 1,
			);
			$directory_page = wp_insert_post( $directory_page );
			$pages['members_page'] = $directory_page;
			$post = get_post($directory_page, ARRAY_A);
			thunder_set_option('slug_members', $post['post_name']);
			
			$parent = array(
				  'post_title'  		=> __('My Profile','thunder'),
				  'post_content' 		=> '[thunder tpl=view]',
				  'post_name'			=> $slug,
				  'comment_status' 		=> 'closed',
				  'post_type'     		=> 'page',
				  'post_status'   		=> 'publish',
				  'post_author'   		=> 1,
			);
			$parent = wp_insert_post( $parent );
			$pages['user'] = $parent;
			$post = get_post($parent, ARRAY_A);
			thunder_set_option('slug', $post['post_name']);
			
			$edit = array(
				  'post_title'  		=> __('Edit Profile','thunder'),
				  'post_content' 		=> '[thunder tpl=edit]',
				  'post_name'			=> $slug_edit,
				  'comment_status' 		=> 'closed',
				  'post_type'     		=> 'page',
				  'post_status'   		=> 'publish',
				  'post_author'   		=> 1,
				  'post_parent'			=> $parent
			);
			$edit = wp_insert_post( $edit );
			$pages['edit'] = $edit;
			$post = get_post($edit, ARRAY_A);
			thunder_set_option('slug_edit', $post['post_name']);
			
			$register = array(
				  'post_title'  		=> __('Register','thunder'),
				  'post_content' 		=> '[thunder tpl=register]',
				  'post_name'			=> $slug_register,
				  'comment_status' 		=> 'closed',
				  'post_type'     		=> 'page',
				  'post_status'   		=> 'publish',
				  'post_author'   		=> 1,
				  'post_parent'			=> $parent
			);
			$register = wp_insert_post( $register );
			$pages['register'] = $register;
			$post = get_post($register, ARRAY_A);
			thunder_set_option('slug_register', $post['post_name']);
			
			$login = array(
				  'post_title'  		=> __('Login','thunder'),
				  'post_content' 		=> '[thunder tpl=login]',
				  'post_name'			=> $slug_login,
				  'comment_status' 		=> 'closed',
				  'post_type'     		=> 'page',
				  'post_status'   		=> 'publish',
				  'post_author'   		=> 1,
				  'post_parent'			=> $parent
			);
			$login = wp_insert_post( $login );
			$pages['login'] = $login;
			$post = get_post($login, ARRAY_A);
			thunder_set_option('slug_login', $post['post_name']);
			
			update_option('thunder_links', $pages);
				
			/* Rewrite rules */
			$slug = thunder_get_option('slug');
			$slug_edit = thunder_get_option('slug_edit');
			$slug_register = thunder_get_option('slug_register');
			$slug_login = thunder_get_option('slug_login');
			$slug_directory = thunder_get_option('slug_directory');
			$slug_logout = thunder_get_option('slug_logout');
			add_rewrite_rule("$slug/$slug_register",'index.php?pagename='.$slug.'/'.$slug_register, 'top');
			add_rewrite_rule("$slug/$slug_login",'index.php?pagename='.$slug.'/'.$slug_login, 'top');
			add_rewrite_rule("$slug/$slug_edit/([^/]+)/?",'index.php?pagename='.$slug.'/'.$slug_edit.'&usr=$matches[1]', 'top' );
			add_rewrite_rule("$slug/$slug_edit",'index.php?pagename='.$slug.'/'.$slug_edit, 'top' );
			add_rewrite_rule("$slug/([^/]+)/?",'index.php?pagename='.$slug.'&usr=$matches[1]', 'top');
			
			flush_rewrite_rules();
			
		} else {
		
			// pages installed
			$slug = thunder_get_option('slug');
			$slug_edit = thunder_get_option('slug_edit');
			$slug_register = thunder_get_option('slug_register');
			$slug_login = thunder_get_option('slug_login');
			$slug_directory = thunder_get_option('slug_directory');
			$slug_logout = thunder_get_option('slug_logout');
			add_rewrite_rule("$slug/$slug_register",'index.php?pagename='.$slug.'/'.$slug_register, 'top');
			add_rewrite_rule("$slug/$slug_login",'index.php?pagename='.$slug.'/'.$slug_login, 'top');
			add_rewrite_rule("$slug/$slug_edit/([^/]+)/?",'index.php?pagename='.$slug.'/'.$slug_edit.'&usr=$matches[1]', 'top' );
			add_rewrite_rule("$slug/$slug_edit",'index.php?pagename='.$slug.'/'.$slug_edit, 'top' );
			add_rewrite_rule("$slug/([^/]+)/?",'index.php?pagename='.$slug.'&usr=$matches[1]', 'top');
			
		}
	
	}

	// Setup query variables 
	add_filter( 'query_vars', 'thunder_uid_query_var' );
	function thunder_uid_query_var( $query_vars ) {
		$query_vars[] = 'usr';		
		return $query_vars;
	}