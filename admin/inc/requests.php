<h3><?php echo __('Users requesting verified status','thunder'); ?></h3>

<?php
//global $thunder;
$requests = get_option('thunder_verify_requests');
if (is_array($requests) && $requests != '' && !empty($requests) ) : $requests = array_reverse($requests);
?>
<?php foreach( $requests as $user_id) : $user = get_userdata($user_id); if ($user) : ?>
<div class="upadmin-pending-verify">
	<div class="upadmin-pending-img"><a href="<?php echo permalink($user_id); ?>" target="_blank"><?php echo get_avatar( $user_id, 64 ); ?></a></div>
	<div><a href="<?php echo permalink($user_id); ?>" target="_blank"><?php echo thunder_profile_data('display_name', $user_id); ?></a></div>
	<div><span><?php echo $user->user_email; ?></span></div>
	<div>
		<a href="#" class="button button-primary upadmin-verify" data-user="<?php echo $user_id; ?>"><?php echo __('Verify','thunder'); ?></a>
		<a href="#" class="button upadmin-unverify" data-user="<?php echo $user_id; ?>"><?php _e('Reject','thunder'); ?></a>
	</div>
</div>
<?php else : delete_pending_request($user_id); endif; endforeach; ?>

<?php else : ?>
<p><?php echo __('No users are requesting verification badge.','thunder'); ?></p>
<?php endif; ?>





<h3><?php echo __('Users awaiting manual review','thunder'); ?></h3>

<?php

	$users = get_users(array(
		'meta_key'     => '_account_status',
		'meta_value'   => 'pending_admin',
		'meta_compare' => '=',
	));
	
	if (!empty($users)){
	foreach($users as $user) {
		$user_id = $user->ID;
	
	?>
		
		<div class="upadmin-pending-verify">
			<div class="upadmin-pending-img"><a href="<?php echo permalink($user_id); ?>" target="_blank"><?php echo get_avatar( $user_id, 64 ); ?></a></div>
			<div><a href="<?php echo permalink($user_id); ?>" target="_blank"><?php echo thunder_profile_data('display_name', $user_id); ?></a></div>
			<div><span><?php echo $user->user_email; ?></span></div>
			<div>
				<a href="#" class="button button-primary upadmin-user-approve" data-user="<?php echo $user_id; ?>"><?php echo __('Approve','thunder'); ?></a>
				<a href="#" class="button upadmin-user-deny" data-user="<?php echo $user_id; ?>"><?php echo __('Delete user','thunder'); ?></a>
			</div>
		</div>

	<?php
	}
	
	} else {
			?>
			<p><?php echo __('No users are pending email confirmation yet.','thunder'); ?></p>
			<?php
	}
	
?>

<h3><?php echo __('Users awaiting/have not verified e-mail','thunder'); ?></h3>

<?php

	$users = get_users(array(
		'meta_key'     => '_account_status',
		'meta_value'   => 'pending',
		'meta_compare' => '=',
	));
	
	if (!empty($users)){
	foreach($users as $user) {
		$user_id = $user->ID;
	
	?>
		
		<div class="upadmin-pending-verify">
			<div class="upadmin-pending-img"><a href="<?php echo permalink($user_id); ?>" target="_blank"><?php echo get_avatar( $user_id, 64 ); ?></a></div>
			<div><a href="<?php echo permalink($user_id); ?>" target="_blank"><?php echo thunder_profile_data('display_name', $user_id); ?></a></div>
			<div><span><?php echo $user->user_email; ?></span></div>
			<div>
				<a href="#" class="button button-primary upadmin-user-approve" data-user="<?php echo $user_id; ?>"><?php echo __('Activate','thunder'); ?></a>
				<a href="#" class="button upadmin-user-deny" data-user="<?php echo $user_id; ?>"><?php echo __('Delete user','thunder'); ?></a>
			</div>
		</div>

	<?php
	}
	
	} else {
			?>
			<p><?php echo __('No users are pending email confirmation yet.','thunder'); ?></p>
			<?php
	}
	
?>