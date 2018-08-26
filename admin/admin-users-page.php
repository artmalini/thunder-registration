<?php 
add_filter( 'manage_users_columns', 'thunder_add_column' );
function thunder_add_column( $columns ) {	
    $columns['pending-request'] = __( 'Pending', 'thunder');	
    return $columns;
}
 
add_action( 'manage_users_custom_column', 'thunder_parse_requsts', 10, 3 );
function thunder_parse_requsts( $value, $column_name, $user_id ) {
    $user = get_userdata( $user_id );
	$id = 0;
	$result = '';	

	if ( thunder_get_option( 'backend_users_change' ) ) {
		$users = get_users( array(
			'meta_key'     => '_account_status',
			'meta_value'   => 'pending',
			'meta_compare' => '=',
		) );	

		if ( ! empty( $users ) ) {
			foreach( $users as $user ) {
				$id = $user->ID;
				if ( $id == $user_id ) {
					$result = '<div class="upadmin-pending-verify">			
						<a href="#" class="button button-primary upadmin-user-approve" data-user="' . $user_id . '">' . __( 'Activate', 'thunder') .'</a>
						</div>';
				};
			};
		};

		$users = get_users( array(
			'meta_key'     => '_account_status',
			'meta_value'   => 'pending_admin',
			'meta_compare' => '=',
		) );	

		if ( ! empty( $users ) ) {
			foreach($users as $user) {
				$id = $user->ID;
				if ( $id == $user_id ) {
					$result = '<div class="upadmin-pending-verify">			
						<a href="#" class="button button-primary upadmin-user-approve" data-user="' . $user_id . '">' . __( 'Approve', 'thunder') . '</a>
						</div>';
				};
			};
		};		
	};
	return $result;	
 }

/*add_action('manage_users_custom_column',  'userpro_admin_users_badges', 10, 3);
function userpro_admin_users_badges($value, $column_name, $user_id) {
	global $userpro;
    $user = get_userdata( $user_id );
	
	if (userpro_get_option('backend_users_change')){
	if ( 'userpro_username' == $column_name) {
		$res = '<div class="upadmin-avatar">'.get_avatar($user_id, 40).'</div>';
		$res .= '<strong><a href="'.$userpro->permalink($user_id).'" target="_blank" title="'.__('View Profile','userpro').'">'.$user->user_login.'</a></strong><br />';
		$res .= '<span class="upadmin-small-name">('.userpro_profile_data('display_name', $user_id).')</span>';
		$res .= '<div class="row-actions"><span class="edit"><a href="'.$userpro->permalink($user_id, 'edit').'" target="_blank">'.__('Edit Profile','userpro').'</a></span></div>';
		return $res;
	}
	}
	
	if ( 'userpro_admin_badges' == $column_name ) {
		$res = userpro_show_badges($user_id, true);
		return $res;
	}*/
?>