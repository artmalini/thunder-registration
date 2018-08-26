<?php 



/**
*  Grab the user profile from social network
*/
/*function thunder_process_login_request_user_social_profile( $provider )
{
	$adapter                 = null;
	$config                  = null;
	$hybridauth_user_profile = null;

	try
	{
		// get idp adapter
		$adapter = thunder_process_login_get_provider_adapter( $provider );

		$config = $adapter->config;

		// if user authenticated successfully with social network
		if( $adapter->isUserConnected() )
		{
			// grab user profile via hybridauth api
			$hybridauth_user_profile = $adapter->getUserProfile();
		}

		// if user not connected to provider (ie: session lost, url forged)
		else
		{
			return thunder_process_login_render_notice_page( sprintf( __( "Sorry, we couldn't connect you with <b>%s</b>. <a href=\"%s\">Please try again</a>.", 'wordpress-social-login' ), $provider, site_url( 'wp-login.php', 'login_post' ) ) );
		}
	}

	// if things didn't go as expected, we dispay the appropriate error message
	catch( Exception $e )
	{
		return thunder_social_error_page( $e, $config, $provider, $adapter );
	}

	return $hybridauth_user_profile;
}*/

// --------------------------------------------------------------------


		
	add_action( 'init', 'thunder_social' );
	function thunder_social() {
		$action = isset( $_REQUEST['action'] ) ? $_REQUEST['action'] : null;

		if( ! in_array( $action, array( "thunder_social_request", "thunder_social_account_linking", "thunder_social_authenticated" ) ) )	{
			return false;
		}

		if ( $action == 'thunder_social_request' ) {
			return thunder_process_login_request();
		}	
			// if action=wordpress_social_authenticated or action=wordpress_social_profile_completion	
		if ( $action == 'thunder_social_authenticated' ) {
			thunder_process_login_end();
		} else {
			return false;
		}
	}



	function thunder_process_login_request() {
		$config     = null;
		$hybridauth = null;
		$provider   = null;
		$adapter    = null;

		$provider = isset( $_REQUEST["provider"] ) ? sanitize_text_field( $_REQUEST["provider"] ) : null;
		
		if (! isset( $_REQUEST["redirect_to_provider"] )) {
			thunder_render_redirect_to_provider_loading_screen($provider);
		}

		$config = thunder_build_provider_config( $provider );

		if( ! class_exists('Hybrid_Auth', false) )	{		
			require_once THUNDER_DIR . "hybridauth/Hybrid/Auth.php";
		}
		try	{
			// create an instance for Hybridauth with the configuration array as parameter
			$hybridauth = new Hybrid_Auth( $config );
			// start the authentication process via hybridauth		
			$adapter = $hybridauth->authenticate( $provider );
		}
		// if hybridauth fails to authenticate the user, then we display an error message
		catch( Exception $e ) {
			return thunder_social_error_page( $e, $config, $provider );		
	}

		

	// authentication mode
		//$auth_mode = thunder_process_login_get_auth_mode();
		$auth_mode = 'login';

		$redirect_to = isset( $_REQUEST[ 'redirect_to' ] ) ? $_REQUEST[ 'redirect_to' ] : home_url();

		//$wsl_settings_use_popup = get_option( 'wsl_settings_use_popup' );
		$thunder_settings_use_popup = 2;

	// build the authenticateD, which will make thunder_process_login() fire the next step thunder_process_login_end()
		$authenticated_url = site_url( 'wp-login.php', 'login_post' ) . ( strpos( site_url( 'wp-login.php', 'login_post' ), '?' ) ? '&' : '?' ) . "action=thunder_social_authenticated&provider=" . $provider . '&mode=' . $auth_mode;

	// display a loading screen
		return thunder_render_return_from_provider_loading_screen( $provider, $authenticated_url, $redirect_to, $thunder_settings_use_popup );

}

function thunder_build_provider_config( $provider ) {
	$config = array();
	//$config["base_url"] = WORDPRESS_SOCIAL_LOGIN_HYBRIDAUTH_ENDPOINT_URL;
	//$config["base_url"] =  THUNDER_REG_URL . 'hybridauth-stat/';
	$config["base_url"] =  THUNDER_REG_URL . 'hybridauth/';
	$config["providers"] = array();
	$config["providers"][$provider] = array();
	$config["providers"][$provider]["enabled"] = true;
	$config["providers"][$provider]["keys"] = array( 'id' => null, 'key' => null, 'secret' => null );
	//$config["providers"][$provider]["keys"] = array( 'id' => '612958402199606', 'key' => null, 'secret' => '8b08a88e88343954dac82218ffa8379e' );

	// provider application id
	if( thunder_get_option( $provider . '_sdk_id' ) ) {
		$config["providers"][$provider]["keys"]["id"] = thunder_get_option( $provider . '_sdk_id' );
	}	
	// provider application key
	if( thunder_get_option( $provider . '_sdk_key' ) ) {
		$config["providers"][$provider]["keys"]["key"] = thunder_get_option( $provider . '_sdk_key' );
	}
	// provider application secret
	if( thunder_get_option( $provider . '_sdk_secret' ) ) {
		$config["providers"][$provider]["keys"]["secret"] = thunder_get_option( $provider . '_sdk_secret' );
	}		
	// provider application id ?
	/*if( get_option( 'wsl_settings_' . $provider . '_app_id' ) )
	{
		$config["providers"][$provider]["keys"]["id"] = get_option( 'wsl_settings_' . $provider . '_app_id' );
	}

	// provider application key ?
	if( get_option( 'wsl_settings_' . $provider . '_app_key' ) )
	{
		$config["providers"][$provider]["keys"]["key"] = get_option( 'wsl_settings_' . $provider . '_app_key' );
	}

	// provider application secret ?
	if( get_option( 'wsl_settings_' . $provider . '_app_secret' ) )
	{
		$config["providers"][$provider]["keys"]["secret"] = get_option( 'wsl_settings_' . $provider . '_app_secret' );
	}*/

	// set custom endpoint?
	/*if( in_array( strtolower( $provider ), array( 'dribbble', 'live' ) ) )
	{
		$config["providers"][$provider]["endpoint"] = WORDPRESS_SOCIAL_LOGIN_HYBRIDAUTH_ENDPOINT_URL . 'endpoints/' . strtolower( $provider ) . '.php';
	}

	// set default scope
	if( get_option( 'wsl_settings_' . $provider . '_app_scope' ) )
	{
		$config["providers"][$provider]["scope"] = get_option( 'wsl_settings_' . $provider . '_app_scope' );
	}*/

	// set custom config for facebook
	if( strtolower( $provider ) == "facebook" )
	{
		//$config["providers"][$provider]["display"] = "popup";
		//$config["providers"][$provider]["trustForwarded"] = true;

		// switch to fb::display 'page' if wsl auth in page
		//if( get_option( 'wsl_settings_use_popup') == 2 )
		/*if( true )
		{
			$config["providers"][$provider]["display"] = "page";
		}*/
		//$config["providers"][$provider]["display"] = "page";
		$config["providers"][$provider]["trustForwarded"] = true;
		//$config["debug_mode"] = "error";
		//$config["debug_file"] = THUNDER_DIR . 'debug.txt';
	}

	// set custom config for google
	/*if( strtolower( $provider ) == "google" )
	{
		// if contacts import enabled, we request an extra permission 'https://www.google.com/m8/feeds/'
		if( wsl_is_component_enabled( 'contacts' ) && get_option( 'wsl_settings_contacts_import_google' ) == 1 )
		{
			$config["providers"][$provider]["scope"] .= " https://www.google.com/m8/feeds/";
		}
	}*/

	//$provider_scope = isset( $config["providers"][$provider]["scope"] ) ? $config["providers"][$provider]["scope"] : '' ;
	$config["providers"][$provider]["scope"] = "email,public_profile,user_friends";

	// HOOKABLE: allow to overwrite scopes
	//$config["providers"][$provider]["scope"] = apply_filters( 'wsl_hook_alter_provider_scope', $provider_scope, $provider );

	// HOOKABLE: allow to overwrite hybridauth config for the selected provider
	//$config["providers"][$provider] = apply_filters( 'wsl_hook_alter_provider_config', $config["providers"][$provider], $provider );

	return $config;
}
//$hybridauth = new Hybrid_Auth( $config );





