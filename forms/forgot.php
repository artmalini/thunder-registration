<div class="thunder thunder-<?php echo $master_id; ?> thunder-<?php echo $layout; ?>" data-required_field="<?php echo __('This field is required', 'thunder'); ?>" >

<div class="thunder-body">

	<div class="thunder-head"><?php echo $args["{$template}_header"]; ?></div>
	
	<form action="" method="post" data-action="<?php echo $template; ?>">
		<!-- fields -->
		<div class='thunder-field' data-key='username_or_email'>
			<div class='thunder-label <?php if ($args['field_icons'] == 1) { echo 'iconed'; } ?>'><label for='username_or_email-<?php echo $master_id; ?>'><?php _e('Username or Email','thunder'); ?></label></div>
			<div class='thunder-input'>
				<input type='text' name='username_or_email-<?php echo $master_id; ?>' id='username_or_email-<?php echo $master_id; ?>' />
				<div class='thunder-clear'></div>
			</div>
		</div><div class='thunder-clear'></div>
		
		<?php  $key = 'antispam'; $array = $array[$key];
			if (isset($array) && is_array($array)) echo thunder_edit_field( $key, $array, $master_id, $args ); ?>
	
	<?php 
	if (!isset($user_id)) $user_id = 0;
	wp_nonce_field( '_mythunder_nonce_'.$args['template'].'_'.'$master_id' , '_mythunder_nonce' );
		?>
	<input type="hidden" name="unique_id" id="unique_id" value="<?php echo $master_id; ?>" />
	
	<div class="thunder-submit">
		<?php if ($args["{$template}_button_primary"]) { ?>
				<input type="submit" value="<?php echo $args["{$template}_button_primary"]; ?>" class="thunder-button" />
				<?php } ?>
				
		<?php if ($args["{$template}_button_secondary"]) { ?>
				<input type="button" value="<?php echo $args["{$template}_button_secondary"]; ?>" class="thunder-button secondary" data-template="<?php echo $args["{$template}_button_action"]; ?>" />
				<?php } ?>
	</div>

	</form>


</div>


</div>