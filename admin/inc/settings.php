<?php 
	//$array = get_option('thunder-registration');
	//$array = get_option('thunder_fields_groups');	
	//print_r($array);
	$config;
	if( thunder_get_option( $provider . '_sdk_id' ) ) {
		$config[] = $config["providers"][$provider]["keys"]["id"] = thunder_get_option( $provider . '_sdk_id' );
	}
	/*$provider = 'Facebook';
	echo thunder_get_option( $provider . '_sdk_id' );*/



	$thumbnail_base_url = THUNDER_REG_URL . 'admin/img/';
 ?>
<form method="post" action="">

<h3><?php __('Login Settings','thunder'); ?></h3>
<table class="form-table">

	<tr valign="top">
		<th scope="row"><label for="admin_page_after_login"><?php _e('Redirect to admin dashboard always','thunder'); ?></label></th>
		<td>
			<select name="admin_page_after_login" id="admin_page_after_login" class="gchosen-select" style="width:300px">
				<option value="1" <?php selected(1, thunder_get_option('admin_page_after_login')); ?>><?php _e('Yes','thunder'); ?></option>
				<option value="0" <?php selected(0, thunder_get_option('admin_page_after_login')); ?>><?php _e('No','thunder'); ?></option>
			</select>
		</td>
	</tr>


	<tr valign="top" class="after_login">
		<th scope="row"><label for="users_login_redirect"><?php echo __('Redirect after a successful login','thunder'); ?></label></th>
		<td>
			<select name="users_login_redirect" id="users_login_redirect" class="gchosen-select" style="width:300px">
				<option value="no_redirect" <?php selected("no_redirect", thunder_get_option('users_login_redirect')); ?>><?php echo __('Refresh current page','thunder'); ?></option>
				<option value="profile" <?php selected("profile", thunder_get_option('users_login_redirect')); ?>><?php echo __('Redirect user to front-end profile','thunder'); ?></option>
			</select>
		</td>
	</tr>
	

	<tr valign="top" class="after_login">
		<th scope="row"><label for="login_redirect"><?php echo __('Custom page after a successful login','thunder'); ?></label></th>
		<td>
			<input type="text" name="login_redirect" id="login_redirect" value="<?php echo thunder_get_option('login_redirect'); ?>"/>
			<span class="description"><?php echo __('Login redirect.','thunder'); ?></span>
		</td>
	</tr>	
	
</table>

<h3><?php __('Register Settings','thunder'); ?></h3>
<table class="form-table">

	<tr valign="top">
		<th scope="row"><label for="users_can_register"><?php echo __('Membership','thunder'); ?></label></th>
		<td>
			<select name="users_can_register" id="users_can_register" class="chosen-select" style="width:300px">
				<option value="1" <?php selected(1, thunder_get_option('users_can_register')); ?>><?php echo __('Anyone can register','thunder'); ?></option>
				<option value="0" <?php selected(0, thunder_get_option('users_can_register')); ?>><?php echo __('Disable registration','thunder'); ?></option>
			</select>
		</td>
	</tr>
	
	<tr valign="top">
		<th scope="row"><label for="users_approve"><?php echo __('New User Approval','thunder'); ?></label></th>
		<td>
			<select name="users_approve" id="users_approve" class="chosen-select" style="width:300px">
				<option value="1" <?php selected('1', thunder_get_option('users_approve')); ?>><?php echo __('Auto Approve','thunder'); ?></option>
				<option value="2" <?php selected('2', thunder_get_option('users_approve')); ?>><?php echo __('Require E-mail Activation','thunder'); ?></option>	
				<option value="3" <?php selected('3', thunder_get_option('users_approve')); ?>><?php echo __('Require Admin Activation','thunder'); ?></option>			
			</select>
		</td>
	</tr>

</table>

<h3><?php echo __('Module Settings','thunder'); ?></h3>
<table class="form-table">

	<tr valign="top">
		<th scope="row"><label for="modstate_social"><?php echo __('Social Features','thunder'); ?></label></th>
		<td>
			<select name="modstate_social" id="modstate_social" class="chosen-select" style="width:300px">
				<option value="1" <?php selected(1, thunder_get_option('modstate_social')); ?>><?php echo __('Activate','thunder'); ?></option>
				<option value="0" <?php selected(0, thunder_get_option('modstate_social')); ?>><?php echo __('Deactivate','thunder'); ?></option>
			</select>
		</td>
	</tr>
	
	<tr valign="top">
		<th scope="row"><label for="modstate_online"><?php echo __('Online/Offline Status','thunder'); ?></label></th>
		<td>
			<select name="modstate_online" id="modstate_online" class="chosen-select" style="width:300px">
				<option value="1" <?php selected(1, thunder_get_option('modstate_online')); ?>><?php echo __('Activate','thunder'); ?></option>
				<option value="0" <?php selected(0, thunder_get_option('modstate_online')); ?>><?php echo __('Deactivate','thunder'); ?></option>
			</select>
		</td>
	</tr>
	
	<tr valign="top">
		<th scope="row"><label for="modstate_showoffline"><?php echo __('Show Offline Icon','thunder'); ?></label></th>
		<td>
			<select name="modstate_showoffline" id="modstate_showoffline" class="chosen-select" style="width:300px">
				<option value="1" <?php selected(1, thunder_get_option('modstate_showoffline')); ?>><?php echo __('Yes','thunder'); ?></option>
				<option value="0" <?php selected(0, thunder_get_option('modstate_showoffline')); ?>><?php echo __('No','thunder'); ?></option>
			</select>
		</td>
	</tr>
	