if( ! function_exists( 'thunder_render_redirect_to_provider_loading_screen' ) ) {

	function thunder_render_redirect_to_provider_loading_screen( $provider ) {
		$assets_base_url  = WORDPRESS_SOCIAL_LOGIN_PLUGIN_URL . 'assets/img/';
?>
<!DOCTYPE html>
	<head>
		<meta name="robots" content="NOINDEX, NOFOLLOW">
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<title><?php echo __("Redirecting...", 'thunder') ?> - <?php bloginfo('name'); ?></title>
		<style type="text/css">
			html {
				background: #f1f1f1;
			}
			body {
				background: #fff;
				color: #444;
				font-family: "Open Sans", sans-serif;
				margin: 2em auto;
				padding: 1em 2em;
				max-width: 700px;
				-webkit-box-shadow: 0 1px 3px rgba(0,0,0,0.13);
				box-shadow: 0 1px 3px rgba(0,0,0,0.13);
			}
			#loading-screen {
				margin-top: 50px;
			}
			#loading-screen div{
				line-height: 20px;
				padding: 8px;
				background-color: #f2f2f2;
				border: 1px solid #ccc;
				padding: 10px;
				text-align:center;
				box-shadow: 0 1px 3px rgba(0,0,0,0.13);
				margin-top:25px;
			}
		</style>
		<script>
			function init()
			{
				window.location.replace( window.location.href + "&redirect_to_provider=true" );
			}
			console.log('loading_screen');
		</script>
	</head>
	<body id="loading-screen" onload="init();">
		<table width="100%" border="0">
			<tr>
				<td align="center"><img src="<?php echo $assets_base_url ?>loading.gif" /></td>
			</tr>
			<tr>
				<td align="center">
					<div>
						<?php echo sprintf( __( "Contacting <b>%s</b>, please wait...", 'thunder'), __( ucfirst( $provider ), 'thunder') )  ?>						
					</div>
				</td>
			</tr>
		</table>
	</body>
</html>
<?php
		die();
	}
}

if( ! function_exists( 'thunder_render_return_from_provider_loading_screen' ) )
{
	function thunder_render_return_from_provider_loading_screen( $provider, $authenticated_url, $redirect_to, $thunder_settings_use_popup )
	{
		/*
		* If Authentication displayis undefined or eq Popup ($thunder_settings_use_popup==1)
		* > create a from with javascript in parent window and submit it to wp-login.php ($authenticated_url)
		* > with action=thunder_social_authenticated, then close popup
		*
		* If Authentication display eq In Page ($thunder_settings_use_popup==2)
		* > create a from in page then submit it to wp-login.php with action=thunder_social_authenticated
		*/

		$assets_base_url  = WORDPRESS_SOCIAL_LOGIN_PLUGIN_URL . 'assets/img/';
?>
<!DOCTYPE html>
	<head>
		<meta name="robots" content="NOINDEX, NOFOLLOW">
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<title><?php echo __("Redirecting...", 'thunder') ?> - <?php bloginfo('name'); ?></title>
		<style type="text/css">
			html {
				background: #f1f1f1;
			}
			body {
				background: #fff;
				color: #444;
				font-family: "Open Sans", sans-serif;
				margin: 2em auto;
				padding: 1em 2em;
				max-width: 700px;
				-webkit-box-shadow: 0 1px 3px rgba(0,0,0,0.13);
				box-shadow: 0 1px 3px rgba(0,0,0,0.13);
			}
			#loading-screen {
				margin-top: 50px;
			}
			#loading-screen div{
				line-height: 20px;
				padding: 8px;
				background-color: #f2f2f2;
				border: 1px solid #ccc;
				padding: 10px;
				text-align:center;
				box-shadow: 0 1px 3px rgba(0,0,0,0.13);
				margin-top:25px;
			}
		</style>
		<script>
		//console.log('return-provider');
			function init()
			{
				<?php
					if( $thunder_settings_use_popup == 1 || ! $thunder_settings_use_popup ){
						?>
							if( window.opener )
							{
								window.opener.thunder_wordpress_social_login({
									'action'   : 'thunder_social_authenticated',
									'provider' : '<?php echo $provider ?>'
								});

								window.close();
							}
							else
							{
								document.loginform.submit();
							}
						<?php
					}
					elseif( $thunder_settings_use_popup == 2 ){
						?>
							document.loginform.submit();
						<?php
					}
				?>
			}
		</script>
	</head>
	<body id="loading-screen" onload="init();">
		<table width="100%" border="0">
			<tr>
				<td align="center"><img src="<?php echo $assets_base_url ?>loading.gif" /></td>
			</tr>
			<tr>
				<td align="center">
					<div>
						<?php echo __( "Processing, please wait...", 'thunder');  ?>
					</div>
				</td>
			</tr>
		</table>

		<form name="loginform" method="post" action="<?php echo $authenticated_url; ?>">
			<input type="hidden" id="redirect_to" name="redirect_to" value="<?php echo esc_url( $redirect_to ); ?>">
			<input type="hidden" id="provider" name="provider" value="<?php echo $provider ?>">
			<input type="hidden" id="action" name="action" value="thunder_social_authenticated">
		</form>
	</body>
