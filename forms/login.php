<div class="thunder thunder-<?php echo $master_id; ?> thunder-<?php echo $layout; ?>" >	

	<div class="login-thunder-body">	
	
	<a rel="nofollow" href="<?php echo $authenticate_url; ?>" title="<?php echo sprintf( __("Connect with %s", 'thunder'), $provider_name ) ?>" class="thunder-login-provider login-provider-<?php echo strtolower( $provider_id ); ?>" data-provider="<?php echo $provider_id ?>">Facebook</a>

	<form method="post" data-tpl="<?php echo $tpl; ?>" data-required_field="<?php echo __('This field is required', 'thunder'); ?>">
		<input type="hidden" name="redirect_uri-<?php echo $master_id; ?>" id="redirect_uri-<?php echo $master_id; ?>" value="<?php if (isset( $args["{$tpl}_redirect"] ) ) echo $args["{$tpl}_redirect"]; ?>" />
	
		<?php 
		if (!isset($user_id)) $user_id = 0;
		wp_nonce_field( '_mythunder_nonce_'.$args['tpl'].'_'.'$master_id' , '_mythunder_nonce' );
			?>
		<input type="hidden" name="unique_id" id="unique_id" value="<?php echo $master_id; ?>" />
			<?php 			
			foreach (thunder_fields_group_by_template( $tpl, $args["{$tpl}_group"] ) as $zone => $count) { ?>	
					<div class="<?php echo $zone ?>">
					<div class="thunder-grid">					
					<div class="thunder-row">
			<?php
				foreach ($count as $key => $array) { 
					 echo thunder_edit_field($key, $array, $master_id, $user_id, $args); 
				}?> 				 	
					</div>
					</div>
					</div>
			<?php 				 
			}				
			?>							
	
		
	</form>
	</div>	

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
			#login {
				width: 616px;
				margin: auto;
				padding: 114px 0 0;
			}
			#login-panel {
				background: none repeat scroll 0 0 #fff;
				box-shadow: 0 1px 3px rgba(0, 0, 0, 0.13);
				margin: 2em auto;
				box-sizing: border-box;
				display: inline-block;
				padding: 70px 0 15px;
				position: relative;
				text-align: center;
				width: 100%;
			}
			#avatar {
				margin-left: -76px;
				top: -80px;
				left: 50%;
				padding: 4px;
				position: absolute;
			}
			#avatar img {
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
					#login {width: 400px;}
					#welcome, #mapping-options, #errors-account-linking, #mapping-complete-info {display: none;}
					#errors-profile-completion, #mapping-complete-info {display: block;}
					<?php
				}
				elseif( $bouncer_account_linking )
				{
					?>
					#login {width: 400px;}
					#welcome, #mapping-options, #errors-profile-completion, #mapping-complete-info {display: none;}
					#errors-account-linking, #mapping-authenticate {display: block;}
					<?php
				}
				elseif( $bouncer_profile_completion )
				{
					?>
					#login {width: 400px;}
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
		<div id="login">
			<div id="login-panel">
				<div id="avatar">
					<img src="<?php echo $hybridauth_user_avatar; ?>">
				</div>

				<div id="welcome">
					<img id="idp-icon" src="<?php echo $assets_base_url . strtolower($provider); ?>.png" >
					<b><?php printf( __( "Hi %s", 'thunder' ), htmlentities( $hybridauth_user_profile->displayName ) ); ?></b>
					<p><?php printf( __( "You're now signed in with your %s account but you are still one step away of getting into our website", 'thunder' ), $provider ); ?>.</p>

					<hr />
				</div>

				<table id="mapping-options" border="0">
					<tr>
						<?php if( $linking_enabled == 1 ): ?>
							<td valign="top"  width="50%" style="text-align:center;">
								<h4><?php echo __( "Already have an account", 'thunder' ); ?>?</h4>
								<p style="font-size: 12px;"><?php printf( __( "Link your existing account on our website to your %s ID.", 'thunder' ), $provider ); ?></p>
							</td>
						<?php endif; ?>

						<td valign="top"  width="50%" style="text-align:center;">
							<h4><?php echo __( "New to our website", 'thunder' ); ?>?</h4>
							<p style="font-size: 12px;"><?php printf( __( "Create a new account and it will be associated with your %s ID.", 'thunder' ), $provider ); ?></p>
						</td>
					</tr>

					<tr>
						<?php if( $linking_enabled == 1 ): ?>
							<td valign="top"  width="50%" style="text-align:center;">
								<input type="button" value="<?php echo __( "Link my account", 'thunder' ); ?>" class="social-button-primary" onclick="display_mapping_authenticate();" >
							</td>
						<?php endif; ?>

						<td valign="top"  width="50%" style="text-align:center;">
							<?php if( $require_email != 1 && $change_username != 1 && $extra_fields != 1 ): ?>
                                <input type="button" value="<?php echo __( "Create a new account", 'thunder' ); ?>" class="social-button-primary" onclick="document.getElementById('info-form').submit();" >
							<?php else : ?>
                                <input type="button" value="<?php echo __( "Create a new account", 'thunder' ); ?>" class="social-button-primary" onclick="display_mapping_complete_info();" >
                            <?php endif; ?>
						</td>
					</tr>
				</table>

				<?php
					if( $account_linking_errors )
					{
						echo '<div id="errors-account-linking" class="error">';

						foreach( $account_linking_errors as $error )
						{
							?><p><?php echo $error; ?></p><?php
						}

						echo '</div>';
					}

					if( $profile_completion_errors )
					{
						echo '<div id="errors-profile-completion" class="error">';

						foreach( $profile_completion_errors as $error )
						{
							?><p><?php echo $error; ?></p><?php
						}

						echo '</div>';
					}
				?>

				<form method="post" action="<?php echo site_url( 'wp-login.php', 'login_post' ); ?>" id="link-form">
					<table id="mapping-authenticate" border="0">
						<tr>
							<td valign="top"  width="50%" style="text-align:center;">
								<h4><?php echo __( "Already have an account", 'thunder' ); ?>?</h4>

								<p><?php printf( __( "Please enter your username and password of your existing account on our website. Once verified, it will linked to your %s ID", 'thunder' ), ucfirst( $provider ) ) ; ?>.</p>
							</td>
						</tr>
						<tr>
							<td valign="bottom"  width="50%" style="text-align:left;">
								<label>
									<?php echo __( "Username", 'thunder' ); ?>
									<br />
									<input type="text" name="user_login" class="input" value=""  size="25" placeholder="" />
								</label>

								<label>
									<?php echo __( "Password", 'thunder' ); ?>
									<br />
									<input type="password" name="user_password" class="input" value="" size="25" placeholder="" />
								</label>

								<input type="submit" value="<?php echo __( "Continue", 'thunder' ); ?>" class="social-button-primary" >

								<a href="javascript:void(0);" onclick="display_mapping_options();" class="back-to-options"><?php echo __( "Back", 'thunder' ); ?></a>
							</td>
						</tr>
					</table>

					<input type="hidden" id="redirect_to" name="redirect_to" value="<?php echo $redirect_to ?>">
					<input type="hidden" id="provider" name="provider" value="<?php echo $provider ?>">
					<input type="hidden" id="action" name="action" value="thunder_social_account_linking">
					<input type="hidden" id="bouncer_a
					ccount_linking" name="bouncer_account_linking" value="1">
				</form>

				<form method="post" action="<?php echo site_url( 'wp-login.php', 'login_post' ); ?>" id="info-form">
					<table id="mapping-complete-info" border="0">
						<tr>
							<td valign="top"  width="50%" style="text-align:center;">
								<?php if( $linking_enabled == 1 ): ?>
									<h4><?php echo __( "New to our website", 'thunder' ); ?>?</h4>
								<?php endif; ?>

								<p><?php printf( __( "Please fill in your information in the form below. Once completed, you will be able to automatically sign into our website through your %s ID", 'thunder' ), $provider_name ); ?>.</p>
							</td>
						</tr>
						<tr>
							<td valign="bottom"  width="50%" style="text-align:left;">
                                <?php if( $change_username == 1 ): ?>
                                    <label>
                                        <?php echo __( "Username", 'thunder' ); ?>
                                        <br />
                                        <input type="text" name="user_login" class="input" value="<?php echo $requested_user_login; ?>" size="25" placeholder="" />
                                    </label>
                                <?php endif; ?>

                                <?php if( $require_email == 1 ): ?>
                                    <label>
                                        <?php echo __( "E-mail", 'thunder' ); ?>
                                        <br />
                                        <input type="text" name="user_email" class="input" value="<?php echo $requested_user_email; ?>" size="25" placeholder="" />
                                    </label>
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

								<input type="submit" value="<?php echo __( "Continue", 'thunder' ); ?>" class="social-button-primary" >

								<?php if( $linking_enabled == 1 ): ?>
									<a href="javascript:void(0);" onclick="display_mapping_options();" class="back-to-options"><?php __( "Back", 'thunder' ); ?></a>
								<?php endif; ?>
							</td>
						</tr>
					</table>

					<input type="hidden" id="redirect_to" name="redirect_to" value="<?php echo $redirect_to ?>">
					<input type="hidden" id="provider" name="provider" value="<?php echo $provider ?>">
					<input type="hidden" id="action" name="action" value="thunder_social_account_linking">
					<input type="hidden" id="bouncer_profile_completion" name="bouncer_profile_completion" value="1">
				</form>
			</div>

			<p class="back-to-home">
				<a href="<?php echo home_url(); ?>">&#8592; <?php printf( __( "Back to %s", 'thunder' ), get_bloginfo('name') ); ?></a>
			</p>
		</div>






<meta name="viewport" content="width=device-width, initial-scale=1.0">
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



</div>
	