</table>

<h3><?php echo __('General','thunder'); ?></h3>
<table class="form-table">

	
	<tr valign="top">
		<th scope="row"><label for="permalink_type"><?php echo __('Profile Permalink Structure','thunder'); ?></label></th>
		<td>
			<select name="permalink_type" id="permalink_type" class="chosen-select" style="width:300px">
				<option value="ID" <?php selected('ID', thunder_get_option('permalink_type')); ?>><?php echo __('User ID','thunder'); ?></option>
				<option value="username" <?php selected('username', thunder_get_option('permalink_type')); ?>><?php echo __('Username','thunder'); ?></option>
				<option value="name" <?php selected('name', thunder_get_option('permalink_type')); ?>><?php echo __('Full Name','thunder'); ?></option>
				<option value="display_name" <?php selected('display_name', thunder_get_option('permalink_type')); ?>><?php echo __('Display Name','thunder'); ?></option>
			</select>
			<span class="description"><?php echo __('User profiles permalink structure setting e.g. /profile/34 or /profile/Username or /profile/FirstName+LastName If you have a problem with permalink structure, you can try to change this setting.','thunder'); ?></span>
		</td>
	</tr>
	
	<tr valign="top">
		<th scope="row"><label for="user_display_name"><?php echo __('User Display Name Field','thunder'); ?></label></th>
		<td>
			<select name="user_display_name" id="user_display_name" class="chosen-select" style="width:300px">
				<option value="display_name" <?php selected('display_name', thunder_get_option('user_display_name')); ?>><?php echo __('Default (Display Name)','thunder'); ?></option>
				<option value="name" <?php selected('name', thunder_get_option('user_display_name')); ?>><?php echo __('Full Name','thunder'); ?></option>
			</select>
		</td>
	</tr>
	
	<tr valign="top">
		<th scope="row"><label for="user_display_name_key"><?php echo __('Replace Display Name with custom field','thunder'); ?></label></th>
		<td>
			<input type="text" name="user_display_name_key" id="user_display_name_key" value="<?php echo thunder_get_option('user_display_name_key'); ?>"/>
			<span class="description"><?php echo __('Enter custom field key to override default display name on profiles with this custom field value.','thunder'); ?></span>
		</td>
	</tr>
	
	<tr valign="top">
		<th scope="row"><label for="hidden_from_view"><?php echo __('Fields to hide completely from profile view','thunder'); ?></label></th>
		<td>
			<input type="text" name="hidden_from_view" id="hidden_from_view" value="<?php echo thunder_get_option('hidden_from_view'); ?>"/>
			<span class="description"><?php echo __('A comma seperated list of custom fields to hide from profile view anyway.','thunder'); ?></span>
		</td>
	</tr>
	
	<tr valign="top">
		<th scope="row"><label for="unverify_on_namechange"><?php echo __('Unverify Verified accounts automatically if they change display name','thunder'); ?></label></th>
		<td>
			<select name="unverify_on_namechange" id="unverify_on_namechange" class="chosen-select" style="width:300px">
				<option value="1" <?php selected(1, thunder_get_option('unverify_on_namechange')); ?>><?php echo __('Yes','thunder'); ?></option>
				<option value="0" <?php selected(0, thunder_get_option('unverify_on_namechange')); ?>><?php echo __('No','thunder'); ?></option>
			</select>
		</td>
	</tr>
	
	<tr valign="top">
		<th scope="row"><label for="verified_badge_by_name"><?php echo __('Display verified account badge beside name','thunder'); ?></label></th>
		<td>
			<select name="verified_badge_by_name" id="verified_badge_by_name" class="chosen-select" style="width:300px">
				<option value="1" <?php selected(1, thunder_get_option('verified_badge_by_name')); ?>><?php echo __('Yes','thunder'); ?></option>
				<option value="0" <?php selected(0, thunder_get_option('verified_badge_by_name')); ?>><?php echo __('No','thunder'); ?></option>
			</select>
			<span class="description"><?php echo __('Should the verified account badge display beside name, or as a standard badge in badges.','thunder'); ?></span>
		</td>
	</tr>
	
	<tr valign="top">
		<th scope="row"><label for="verified_link"><?php echo __('Verified Badge Link','thunder'); ?></label></th>
		<td>
			<input type="text" name="verified_link" id="verified_link" value="<?php echo thunder_get_option('verified_link'); ?>"/>
			<span class="description"><?php echo __('Should the verified badge link to a specific page?','thunder'); ?></span>
		</td>
	</tr>
	