</html>
<?php
		die();
	}
}










// --------------------------------------------------------------------

/**
* Display an error message in case user authentication fails
*/
function thunder_social_error_page( $e, $config = null, $provider = null, $adapter = null )
{
	// HOOKABLE:
	//do_action( "wsl_process_login_render_error_page", $e, $config, $provider, $adapter );

	$assets_base_url  = WORDPRESS_SOCIAL_LOGIN_PLUGIN_URL . 'assets/img/';

	$message  = __("Unspecified error!", 'thunder');
	$notes    = "";
	$apierror = substr( $e->getMessage(), 0, 145 );

	switch( $e->getCode() )
	{
		case 0 : $message = __("Unspecified error.", 'thunder'); break;
		case 1 : $message = __("WordPress Social Login is not properly configured.", 'thunder'); break;
		case 2 : $message = sprintf( __("WordPress Social Login is not properly configured.<br /> <b>%s</b> need to be properly configured.", 'thunder'), $provider ); break;
		case 3 : $message = __("Unknown or disabled provider.", 'thunder'); break;
		case 4 : $message = sprintf( __("WordPress Social Login is not properly configured.<br /> <b>%s</b> requires your application credentials.", 'thunder'), $provider );
			 $notes   = sprintf( __("<b>What does this error mean ?</b><br />Most likely, you didn't setup the correct application credentials for this provider. These credentials are required in order for <b>%s</b> users to access your website and for WordPress Social Login to work.", 'thunder'), $provider ) . __('<br />Instructions for use can be found in the <a href="http://miled.github.io/thunder/networks.html" target="_blank">User Manual</a>.', 'thunder');
			 break;
		case 5 : $message = sprintf( __("Authentication failed. Either you have cancelled the authentication or <b>%s</b> refused the connection.", 'thunder'), $provider ); break;
		case 6 : $message = sprintf( __("Request failed. Either you have cancelled the authentication or <b>%s</b> refused the connection.", 'thunder'), $provider ); break;
		case 7 : $message = __("You're not connected to the provider.", 'thunder'); break;
		case 8 : $message = __("Provider does not support this feature.", 'thunder'); break;
	}

	if( is_object( $adapter ) )
	{
		$adapter->logout();
	}

	// provider api response
	/*if( class_exists( 'Hybrid_Error', false ) && Hybrid_Error::getApiError() )
	{
		$tmp = Hybrid_Error::getApiError();

		$apierror = $apierror . "\n" . '<br />' . $tmp;

		// network issue
		if( trim( $tmp ) == '0.' )
		{
			$apierror = "Could not establish connection to provider API";
		}
	}*/

	return thunder_render_error_page( $message, $notes, $provider, $apierror, $e );
}




if( ! function_exists( 'thunder_render_error_page' ) )
{
	function thunder_render_error_page( $message, $notes = null, $provider = null, $api_error = null, $php_exception = null ) {
		$assets_base_url = WORDPRESS_SOCIAL_LOGIN_PLUGIN_URL . 'assets/img/';
?>
<!DOCTYPE html>
	<head>
		<meta name="robots" content="NOINDEX, NOFOLLOW">
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<title><?php bloginfo('name'); ?> - <?php echo __("Oops! We ran into an issue", 'thunder') ?>.</title>
		<style type="text/css">
			body {
				background: #f1f1f1;
			}
			h4 {
				color: #666;
				font: 20px "Open Sans", sans-serif;
				margin: 0;
				padding: 0;
				padding-bottom: 7px;
			}
			p {
				font-size: 14px;
				line-height: 1.5;
				margin: 15px 0;
				line-height: 25px;
				padding: 10px;
				text-align:left;
			}
			a {
				color: #21759B;
				text-decoration: none;
			}
			a:hover {
				color: #D54E21;
			}
			#error-page {
				background: #fff;
				color: #444;
				font-family: "Open Sans", sans-serif;
				margin: 2em auto;
				padding: 1em 2em;
				max-width: 700px;
				-webkit-box-shadow: 0 1px 3px rgba(0,0,0,0.13);
				box-shadow: 0 1px 3px rgba(0,0,0,0.13);
				margin-top: 50px;
			}
			#error-page pre {
				max-width: 680px;
				overflow: scroll;
				padding: 5px;
				background: none repeat scroll 0 0 #F5F5F5;
				border-radius:3px;
				font-family: Consolas, Monaco, monospace;
			}
			.error-message {
				line-height: 26px;
				padding: 8px;
				background-color: #f2f2f2;
				border: 1px solid #ccc;
				padding: 10px;
				text-align:center;
				box-shadow: 0 1px 3px rgba(0,0,0,0.13);
				margin-top:25px;
			}
			.error-hint{
				margin:0;
			}
			#debuginfo {
				display:none;
				text-align: center;
				margin: 0;
				padding: 0;
				padding-top: 10px;
				margin-top: 10px;
				border-top: 1px solid #d2d2d2;
			}
		</style>
		<script>
			function xi(){ document.getElementById('debuginfo').style.display = 'block'; }
		</script>
	</head>
	<body>
		<div id="error-page">
			<table width="100%" border="0">
				<tr>
					<td align="center"><img src="<?php echo $assets_base_url ?>alert.png" /></td>
				</tr>

				<tr>
					<td align="center"><h4><?php __("Oops! We ran into an issue", 'thunder') ?>.</h4></td>
				</tr>

				<tr>
					<td>
						<div class="error-message">
							<?php echo $message ; ?>
						</div>

						<?php
							// any hint or extra note?
							if( $notes )
							{
								?>
									<p class="error-hint"><?php echo __( $notes, 'thunder'); ?></p>
								<?php
							}
						?>
					</td>
				</tr>

				<tr>
					<td>
						<p style="padding: 0;">
							<a href="javascript:xi();" style="float:right"><?php echo __("Details", 'thunder') ?></a>
							<a href="<?php echo home_url(); ?>" style="float:left">&xlarr; <?php __("Back to home", 'thunder') ?></a>
						</p>

						<br style="clear:both;" />

						<p id="debuginfo">&xi; <?php echo $api_error ?></p>
					</td>
				</tr>
			</table>
		</div>

		<?php
			// Development mode on?
			/*if( get_option( 'wsl_settings_development_mode_enabled' ) )
			{
				wsl_render_error_page_debug_section( $php_exception );
			}*/
		?>
	</body>
