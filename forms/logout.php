<?php 
$groups = get_option('thunder_fields_groups');
$groups = $groups['view']['default']['tcontent']['role'];
//$groups = $groups['view']['default'];
//print_r($groups);
 ?>
<div class="lol"><?php print_r($groups); ?></div>

<div class="thunder thunder-<?php echo $master_id; ?> thunder-<?php echo $layout; ?>" >	
	<div class="login-thunder-body">

		<div class="thunder-profile-img" data-key="picture"><a href="<?php echo permalink(); ?>"><?php echo get_avatar( $user_id, $profile_thumb_size ); ?></a></div>	
	
			<div class="thunder-profile-img-after">
				<div class="thunder-profile-name">
					<a href="<?php echo permalink(); ?>" title="<?php echo __('View/manage your profile','thunder'); ?>"><?php echo thunder_profile_data('display_name', $user_id); ?></a>
				</div>
				<div class="thunder-profile-img-btn">
					<a href="<?php echo permalink(); ?>" class="thunder-button secondary"><?php echo __('View Profile','thunder'); ?></a>
					<a href="<?php echo thunder_logout_url( $user_id, $args['url'], $args['logout_redirect'] ); ?>" class="thunder-button secondary"><?php echo __('Logout','thunder'); ?></a>
				</div>
			</div>

	
	</div>	

</div>