</table>


<h3><?php echo __('Social API','thunder'); ?></h3>
<table class="form-table">

	<tr valign="top">
		<th scope="row"><label for="facebook_sdk_enabled"><?php echo __('Enabled','thunder'); ?></label></th>
		<td>
			<select name="facebook_sdk_enabled" id="facebook_sdk_enabled" class="chosen-select" style="width:300px">
				<option value="1" <?php selected(1, thunder_get_option('facebook_sdk_enabled')); ?>><?php echo __('Yes','thunder'); ?></option>
				<option value="0" <?php selected(0, thunder_get_option('facebook_sdk_enabled')); ?>><?php echo __('No','thunder'); ?></option>
			</select>			
		</td>
	</tr>

	<tr valign="top">
		<th scope="row"><label for="Facebook_sdk_id"><?php echo __('Facebook Application ID:','thunder'); ?></label></th>
		<td>
			<input type="text" name="Facebook_sdk_id" value="<?php echo thunder_get_option('Facebook_sdk_id'); ?>"/>			
			<a href="#" class="facebook_sdk"><?php echo __('More info?','thunder'); ?></a>
		</td>
	</tr>
	<tr valign="top">	
		<th scope="row"><label for="Facebook_sdk_secret"><?php echo __('Facebook Application Secret:','thunder'); ?></label></th>
		<td>
			<input type="text" name="Facebook_sdk_secret" value="<?php echo thunder_get_option('Facebook_sdk_secret'); ?>"/>
			<a href="#" class="facebook_sdk"><?php echo __('More info?','thunder'); ?></a>
		</td>
	</tr>		
	<div class="help-facebook">
		<?php echo sprintf( __('<p>In order to get an App ID and Secret Key from Facebook, you’ll need to register a new application. This application will link your website <b>%s</b> to Facebook API and these credentials are needed in order for Facebook users to access your website.  </p>','thunder'), $_SERVER["SERVER_NAME"]); ?>
		<div style="margin-left: 30px;">
			<?php echo __('<p><b>1)</b> Go to: <a href="https://developers.facebook.com">developers.facebook.com</a>. You’ll need to login to your Facebook account. Once logged in, you’ll see a screen similar to this:</p>','thunder'); ?>
			<p><a href="<?php echo $thumbnail_base_url . 'facebook/1.jpg' ?>" target="_blank"><img class="social-thumbnail" src="<?php echo $thumbnail_base_url . 'facebook/1.jpg' ?>"></a></p>			
			<?php echo __('<p>To begin, click the green “Add a New App”</p>','thunder'); ?>

			<?php echo __('<p><b>2)</b> Once you’ve clicked “Add a New App”, a box will appear asking you for your new App’s Display Name, Contact E-Mail Address. Just pick a name that is easy for you to remember.</p>','thunder'); ?>	
				<p><a href="<?php echo $thumbnail_base_url . 'facebook/2.jpg' ?>" target="_blank"><img class="social-thumbnail" src="<?php echo $thumbnail_base_url . 'facebook/2.jpg' ?>"></a></p>	

			<?php echo __('<p><b>3)</b> After you’ve filled out the required fields and clicked Create a New App ID, you’ll be taken to your new App’s dashboard. From here, you’ll need to click on the Settings link to view your App ID and App Secret.</p>','thunder'); ?>	
				<p><a href="<?php echo $thumbnail_base_url . 'facebook/3.jpg' ?>" target="_blank"><img class="social-thumbnail" src="<?php echo $thumbnail_base_url . 'facebook/3.jpg' ?>"></a></p>	

			<?php echo __('<p><b>4)</b> Go back to this page and past the created application credentials (APP ID and App Secret) into the (Facebook Application ID and Facebook Application Secret ).</p>','thunder'); ?>
			
		</div>
	</div>
	
</table>

<!-- <h3><?php  ?></h3>
<table class="form-table">	

	<tr valign="top" class="after_login">
		<th scope="row"><label for="login_redirect"><?php  ?></label></th>
		<td>
			<input type="text" name="login_redirect" id="login_redirect" value="<?php  ?>"/>
			<span class="description"><?php  ?></span>
		</td>
	</tr>	
	
</table> -->

<p class="submit">
	<input type="submit" name="submit" id="submit" class="button button-primary" value="<?php echo __('Save Changes','thunder'); ?>"  />
	<input type="submit" name="reset-options" id="reset-options" class="button" value="<?php echo __('Reset Options','thunder'); ?>"  />
</p>

</form>