</html>
<?php
	# keep these 2 LOC
		//do_action( 'wsl_clear_user_php_session' );

		die();
	}
}




















if( ! function_exists( 'thunder_render_notice_page' ) )
{
	function thunder_render_notice_page( $message )
	{
		$assets_base_url = WORDPRESS_SOCIAL_LOGIN_PLUGIN_URL . 'assets/img/';
?>
<!DOCTYPE html>
	<head>
		<meta name="robots" content="NOINDEX, NOFOLLOW">
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<title><?php bloginfo('name'); ?></title>
		<style type="text/css">
			body {
				background: #f1f1f1;
			}
			h4 {
				color: #666;
				font: 20px "Open Sans", sans-serif;
				margin: 0;
				padding: 0;
				padding-bottom: 12px;
			}
			a {
				color: #21759B;
				text-decoration: none;
			}
			a:hover {
				color: #D54E21;
			}
			p {
				font-size: 14px;
				line-height: 1.5;
				margin: 25px 0 20px;
			}
			#notice-page {
				background: #fff;
				color: #444;
				font-family: "Open Sans", sans-serif;
				margin: 2em auto;
				padding: 1em 2em;
				max-width: 700px;
				-webkit-box-shadow: 0 1px 3px rgba(0,0,0,0.13);
				box-shadow: 0 1px 3px rgba(0,0,0,0.13);
				margin-top: 50px;
			}
			#notice-page code {
				font-family: Consolas, Monaco, monospace;
			}
			.notice-message {
				line-height: 26px;
				padding: 8px;
				background-color: #f2f2f2;
				border: 1px solid #ccc;
				padding: 10px;
				text-align:center;
				box-shadow: 0 1px 3px rgba(0,0,0,0.13);
				margin-top:25px;
			}
		</style>
	<head>
	<body>
		<div id="notice-page">
			<table width="100%" border="0">
				<tr>
					<td align="center"><img src="<?php echo $assets_base_url ?>alert.png" /></td>
				</tr>
				<tr>
					<td align="center">
						<div class="notice-message">
							<?php echo nl2br( $message ); ?>
						</div>
					</td>
				</tr>
			</table>
		</div>

		<?php
			// Development mode on?
			/*if( get_option( 'wsl_settings_development_mode_enabled' ) )
			{
				wsl_render_error_page_debug_section();
			}*/
		?>
	</body>
</html>
<?php
		die();
	}
}















/**
* Returns hybriauth idp adapter.
*/
function thunder_process_login_get_provider_adapter( $provider )
{
	if( ! class_exists( 'Hybrid_Auth', false ) )
	{
		require_once THUNDER_DIR . "hybridauth/Hybrid/Auth.php";
			
		$config = thunder_build_provider_config( $provider );
		$main = new Hybrid_Auth( $config );			
	}

	return Hybrid_Auth::getAdapter( $provider );
}







function thunder_process_login_end() {	
	$redirect_to = home_url();
	//$redirect_to = wsl_process_login_get_redirect_to();
	$provider = isset( $_REQUEST["provider"] ) ? sanitize_text_field( $_REQUEST["provider"] ) : null;
	list(
			$user_id                ,
			$adapter                ,
			$hybridauth_user_profile,
			$requested_user_login   ,
			$requested_user_email   ,
			$wordpress_user_id
		) = thunder_process_login_get_user_data( $provider, $redirect_to );

	store_social_meta($user_id, $provider, $hybridauth_user_profile);
}



function store_social_meta( $user_id, $provider, $hybridauth_user_profile) {
		update_user_meta( $user_id, 'thunder_social_{$provider}_name', $provider );
		update_user_meta( $user_id, 'thunder_{$provider}_id', $hybridauth_user_profile->identifier );

	if(  $hybridauth_user_profile->photoURL )
	{
		update_user_meta( $user_id, 'picture', $hybridauth_user_profile->photoURL );
	}

	die();
		/*$fields = array( 
		'identifier', 
		'profileurl', 
		'websiteurl', 
		'photourl', 
		'displayname', 
		'description', 
		'firstname', 
		'lastname', 
		'gender', 
		'language', 
		'age', 
		'birthday', 
		'birthmonth', 
		'birthyear', 
		'email', 
		'emailverified', 
		'phone', 
		'address', 
		'country', 
		'region', 
		'city', 
		'zip'
	);

	foreach( $hybridauth_user_profile as $key => $value )
	{
		$key = strtolower($key);

		if( in_array( $key, $fields ) )
		{
			//$table_data[ $key ] = (string) $value;
			update_user_meta($user_id, (string) $value, (string) $value); 
		}
	}*/

	//$wpdb->replace( "{$wpdb->prefix}wslusersprofiles", $table_data ); 

	//return $wpdb->insert_id;
}
/*if (thunder_get_option('users_approve') === '2') {
							
								$user_id = new_user( $user_login, $user_password, $user_email, $form, $type='standard', $approved=0 );
								pending_email_approve( $user_id, $user_password, $form );
}*/





