<div class="thunder thunder-<?php echo $master_id; ?> thunder-<?php echo $layout; ?>" >	
	<div class="login-thunder-body">	
	<form action="" method="post" data-tpl="<?php echo $tpl; ?>" data-required_field="<?php echo __('This field is required', 'thunder'); ?>">

		<input type="hidden" name="redirect_uri-<?php echo $master_id; ?>" id="redirect_uri-<?php echo $master_id; ?>" value="<?php if (isset( $args["{$tpl}_redirect"] ) ) echo $args["{$tpl}_redirect"]; ?>" />
		<input type="hidden" name="user_id-<?php echo $master_id; ?>" value="<?php echo $user_id; ?>" />
		<?php 
		if (!isset($user_id)) $user_id = 0;
		wp_nonce_field( '_mythunder_nonce_'.$args['tpl'].'_'.'$master_id' , '_mythunder_nonce' );
			?>
		<input type="hidden" name="unique_id" id="unique_id" value="<?php echo $master_id; ?>" />
	
		<input type="button" data-tpl="view" class="fields-trigger" value="<?php echo __('View Profile','thunder'); ?>" />	

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

</div>