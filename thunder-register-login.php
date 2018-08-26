<?php 
/**
 * Plugin Name:       Thunder Registration master
 * Plugin URI:        https://github.com/artmalini/
 * Description:       Plugin for login.
 * Version:           1.0
 * Author:            Artem Makhinya
 * Author URI:        https://github.com/artmalini/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       thunder-registration
 * Domain Path:       /languages
 */

if ( !defined ( 'ABSPATH' ) ) {
    exit;
} // Exit if accessed directly

if ( !class_exists( 'Thunder_Registration' ) ) {

	class Thunder_Registration {

		/**
		 * A reference to an instance of this class.
		 *
		 * @since 1.0.0
		 * @var   object
		 */		
		private static $instance = null;
		
		public function __construct() {
			// Set redirect
			add_action( 'activated_plugin', array( $this, 'redirect' ), 1 );
			// Set the constants needed by the plugin.
			add_action( 'plugins_loaded', array( $this, 'constants' ), 2 );
			// Load the functions files.
			add_action( 'plugins_loaded', array( $this, 'includes' ),  3 );

			// Load public-facing style sheet.
			//add_action( 'admin_enqueue_scripts', array( $this, 'thunder_registration_scripts' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'thunder_registration_scripts' ), 31);
			//add_action('wp_enqueue_scripts', array( $this, 'thunder_main_styles' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'myajax_data' ), 99 );		
						
		}

		function constants()	{			
			/**
			 * Define Path 
			 */
			define( 'THUNDER_REG_URL', trailingslashit( plugin_dir_url( __FILE__ ) ) );
			define( 'THUNDER_DIR', trailingslashit( plugin_dir_path( __FILE__ ) ) );
			define( 'THUNDER', plugin_dir_url( __FILE__ ).'js/jQuery.stringify.js'  );			
			
			/*define( 'THUNDER_FORM_STRUCTURE', '[[{"name":"log","avatar:"'.get_avatar_url( get_the_author_meta('user_email'), 32 ).'"","index":0,"title":"Username","required":true,"ftype":"ftext","userhelp":"","csslayout":"","predefined":"","size":"medium","shortlabel":"","userhelpTooltip":false,"predefinedClick":false,"minlength":"","maxlength":"","equalTo":""},{"name":"pwd","index":1,"shortlabel":"","ftype":"fpassword","userhelp":"","userhelpTooltip":false,"csslayout":"","title":"Password","predefined":"","predefinedClick":false,"required":false,"size":"medium","minlength":"","maxlength":"","equalTo":""}],[{"title":"Test","description":"","formlayout":"top_aligned"}]]' );*/
		}

		public function myajax_data() {
			 // User ID
		    $user_id = ! empty( $_GET['user_id'] )
		        ? (int) $_GET['user_id']
		        : get_current_user_id();
			wp_localize_script('my', 'my_data', array(
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
				'upload' =>  THUNDER_REG_URL . 'files/upload.php',			
				'ajaxnonce' => wp_create_nonce('myajax-nonce'),
				'user_id' => $user_id
			));
		}


		function includes() {
			/**
			 * Require Files
			 */
			session_start();
			//require_once dirname( __FILE__ ) . '/inc/thunder_db.php';
			//require_once dirname( __FILE__ ) . '/inc/thunder_registration.php';
			//require_once dirname( __FILE__ ) . '/inc/thunder_shortcodes.php';
			require_once dirname( __FILE__ ) . '/files/thunder-default.php';
			require_once dirname( __FILE__ ) . '/files/fields.php';

			require_once dirname( __FILE__ ) . '/files/bfi_thumb.php';
			require_once dirname( __FILE__ ) . '/files/fields-functions.php';
			require_once dirname( __FILE__ ) . '/files/thunder_ajax.php';
			require_once dirname( __FILE__ ) . '/inc/thunder_shortcode.php';		
			require_once dirname( __FILE__ ) . '/files/auth.php';
			
			require_once dirname( __FILE__ ) . '/admin/admin.php';

		do_upload_dir();
		}
		

		/**
		 * Adding scripts
		 */
		function thunder_registration_scripts() {

			//wp_deregister_script('stringify');	
			wp_register_script('stringify', trailingslashit( THUNDER_REG_URL ) . 'js/jQuery.stringify.js', array('jquery'),'1.0', false);
			wp_register_script('fileupload', trailingslashit( THUNDER_REG_URL ) . 'js/jquery.fileupload.js', null,'1.0', false);
			wp_register_script('validate', trailingslashit( THUNDER_REG_URL ) . 'js/jquery.validate.js', null,'1.0', false);
			wp_register_script('fbuilders', trailingslashit( THUNDER_REG_URL ) . 'js/fbuilder.jquery.js', array("jquery-ui-core","jquery-ui-sortable","jquery-ui-tabs","jquery-ui-droppable","jquery-ui-button","jquery-ui-datepicker",'stringify','fileupload','validate'),'1.0', false);
			
			wp_register_script('my', trailingslashit( THUNDER_REG_URL ) . 'js/my.js', array('fbuilders'),'1.0', true);
			wp_enqueue_script('my');

			wp_enqueue_style( 'thunder-style', trailingslashit( THUNDER_REG_URL ) . 'css/thunder-style.css', '', '1.0' );
			wp_enqueue_style( 'thunder-layout', trailingslashit( THUNDER_REG_URL ) . 'css/layout1.css', '', '1.0' );
		}

//не нужно
		function thunder_main_styles() {
			$array = get_option('thunder_fields_groups');
			$groups = $array['login']['default'];

			$stylesheet = null;
			foreach ($groups['head'] as $k => $styles) {
			foreach ($styles['styles'] as $tag => $arrays) {				
					$tick = 0;					
					$countarr = count($arrays) - 1;//exclude tagzone
				foreach ($arrays as $key => $css) {					
					if ($key != 'tagzone') {
						$tick++;
						if( $key != 'font-weight:' && is_numeric($css)) {
							$css = $css.'px';
						}
						if ($tick == 1) {
							if ($key == 'padding:' || $key == 'margin:') {
								$stylesheet .= '.thunder-body .head '.$tag.'{'.$key.''.$css['up'].'px '.$css['rt'].'px '.$css['bt'].'px '.$css['lf'].'px; ';
							} else {							
								$stylesheet .= '.thunder-body .head '.$tag.'{'.$key.''.$css.'; '; 
							}
						}
						if ($countarr == 1) {
						 	$stylesheet .= '}';//close brace if only one style	
						 	continue;//left last brace					 	
						 } 

						if ($tick > 1) { //cintinue adding styles if more than one	
							if ($key == 'padding:' || $key == 'margin:') {		
								$stylesheet .= $key.''.$css['up'].'px '.$css['rt'].'px '.$css['bt'].'px '.$css['lf'].'px; ';
							} else {							
								$stylesheet .= $key.''.$css.'; '; 
							}
						}								
							
						if ($tick == $countarr) {
							$stylesheet .= '}';
						}
						

					 //$stylesheet .= '.thunder-body '.$tag.' {'.$key.' '.$css.'}';
				 		} else {
							foreach ($css as $sectags => $secstyle) {
								$tick1 = 0;
								$countarr1 = count($secstyle);
								foreach ($secstyle as $newkey => $seccss) {
									$tick1++;
									if( $newkey != 'font-weight:' && is_numeric($seccss)) {
										$seccss = $seccss.'px';
									}
									if ($tick1 == 1) {
										if ($newkey == 'padding:' || $newkey == 'margin:') {
											$stylesheet .= '.thunder-body .head '.$tag.' '.$sectags.'{'.$newkey.''.$seccss['up'].'px '.$seccss['rt'].'px '.$seccss['bt'].'px '.$seccss['lf'].'px; ';
										} else {						
											$stylesheet .= '.thunder-body .head '.$tag.' '.$sectags.'{'.$newkey.''.$seccss.'; ';// .
										} 
									}
									if ($countarr1 == 1) {
									 	$stylesheet .= '}';//close brace if only one style	
									 	continue;					 	
									 } 									
									if ($tick1 > 1) {										 	
										if ($newkey == 'padding:' || $newkey == 'margin:') {
											$stylesheet .= $newkey.''.$seccss['up'].'px '.$seccss['rt'].'px '.$seccss['bt'].'px '.$seccss['lf'].'px; ';
										} else {							
											$stylesheet .= $newkey.''.$seccss.'; ';// .
										} 
											
									}
									if ($tick1 == $countarr1) {
										$stylesheet .= '}';//.count($secstyle);
									}

									
								}
							}//end foreach
						}			
				}
			}
		}

		//$cfd = ".thunder-body{background: #000;}";

			wp_add_inline_style( 'thunder-layout', $stylesheet );
		}


			 

		/**
		 * Plugin Activation redirect 
		 */		
		public function redirect( $plugin ) {
		    if( $plugin == plugin_basename( __FILE__ ) ) {
		        exit( wp_redirect( admin_url( 'options-general.php?page=thunder_reg_settings' ) ) );
		    }
		}
		
		
		/**
		 * Returns the instance.
		 *		 
		 * @return object
		 */
		public static function get_instance() {

			// If the single instance hasn't been set, set it now.
			if ( null == self::$instance )
				self::$instance = new self;

			return self::$instance;
		}
	}// end class
	Thunder_Registration::get_instance();
	
}

 ?>