function thunder_process_login_get_user_data( $provider, $redirect_to ) {
	//if( ! ( isset( $_SESSION['wsl::userprofile'] )
	/*if ( $user_id == 0 ) {
		$user_id = login_new_users_gateway( $provider, $redirect_to, $hybridauth_user_profile ); //регистрируе или линкует профиль к старой регистр.
	}*/
	//$verif = login_new_users_gateway( $provider, $redirect_to, $hybridauth_user_profile );
	$adapter = thunder_process_login_get_provider_adapter( $provider ); 
	$hybridauth_user_email = sanitize_email( $hybridauth_user_profile->email );
	$hybridauth_user_profile = thunder_process_login_request_user_social_profile( $provider ); 


	
	/*$verif[$shall_pass] = false;
	while ($verif[$shall_pass] != true ) {
		$verif[$shall_pass] = login_new_users_gateway( $provider, $redirect_to, $hybridauth_user_profile );
	}*/
	$user_id = (int) hybridauth_user_id_by_provider_and_provider_uid( $provider, $hybridauth_user_profile->identifier );
    
    $register_closed = 1;
    if (! $user_id) {
    	                // Bouncer :: Accept new registrations? get_option( 'wsl_settings_bouncer_registration_enabled' )
                if(  $register_closed == 2 ) {
                		$message = __( "Registration is now closed.", 'thunder' );
                		return thunder_render_notice_page( $message );
                        //return wsl_process_login_render_notice_page( _wsl__( "Registration is now closed.", 'wordpress-social-login' ) );
                }

    	do {
                list (
                    $shall_pass,
                    $user_id,
                    $requested_user_login,
                    $requested_user_email
                ) = login_new_users_gateway( $provider, $redirect_to, $hybridauth_user_profile );
        } while( ! $shall_pass );
    } /*else {
    	$wordpress_user_id = $user_id;
    }*/

		

		return array(
		$user_id,
		$adapter,
		$hybridauth_user_profile,
		$requested_user_login,
		$requested_user_email,
		$wordpress_user_id
	);
}

/**
*  Grab the user profile from social network
*/
function thunder_process_login_request_user_social_profile( $provider )
{
	$adapter                 = null;
	$config                  = null;
	$hybridauth_user_profile = null;

	try
	{
		// get idp adapter
		$adapter = thunder_process_login_get_provider_adapter( $provider );

		$config = $adapter->config;

		// if user authenticated successfully with social network
		if( $adapter->isUserConnected() )
		{
			// grab user profile via hybridauth api
			$hybridauth_user_profile = $adapter->getUserProfile();
		}

		// if user not connected to provider (ie: session lost, url forged)
		else
		{
    		$message = sprintf( __( "Sorry, we couldn't connect you with <b>%s</b>. <a href=\"%s\">Please try again</a>.", 'thunder' ), $provider, site_url( 'wp-login.php', 'login_post' ) );
    		return thunder_render_notice_page( $message );			
			//return thunder_process_login_render_notice_page( sprintf( _thunder__( "Sorry, we couldn't connect you with <b>%s</b>. <a href=\"%s\">Please try again</a>.", 'wordpress-social-login' ), $provider, site_url( 'wp-login.php', 'login_post' ) ) );			
		}
	}

	// if things didn't go as expected, we dispay the appropriate error message
	catch( Exception $e )
	{
		return thunder_social_error_page( $e, $config, $provider, $adapter );
		//return $e;
	}

	return $hybridauth_user_profile;
}

function hybridauth_user_id_by_provider_and_provider_uid( $provider, $hybridauth_id ) {
			$users = get_users(array(
				'meta_key'     => 'thunder_{$provider}_id',
				'meta_value'   => $hybridauth_id,
				'meta_compare' => '='
			));
			if (isset($users[0]->ID) && is_numeric($users[0]->ID) ){
				$user_id = $users[0]->ID;
			} else {
				$user_id = 0;
			}
			return $user_id;		
}


















