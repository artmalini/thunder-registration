<?php
class Thunder_Admin {
	
	/**
	 * A reference to an instance of this class.
	 *	 
	 * @var object
	 * 
	 */		
	private static $instance = null;

	public function __construct() {	
		
		$this->slug = 'thunder';
		/* Priority actions */
		add_action( 'admin_menu', array( $this, 'add_menu' ), 9 );
		add_action( 'admin_init', array( $this, 'admin_include' ), 9 );
		//add_action('admin_enqueue_scripts', array($this, 'add_styles'), 9);

		//add_action('admin_head', array($this, 'admin_head'), 9 );
		add_action( 'admin_init', array( $this, 'admin_tabs_init' ), 9 );
		add_action( 'admin_enqueue_scripts', array( $this, 'add_style' ), 10 );		
	}
	
	public function admin_include() {
		require_once THUNDER_DIR . '/admin/admin-ajax.php';
		require_once THUNDER_DIR . '/admin/admin-users-page.php';
	}


	public function admin_tabs_init() {		
		$this->tabs = array(
			'fields' => __( 'Customize Fields','thunder' ),
			'settings' => __( 'Global Options','thunder' ),
			'fieldroles' => __( 'Role-based Fields','thunder' ),
			'pages' => __( 'Page Setup','thunder' ),
			'requests' => sprintf( __( '%s Pending Requests', 'thunder' ), $this->pending_requests_ppl() ),

		);
		$this->default_tab = 'fields';
		$this->options = get_option( 'thunder-registration' );

		if ( ! get_option( 'thunder-registration' ) ) {
			update_option( 'thunder-registration', thunder_default() );
		};
	}

	/*	function pending_requests_ppl(){
		$count = 0;
		
		// verification status
		$pending = get_option('userpro_verify_requests');
		if (is_array($pending) && count($pending) > 0){
			$count = count($pending);
		}
		
		// waiting email approve
		$users = get_users(array(
			'meta_key'     => '_account_status',
			'meta_value'   => 'pending',
			'meta_compare' => '=',
		));
		if (isset($users)) {
			$count += count($users);
		}
		
		// waiting admin approve
		$users = get_users(array(
			'meta_key'     => '_account_status',
			'meta_value'   => 'pending_admin',
			'meta_compare' => '=',
		));
		if (isset($users)) {
			$count += count($users);
		}
		
		if ($count > 0){
			return '<span class="upadmin-bubble-new">'.$count.'</span>';
		}
	}*/

	public function add_style() {
		wp_register_style( 'thunder_admin', THUNDER_REG_URL . 'admin/script/style.css' );
		wp_enqueue_style( 'thunder_admin' );
		
		$cssstyle = get_option( 'thunder_fields_styles' );
		$cssstyle = $cssstyle['theme']['style'];  		// layout1
		
		wp_register_style( 'thunder_layout_one', THUNDER_REG_URL . 'css/layout1.css' );
		//if ( $cssstyle )
		//wp_register_style('thunder_layout_one', THUNDER_REG_URL . 'css/'.$cssstyle.'.css' );
		wp_register_style( 'font_select', THUNDER_REG_URL . 'css/fontselect.css' );

		wp_enqueue_style( 'thunder_layout_one' );
		wp_enqueue_style( 'font_select' );
		wp_enqueue_style('thickbox'); //wp uploader
		
		wp_enqueue_style( 'wp-color-picker' );

		//wp_register_script( 'thunder_touch_punch', THUNDER_REG_URL.'admin/script/touch-punch.min.js', array('jquery','jquery-ui-widget','jquery-ui-mouse') );
		wp_register_script( 'thunder_touch_punch', THUNDER_REG_URL.'admin/script/touch-punch.min.js', array('jquery', 'jquery-ui-widget', 'jquery-ui-mouse') );
		wp_register_script( 'thunder_fontselect', THUNDER_REG_URL.'admin/script/jquery.fontselect.js' );

		wp_register_script( 'thunder_frontgeneral', THUNDER_REG_URL.'admin/script/frontgeneral.js', array( 
			'thunder_touch_punch',
			'jquery-ui-sortable'			
		) );

		
		wp_enqueue_script( 'thunder_touch_punch' );
		wp_enqueue_script( 'thickbox' );//wp uploader
		wp_enqueue_script( 'media-upload');//wp uploader
        wp_enqueue_script( 'wp-color-picker' );
		wp_enqueue_script( 'thunder_fontselect' );

		wp_enqueue_script( 'thunder_frontgeneral' );

	} 
	
	/*function thun_adding_dynamic_styles() {	

		$custom_css = ".grid figcaption a, div.grid_no_animation figcaption a.button { background: {$wpb_wps_btn_bg}!important; }";
		$custom_css .= ".grid figcaption a:hover, div.grid_no_animation figcaption a.button:hover { background: {$wpb_wps_btn_bg_hover}!important; }";
		$custom_css .= ".wpb_slider_area .owl-theme .owl-controls .owl-page span { background: {$wpb_pagi_btn_bg}; }";
		$custom_css .= ".wpb_slider_area .owl-theme .owl-controls .owl-page.active span, .wpb_slider_area .owl-theme .owl-controls.clickable .owl-page:hover span { background: {$wpb_pagi_btn_bg_ac}; }";
		$custom_css .= ".wpb_slider_area .owl-theme .owl-controls .owl-buttons > div { background: {$wpb_nav_btn_bg}; }";
		$custom_css .= ".wpb_slider_area .owl-theme .owl-controls.clickable .owl-buttons > div:hover { background: {$wpb_nav_btn_bg_ac}; }";
		$custom_css .= "div.grid_no_animation figcaption .pro_price_area .amount { color: {$wpb_pro_price_color_i}; }";

		wp_add_inline_style( 'thun_main_style', $custom_css );
	}*/

