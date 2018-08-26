<?php 

add_shortcode( 'thunder', 'create_thunder' );
function create_thunder( $args ) {
	global $post, $wp;

	if ( is_home() ) {
		$url = home_url();
	} elseif ( isset($post->ID) ) {
		$url = get_permalink($post->ID);
	} else {
		$url = home_url();
	}


	$defaults = apply_filters('thunder_shortcode_args', array(
		'allow_section'						=> 1,
		'tpl' 							   => null,
		'url'								=> $url,
		'layout'							=>'default',
		'field_icons'						=> thunder_get_option('field_icons'),
		'allow_sections'					=> 1,
		'file_allowed_extensions'			=> thunder_get_option('file_allowed_extensions'),
		'profile_thumb_size'				=> thunder_get_option('avatar_size'),

		'link_target'						=> '_blank',
		'login_group'						=> 'default',
		//'login_header' 						=> __('Login','thunder'),
		//'login_passremember'				=> __('Forgot your password?','thunder'),
		//'login_passremember_action'			=> 'forgot',		
		//'login_button_action'				=> 'register',
		//'login_button_primary'				=> __('Login','thunder'),
		//'login_fields_trigger'			=> __('Create an Account','thunder'), 
		'login_fields_trigger_tpl'			=> 'register',								//Нужно в админки будет сделать выбор
		'login_redirect'					=> thunder_get_option('login_redirect'),
		'logout_redirect'					=> home_url(),

		'register_group'					=> 'default',
		'register_redirect'					=> '',
		//'register_fields_trigger'			=> __('Login','thunder'), 
		'register_fields_trigger_tpl'		=> 'login',
		'register_redirect'					=> home_url(),

		'view_group'						=> 'default',
		'view_fields_trigger_tpl'		    => 'edit',
		//'view_fields_trigger'				=> __('Edit Profile','thunder'),
		'edit_group'						=> 'default',
		'edit_fields_trigger_tpl'		=> 'login',

		'forgot_group'						=> 'default',
		//'forgot_heading'						=> __('Reset Password','thunder'),
		//'forgot_side'						=> __('Back to Login','thunder'),
		///'forgot_side_action'					=> 'login',
		////'forgot_button_action'				=> 'change',
		//'forgot_button_primary'				=> __('Request Secret Key','thunder'),
		//'forgot_fields_trigger'			=> __('Change your Password','thunder'),
		) );

		$args = wp_parse_args( $args, $defaults );
		extract( $args, EXTR_SKIP );

		//$array = get_option('thunder_fields_groups');
		//$groups = $array[$_POST['name']][$_POST['templ']];

		if ($tpl) {

			$master_id = 0;
		
			ob_start();

			$master_id = rand(1, 1000);
			switch ($tpl) {
				case 'login':
					if ( ! is_user_logged_in() ) {						
						$provider_id    = 'Facebook';
						$provider_name  = 'Facebook';


						$request_url = site_url( 'wp-login.php', 'login_post' ) . ( strpos( site_url( 'wp-login.php', 'login_post' ), '?' ) ? '&' : '?' ) . "action=thunder_social_request&mode=login";
                        			// build authentication url
                         $redirect_to = permalink();
                                           

                         $thunder_settings_use_popup = 2; // 1 for pop-up
						//$thunder_settings_use_popup = function_exists( 'wp_is_mobile' ) ? wp_is_mobile() ? 2 : $thunder_settings_use_popup : $thunder_settings_use_popup;
						$authenticate_url = $request_url . "&provider=" . $provider_id . "&redirect_to=" . urlencode( $redirect_to );

						/*if( $thunder_settings_use_popup == 1 &&  $auth_mode != 'test' ) {
								$authenticate_url= "javascript:void(0);";
						}*/
						// http://codex.wordpress.org/Function_Reference/esc_url
						$authenticate_url = esc_url( $authenticate_url );  

			        	include trailingslashit( THUNDER_DIR ) . "forms/$tpl.php"; 
			        } else {						
						$user_id = get_current_user_id();
						include trailingslashit( THUNDER_DIR ) . "forms/logout.php";						
					}
				break;
				case 'forgot': 				
					if ( ! is_user_logged_in() ) {
			        	include trailingslashit( THUNDER_DIR ) . "forms/$tpl.php"; 
			        } else {						
						$user_id = get_current_user_id();
						include trailingslashit( THUNDER_DIR ) . "forms/logout.php";
					}
				break;
				case 'register':
					if ( ! is_user_logged_in() ) {
			        	include trailingslashit( THUNDER_DIR ) . "forms/$tpl.php"; 
			        } else {						
						$user_id = get_current_user_id();
						include trailingslashit( THUNDER_DIR ) . "forms/logout.php";
					}
				break;	
				case 'view':
					if ( ! is_user_logged_in() ) {						
			        	include trailingslashit( THUNDER_DIR ) . "forms/login.php"; 
			        } else {						
						$user_id = get_current_user_id();
						include trailingslashit( THUNDER_DIR ) . "forms/$tpl.php";						
					}
				break;	
				case 'edit':
					if ( ! is_user_logged_in() ) {						
			        	include trailingslashit( THUNDER_DIR ) . "forms/login.php"; 
			        } else {						
						$user_id = get_current_user_id();
						include trailingslashit( THUNDER_DIR ) . "forms/$tpl.php";						
					}
				break;											
			}
		}	
		$out = ob_get_contents();
		ob_end_clean();
		return $out;
}


?>