function login_new_users_gateway( $provider, $redirect_to, $hybridauth_user_profile ) {	
	// HOOKABLE:
	//do_action( "wsl_process_login_new_users_gateway_start", $provider, $redirect_to, $hybridauth_user_profile );

	$assets_base_url = WORDPRESS_SOCIAL_LOGIN_PLUGIN_URL . 'assets/img/16x16/';

	// remove wsl widget
	//remove_action( 'register_form', 'wsl_render_auth_widget_in_wp_register_form' );

	$hybridauth_user_email       = sanitize_email( $hybridauth_user_profile->email );
	$hybridauth_user_login       = sanitize_user( $hybridauth_user_profile->displayName, true );
	$hybridauth_user_avatar      = $hybridauth_user_profile->photoURL;
	$hybridauth_user_website     = $hybridauth_user_profile->webSiteURL;
	$hybridauth_user_link        = $hybridauth_user_profile->profileURL;

	$hybridauth_user_login       = trim( str_replace( array( ' ', '.' ), '_', $hybridauth_user_login ) );
	$hybridauth_user_login       = trim( str_replace( '__', '_', $hybridauth_user_login ) );

	$requested_user_email        = isset( $_REQUEST["user_email"] ) ? trim( $_REQUEST["user_email"] ) : $hybridauth_user_email;
	$requested_user_login        = isset( $_REQUEST["user_login"] ) ? trim( $_REQUEST["user_login"] ) : $hybridauth_user_login;

	$requested_user_email        = apply_filters( 'new_users_gateway_alter_requested_email', $requested_user_email );
	$requested_user_login        = apply_filters( 'new_users_gateway_alter_requested_login', $requested_user_login );

	$user_id    = 0;
	$shall_pass = false;

	$bouncer_account_linking    = false;
	$account_linking_errors     = array();

	$bouncer_profile_completion = false;
	$profile_completion_errors  = array();

	//$linking_enabled = get_option( 'wsl_settings_bouncer_accounts_linking_enabled' );
	$linking_enabled = 1;
       // $require_email   = get_option( 'wsl_settings_bouncer_profile_completion_require_email' );
	$require_email   = 1;
        //$change_username = get_option( 'wsl_settings_bouncer_profile_completion_change_username' );
   	$change_username = 1;
        //$extra_fields    = get_option( 'wsl_settings_bouncer_profile_completion_hook_extra_fields' );
   	$extra_fields = 1; // 1 - подключает виджет wps

	if( isset( $_REQUEST["bouncer_account_linking"] ) )
	{
		if( $linking_enabled == 2 )
		{
			//return wsl_process_login_render_notice_page( __( "Not tonight.", 'thunder' ) );
		}

		$bouncer_account_linking = true;

		$username = isset( $_REQUEST["user_login"]    ) ? trim( $_REQUEST["user_login"]    ) : '';
		$password = isset( $_REQUEST["user_password"] ) ? trim( $_REQUEST["user_password"] ) : '';

		# http://codex.wordpress.org/Function_Reference/wp_authenticate
		$user = wp_authenticate( $username, $password );

		// WP_Error object?
		if( is_wp_error( $user ) ) {
			// we give no useful hint.
			$account_linking_errors[] = 
                                sprintf( 
                                        __( 
                                                '<strong>ERROR</strong>: Invalid username or incorrect password. <a href="%s">Lost your password</a>?', 
                                                'thunder' 
                                        ), 
                                        wp_lostpassword_url( home_url() ) 
                                );
		}

		elseif( is_a( $user, 'WP_User') )
		{
			$user_id = $user->ID;

			$shall_pass = true;
		}
	}

	elseif( isset( $_REQUEST["bouncer_profile_completion"] ) )
	{
		// Bouncer::Profile Completion enabled?
		// > if not enabled we just let the user pass
		if( $require_email == 2 && $change_username == 2 && $extra_fields == 2 )
		{
			$shall_pass = true;
		}

		// otherwise we request email &or username &or extra fields
		else
		{
			$bouncer_profile_completion = true;

			/**
			* Code based on wpmu_validate_user_signup()
			*
			* Ref: http://codex.wordpress.org/Function_Reference/wpmu_validate_user_signup
			*/

			# {{{ validate usermail
			if( $require_email == 1 )
			{
				if ( empty( $requested_user_email ) )
				{
					$profile_completion_errors[] = __( '<strong>ERROR</strong>: Please type your e-mail address.', 'thunder' );
				}

				if ( ! is_email( $requested_user_email ) )
				{
					$profile_completion_errors[] = __( '<strong>ERROR</strong>: Please enter a valid email address.', 'thunder' );
				}

				if ( get_user_by( 'email', $requested_user_email ) )
				{
					$profile_completion_errors[] = __( '<strong>ERROR</strong>: Sorry, that email address is already used!', 'thunder' );
				}
			}
			# }}} validate usermail

			# {{{ validate username (called login in wsl)
			if( $change_username == 1 )
			{
				$illegal_names = array(  'www', 'web', 'root', 'admin', 'main', 'invite', 'administrator' );

				$illegal_names = apply_filters( 'th_users_gateway_alter_illegal_names', $illegal_names ); //для дополнительных имен

				if ( in_array( $requested_user_login, $illegal_names ) == true )
				{
					$profile_completion_errors[] = __( '<strong>ERROR</strong>: That username is not allowed.', 'thunder' );
				}

				if ( strlen( $requested_user_login ) < 4 )
				{
					$profile_completion_errors[] = __( '<strong>ERROR</strong>: Username must be at least 4 characters.', 'thunder' );
				}

				if ( strpos( ' ' . $requested_user_login, '_' ) != false )
				{
					 $profile_completion_errors[] = __( '<strong>ERROR</strong>: Sorry, usernames may not contain the character &#8220;_&#8221;!', 'thunder' );
				}

				if ( preg_match( '/^[0-9]*$/', $requested_user_login ) )
				{
					$profile_completion_errors[] = __( '<strong>ERROR</strong>: Sorry, usernames must have letters too!', 'thunder' );
				}

				if ( username_exists( $requested_user_login) )
				{
					$profile_completion_errors[] = __( '<strong>ERROR</strong>: Sorry, that username already exists!', 'thunder' );
				}
			}
			# }}} validate username

			# ... well, that was a lot of sorries.

			# {{{ extra fields
			if( $extra_fields == 1 )
			{
				$errors = new WP_Error();

				//$errors = apply_filters( 'registration_errors', $errors, $requested_user_login, $requested_user_email );
				//$errors = apply_filters( 'social_errors', $errors, $requested_user_login, $requested_user_email );

				if( $errors = $errors->get_error_messages() )
				{
					foreach ( $errors as $error )
					{
						$profile_completion_errors[] = $error;
					}
				}
			}
			# }}} extra fields

			//$profile_completion_errors = apply_filters( 'th_new_users_gateway_alter_profile_completion_errors', $profile_completion_errors );

			// all check?
			if( ! $profile_completion_errors )
			{
				$shall_pass = true;
			}
		}
	}

	if( $shall_pass == false ) {
		//$provider_name = wsl_get_provider_name_by_id( $provider );
?>
<!DOCTYPE html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title><?php echo get_bloginfo( 'name' ); ?></title>
		<style type="text/css">
			html, body {
				height: 100%;
				margin: 0;
				padding: 0;
			}
			body {
				background: none repeat scroll 0 0 #f1f1f1;
				font-size: 14px;
				color: #444;
				font-family: "Open Sans",sans-serif;
			}
			hr {
				border-color: #eeeeee;
				border-style: none none solid;
				border-width: 0 0 1px;
				margin: 2px 0 0;
			}
			h4 {
				font-size: 14px;
				margin-bottom: 10px;
			}
			.thunder-social-login-grid {
			  padding-right: 6px;
			  padding-left: 6px;
			  margin-right: auto;
			  margin-left: auto; }

			.thunder-social-login-grid .thunder-social-row {
			  margin-right: -6px;
			  margin-left: -6px; }

			.thunder-social-login-grid .thunder-social-row::before {
			  display: table;
			  content: " "; }

			.thunder-social-login-grid .thunder-social-row::after {
			  display: table;
			  content: " ";
			  clear: both; }

			.thunder-social-login-grid .thunder-social-field {
			  float: left;
			  width: 100%; }
			.thunder-social-login {
				width: 616px;
				margin: auto;
				padding: 114px 0 0;
				background: none repeat scroll 0 0 #fff;
				box-shadow: 0 1px 3px rgba(0, 0, 0, 0.13);
				margin: 2em auto;
				box-sizing: border-box;
				display: inline-block;
				/* padding: 70px 0 15px; */
				position: relative;
				text-align: center;
				width: 100%;
			}
			/* #login-panel {
			} */
			.thunder-displ-avatar {
				margin-left: -76px;
				top: -80px;
				left: 50%;
				padding: 4px;
				position: absolute;
			}
			.thunder-displ-avatar img {
				background: none repeat scroll 0 0 #fff;
				border: 3px solid #f1f1f1;
				border-radius: 75px !important;
				box-shadow: 0 1px 3px rgba(0, 0, 0, 0.13);
				height: 145px;
				width: 145px;
			}
			#welcome {
				height: 55px;
				margin: 15px 20px 35px;
			}
			#idp-icon {
				position: absolute;
				margin-top: 2px;
				margin-left: -19px;
			}
			#login-form{
				margin: 0;
				padding: 0;
			}
			.social-button-primary {
				background-color: #21759b;
				background-image: linear-gradient(to bottom, #2a95c5, #21759b);
				border-color: #21759b #21759b #1e6a8d;
				border-radius: 3px;
				border-style: solid;
				border-width: 1px;
				box-shadow: 0 1px 0 rgba(120, 200, 230, 0.5) inset;
				box-sizing: border-box;
				color: #fff;
				cursor: pointer;
				display: inline-block;
				float: none;
				font-size: 12px;
				height: 36px;
				line-height: 23px;
				margin: 0;
				padding: 0 10px 1px;
				text-decoration: none;
				text-shadow: 0 1px 0 rgba(0, 0, 0, 0.1);
				white-space: nowrap;
			}
			.social-button-primary.focus, .social-button-primary:hover{
				background:#1e8cbe;
				border-color:#0074a2;
				-webkit-box-shadow:inset 0 1px 0 rgba(120,200,230,.6);
				box-shadow:inset 0 1px 0 rgba(120,200,230,.6);
				color:#fff
			}
			input[type="text"],
			input[type="password"]{
				border: 1px solid #e5e5e5;
				box-shadow: 1px 1px 2px rgba(200, 200, 200, 0.2) inset;
				color: #555;
				font-size: 17px;
				height: 30px;
				line-height: 1;
				margin-bottom: 16px;
				margin-right: 6px;
				margin-top: 2px;
				outline: 0 none;
				padding: 3px;
				width: 100%;
			}
			input[type="text"]:focus,
			input[type="password"]:focus{
				border-color:#5b9dd9;
				-webkit-box-shadow:0 0 2px rgba(30,140,190,.8);
				box-shadow:0 0 2px rgba(30,140,190,.8)
			}
			input[type="submit"]{
				float:right;
			}
			label{
				color:#777;
				font-size:14px;
				cursor:pointer;
				vertical-align:middle;
				text-align: left;
			}
			table {
				width:355px;
				margin-left:auto;
				margin-right:auto;
			}
			#mapping-options {
				width:555px;
			}
			#mapping-authenticate {
				display:none;
			}
			#mapping-complete-info {
				display:none;
			}
			.error {
				display:none;
				background-color: #fff;
				border-left: 4px solid #dd3d36;
				box-shadow: 0 1px 1px 0 rgba(0, 0, 0, 0.1);
				margin: 0 21px;
				padding: 12px;
				text-align:left;
			}
			.back-to-options {
				float: left;
				margin: 7px 0px;
			}
			.back-to-home {
				font-size: 12px;
				margin-top: -18px;
			}
			.back-to-home a {
				color: #999;
				text-decoration: none;
			}
			<?php
				if( $linking_enabled == 2 )
				{
					?>
					.thunder-social-login {width: 400px;}
					#welcome, #mapping-options, #errors-account-linking, #mapping-complete-info {display: none;}
					#errors-profile-completion, #mapping-complete-info {display: block;}
					<?php
				}
				elseif( $bouncer_account_linking )
				{
					?>
					.thunder-social-login {width: 400px;}
					#welcome, #mapping-options, #errors-profile-completion, #mapping-complete-info {display: none;}
					#errors-account-linking, #mapping-authenticate {display: block;}
					<?php
				}
				elseif( $bouncer_profile_completion )
				{
					?>
					.thunder-social-login {width: 400px;}
					#welcome, #mapping-options, #errors-account-linking, #mapping-complete-info {display: none;}
					#errors-profile-completion, #mapping-complete-info {display: block;}
					<?php
				}
			?>
		</style>
		<script>
			/*setTimeout(function() {
				console.log("working");
			}, 1000); */
			// good old time
			function toggleEl( el, display ) {
				if( el = document.getElementById( el ) )
				{
					el.style.display = display;
				}
			}

			function toggleWidth( el, width ) {
				if( el = document.getElementById( el ) )
				{
					el.style.width = width;
				}
			}

			function display_mapping_options()
			{
				toggleWidth( 'login', '616px' );

				toggleEl( 'welcome'        , 'block' );
				toggleEl( 'mapping-options', 'block' );

				toggleEl( 'errors-profile-completion', 'none' );
				toggleEl( 'mapping-authenticate'     , 'none' );

				toggleEl( 'errors-account-linking', 'none' );
				toggleEl( 'mapping-complete-info' , 'none' );
			}

			function display_mapping_authenticate()
			{
				toggleWidth( 'login', '400px' );

				toggleEl( 'welcome'        , 'none' );
				toggleEl( 'mapping-options', 'none' );

				toggleEl( 'errors-account-linking', 'block' );
				toggleEl( 'mapping-authenticate'  , 'block' );

				toggleEl( 'errors-profile-completion', 'none' );
				toggleEl( 'mapping-complete-info'    ,'none' );
			}

			function display_mapping_complete_info()
			{
				toggleWidth( 'login', '400px' );

				toggleEl( 'welcome'        , 'none' );
				toggleEl( 'mapping-options', 'none' );

				toggleEl( 'errors-account-linking', 'none' );
				toggleEl( 'mapping-authenticate'  , 'none' );

				toggleEl( 'errors-profile-completion', 'block' );
				toggleEl( 'mapping-complete-info'    , 'block' );
			}
		</script>
	</head>
	<body>
	