	public function add_menu() {	
		/*$count = 0;	
		$users = get_users( array(
			'meta_key'     => '_account_status',
			'meta_value'   => 'pending',
			'meta_compare' => '=',
		) ); 

		if ( isset( $users ) ) {
			$count += count( $users );
		};

		$users = get_users( array(
			'meta_key'     => '_account_status',
			'meta_value'   => 'pending_admin',
			'meta_compare' => '=',
		));

		if (isset($users)) {
			$count += count($users);
		};
				
		$pending_count = $count;*/
		$pending_count = $this->number_requests_ppl();
		$pending_title = esc_attr( sprintf( __( '%d new verification requests','thunder'), $pending_count ) );

		if ( $pending_count > 0 ){
			$menu_label = sprintf( __( 'Thunder Registration %s','thunder' ), "<span class='update-plugins count-$pending_count' title='$pending_title'><span class='pending-count'>" . number_format_i18n($pending_count) . "</span></span>" );
			} else {				
				$menu_label = __('Thunder Registration', 'thunder-registration');
		};
		
		add_menu_page( __( 'Thunder Registration', 'thunder-registration' ), $menu_label, 'manage_options', $this->slug, array( $this, 'admin_page' ), THUNDER_REG_URL . 'admin/img/thundermain.png', '199.150' );
	}

	public function admin_page() {

		if ( isset( $_POST['submit'] ) ) {
			$this->save();
		};		

		if ( isset( $_POST['reset-options'] ) ) {
			$this->reset();
		};
	?>
	<div class="wrap thunder-admin">
		<h2 class="nav-tab-wrapper"><?php echo $this->tabs(); ?></h2>
		<div class="thunder-admin-contain">			
	<?php  
		$page = get_current_screen();

		if( strstr( $page->id, $this->slug ) ) {
			if ( isset ( $_GET['tab'] ) ) {
				$tab = $_GET['tab'];
			} else {
				$tab = $this->default_tab;
			};
			require_once THUNDER_DIR . 'admin/inc/' . $tab . '.php';
		};
	?>		
		<div class="clear"></div>
		</div>
	</div>
	<?php 
	}



	public function tabs( $current = null ) {
		$tabs = $this->tabs;
		$links = array();

		if ( isset ( $_GET['tab'] ) ) {
			$current = $_GET['tab'];
		} else {
			$current = $this->default_tab;
		};

		foreach( $tabs as $tab => $name ) {
			if ( $tab == $current ) {
				$links[] = "<a class='nav-tab nav-tab-active' href='?page=".$this->slug."&tab=$tab'>$name</a>";			
			} else {
				$links[] = "<a class='nav-tab' href='?page=".$this->slug."&tab=$tab'>$name</a>";
			};
		};

		foreach ( $links as $link ) {
			echo $link;
		};
	}
		
		

	function save() {
	
		/* restrict tab */
		if ( isset( $_GET['tab'] ) && $_GET['tab'] == 'restrict' ){
			$this->options['userpro_restricted_pages'] = '';
		};
		
		/* field roles tab */
		/*if (isset($_GET['tab']) && $_GET['tab'] == 'fieldroles'){
			$fields = get_option('userpro_fields');
			foreach($fields as $key => $field){
				$this->options[$key.'_roles'] = '';
			}
		}*/
		
		/* other post fields */
		foreach( $_POST as $key => $value ) {
			if ( $key != 'submit' ) {
				if ( ! is_array( $_POST[$key] ) ) {
					$this->options[$key] = stripslashes( esc_attr( $_POST[$key] ) );
				} else {
					$this->options[$key] = $_POST[$key];
				};
			};			
		};
		
		update_option( 'thunder-registration', $this->options );
		echo '<div class="updated"><p><strong>' . __( 'Settings saved.', 'thunder' ) . '</strong></p></div>';
	}

	function reset() {		
		echo '<div class="updated"><p><strong>' . __( 'Settings are reset to default.', 'thunder') . '</strong></p></div>';
	}

	function pending_requests_ppl() {
		return '<span class="count-request">'. $this->number_requests_ppl() .'</span>';
	}


	function number_requests_ppl() {
		$count = 0;
		
		// verification status
		/*$pending = get_option('thunder_verify_requests');
		if (is_array($pending) && count($pending) > 0){
			$count = count($pending);
		}*/
		
		// waiting email approve
		$users = get_users( array(
			'meta_key'     => '_account_status',
			'meta_value'   => 'pending',
			'meta_compare' => '=',
		));

		if ( isset( $users ) ) {
			$count += count( $users );
		};
		
		// waiting admin approve
		$users = get_users( array(
			'meta_key'     => '_account_status',
			'meta_value'   => 'pending_admin',
			'meta_compare' => '=',
		));

		if ( isset( $users ) ) {
			$count += count( $users );
		};
		
		if ( $count > 0 ){
			return $count;
		};
	}

	/**
	 * Returns the instance.
	 *		 
	 * @return object
	 */
	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		};
		return self::$instance;
	}
}// end class
	Thunder_Admin::get_instance();


?>