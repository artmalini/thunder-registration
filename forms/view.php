<div class="thunder thunder-<?php echo $master_id; ?> thunder-<?php echo $layout; ?>" >	
	<div class="login-thunder-body">	
	
	<input type="button" data-tpl="edit" class="fields-trigger" value="<?php echo __('Edit Profile','thunder'); ?>" />	
	
	<form action="" method="post" data-tpl="<?php echo $tpl; ?>" data-required_field="<?php echo __('This field is required', 'thunder'); ?>">
	
		<input type="hidden" name="redirect_uri-<?php echo $master_id; ?>" id="redirect_uri-<?php echo $master_id; ?>" value="<?php if (isset( $args["{$tpl}_redirect"] ) ) echo $args["{$tpl}_redirect"]; ?>" />
	
		<?php 		
		/*if (!isset($user_id)) $user_id = 0;
		wp_nonce_field( '_mythunder_nonce_'.$args['tpl'].'_'.'$master_id' , '_mythunder_nonce' );*/
			?>
		<input type="hidden" name="user_id-<?php echo $master_id; ?>" value="<?php echo $user_id; ?>" />
			<?php 
			foreach (thunder_fields_group_by_template( $tpl, $args["{$tpl}_group"] ) as $zone => $count) { 
			//foreach (thunder_fields_group_by_template( 'view', $args["view_group"] ) as $zone => $count) {
			?>	
					<div class="<?php echo $zone ?>">
					<div class="thunder-grid">					
					<div class="thunder-row">
			<?php
				foreach ($count as $key => $key_array) { 
					 echo thunder_show_field($key, $key_array, $master_id, $args, $user_id, $zone); 
				}?> 				 	
					</div>
					</div>
					</div>
			<?php 				 
			}				
			?>							
	
		
	</form>
	</div>	

</div>
	