<div class="thunder-social-login">
	<div class="thunder-social-login-grid">
		<div class="thunder-social-row">
			<div class="thunder-social-row">
				<div class="thunder-social-field">
					<div class="thunder-displ-avatar">
						<img src="<?php echo $hybridauth_user_avatar; ?>">
					</div>
					<div class="thunder-welcome-msg">
						<img id="welcome-icon" src="<?php echo $assets_base_url . strtolower($provider); ?>.png" >
							<b><?php printf( __( "Hi %s", 'thunder' ), htmlentities( $hybridauth_user_profile->displayName ) ); ?></b>
							<p><?php printf( __( "You're now signed in with your %s account but you are still one step away of getting into our website", 'thunder' ), $provider ); ?>.</p>
					</div>
				</div>
			</div>
			<div class="thunder-social-row">			
				<div class="thunder-social-field">
					<div class="thunder-social-have-acc">
						<?php if( $linking_enabled == 1 ): ?>					
							<h4><?php echo __( "Already have an account", 'thunder' ); ?>?</h4>
							<p style="font-size: 12px;"><?php printf( __( "Link your existing account on our website to your %s ID.", 'thunder' ), $provider ); ?></p>
							<input type="button" value="<?php echo __( "Link my account", 'thunder' ); ?>" class="social-button-primary" onclick="display_mapping_authenticate();" >
							
						<?php endif; ?>	
								
						<?php if( $require_email != 1 && $change_username != 1 && $extra_fields != 1 ): ?>
								                        <input type="button" value="<?php echo __( "Create a new account", 'thunder' ); ?>" class="social-button-primary" onclick="document.getElementById('info-form').submit();" >
							<?php else : ?>
								                        <input type="button" value="<?php echo __( "Create a new account", 'thunder' ); ?>" class="social-button-primary" onclick="display_mapping_complete_info();" >
								        <?php endif; ?>						
					</div>
				</div>
			</div>
			<?php
				if( $account_linking_errors )
				{
					echo '<div class="thunder-social-row"><div class="thunder-social-field"><div id="errors-account-linking" class="error">';

					foreach( $account_linking_errors as $error )
					{
						?><p><?php echo $error; ?></p><?php
					}

					echo '</div></div></div>';
				}

				if( $profile_completion_errors )
				{
					echo '<div class="thunder-social-row"><div class="thunder-social-field"><div id="errors-profile-completion" class="error">';

					foreach( $profile_completion_errors as $error )
					{
						?><p><?php echo $error; ?></p><?php
					}

					echo '</div></div></div>';
				}
			?>

			<div class="thunder-social-row">
				<div id="mapping-authenticate">
					<form method="post" action="<?php echo site_url( 'wp-login.php', 'login_post' ); ?>" id="link-form">
						<div class="thunder-social-field">
								<h4><?php echo __( "Already have an account", 'thunder' ); ?>?</h4>
					
								<p><?php printf( __( "Please enter your username and password of your existing account on our website. Once verified, it will linked to your %s ID", 'thunder' ), ucfirst( $provider ) ) ; ?>.</p>
						</div>
						<div class="thunder-social-field">
							<label>
								<?php echo __( "Username", 'thunder' ); ?>
								<br />
								<input type="text" name="user_login" class="input" value=""  size="25" placeholder="" />
							</label>
						</div>							
						<div class="thunder-social-field">
							<label>
								<?php echo __( "Password", 'thunder' ); ?>
								<br />
								<input type="password" name="user_password" class="input" value="" size="25" placeholder="" />
							</label>
						</div>
						<div class="thunder-social-field">
							<input type="submit" value="<?php echo __( "Continue", 'thunder' ); ?>" class="social-button-primary" >
						</div>
					
						<div class="thunder-social-field">
							<a href="javascript:void(0);" onclick="display_mapping_options();" class="back-to-options"><?php echo __( "Back", 'thunder' ); ?></a>
						</div>

						<input type="hidden" id="redirect_to" name="redirect_to" value="<?php echo $redirect_to ?>">
						<input type="hidden" id="provider" name="provider" value="<?php echo $provider ?>">
						<input type="hidden" id="action" name="action" value="thunder_social_account_linking">
						<input type="hidden" id="bouncer_account_linking" name="bouncer_account_linking" value="1">
					</form>
				</div>
			</div>

			<div class="thunder-social-row">
				<div id="mapping-complete-info">
					<form method="post" action="<?php echo site_url( 'wp-login.php', 'login_post' ); ?>" id="info-form">					 
						<div class="thunder-social-field">
							<?php if( $linking_enabled == 1 ): ?>
								<h4><?php echo __( "New to our website", 'thunder' ); ?>?</h4>
							<?php endif; ?>
											
							<p><?php printf( __( "Please fill in your information in the form below. Once completed, you will be able to automatically sign into our website through your %s ID", 'thunder' ), $provider_name ); ?>.</p>
						</div>
							<?php if( $change_username == 1 ): ?>
                                <div class="thunder-social-field">
                                	<label>
                                	    <?php echo __( "Username", 'thunder' ); ?>
                                	    <br />
                                	    <input type="text" name="user_login" class="input" value="<?php echo $requested_user_login; ?>" size="25" placeholder="" />
                                	</label>
                                </div>
                            <?php endif; ?>

                            <?php if( $require_email == 1 ): ?>
                                <div class="thunder-social-field">
                                	<label>
                                	    <?php echo __( "E-mail", 'thunder' ); ?>
                                	    <br />
                                	    <input type="text" name="user_email" class="input" value="<?php echo $requested_user_email; ?>" size="25" placeholder="" />
                                	</label>
                                </div>
                            <?php endif; ?>
				
							<?php
								/**
								* Fires following the 'E-mail' field in the user registration form.
								*
								* hopefully, this won't become a pain in future
								*
								* Ref: http://codex.wordpress.org/Plugin_API/Action_Reference/register_form
								*/
								/*if( $extra_fields == 1 )
								{
									do_action( 'register_form' );
								}*/
							?>
				
							<div class="thunder-social-field">
								<input type="submit" value="<?php echo __( "Continue", 'thunder' ); ?>" class="social-button-primary" >
							</div>
				
							<?php if( $linking_enabled == 1 ): ?>
								<div class="thunder-social-field">
									<a href="javascript:void(0);" onclick="display_mapping_options();" class="back-to-options"><?php __( "Back", 'thunder' ); ?></a>
								</div>
							<?php endif; ?>						
					<input type="hidden" id="redirect_to" name="redirect_to" value="<?php echo $redirect_to ?>">
					<input type="hidden" id="provider" name="provider" value="<?php echo $provider ?>">
					<input type="hidden" id="action" name="action" value="thunder_social_account_linking">
					<input type="hidden" id="bouncer_profile_completion" name="bouncer_profile_completion" value="1">
					</form>
				</div>
			</div>

		</div>
	</div>
</div>

		<?php
			// Development mode on?
			/*if( get_option( 'wsl_settings_development_mode_enabled' ) )
			{
				wsl_display_dev_mode_debugging_area();
			}*/
		?>
	</body>
</html>
<?php
		die();
	}

	return array( $shall_pass, $user_id, $requested_user_login, $requested_user_email );
	//return $user_id;
};
?>