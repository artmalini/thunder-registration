<?php 
if ( ! defined ( 'ABSPATH' ) ) {
    exit;
} // Exit if accessed directly

if ( !class_exists('Main_Settings' ) ):
class Main_Settings extends Thunder_Db {

    private $settings_api;

		/**
	 * A reference to an instance of this class.
	 *
	 * @since 1.0.0
	 * @var   object
	 */
	private static $instance = null;

    function __construct() {
        
        $this->thunder_db_install(); 

          
         add_action( 'plugins_loaded', array($this, 'save_options') ); 

         add_action( 'wp_ajax_upload_avatar', array($this, 'upload_avatar') );         
       // add_action( 'wp_ajax_nopriv_data_management', array($this, 'data_management') );
        
        // Avatar defaults
        add_filter( 'avatar_defaults', array($this, 'thunder_user_avatars_avatar_defaults') );
        add_filter( 'get_avatar', array($this, 'thunder_user_avatars_filter_get_avatar'), 10, 5 );
        
        add_action( 'admin_init', array($this, 'admin_init') );
        add_action( 'admin_menu', array($this, 'admin_menu') );
    }

    function admin_init() {
        //set the settings        
        //$this->settings_api->set_sections( $this->get_settings_sections() );
        //$this->settings_api->set_fields( $this->get_settings_fields() );
        //initialize settings    
        
        //$this->settings_api->initialize();
    }
	
    function admin_menu() {
        add_menu_page(
         __( 'Thunder Registration', 'thunder-registration' ),	// The title to be displayed in the browser window for   this page.
         __( 'Thunder Registration', 'thunder-registration' ),  // The text to be displayed for this menu item
         'administrator',								// Which type of users can see this menu item
         'thunder_reg_settings',								// The unique ID - that is, the slug - for this menu item
         array($this, 'uc_woo_display')					// The name of the function to call when rendering this menu's page
        );
    }

	
   
       function get_dboptio ( $field, $dbnm, $default = '' ) {
        $option = get_option($dbnm);
        if ( isset($option[$field]) ) {
            return $option[$field];
        } else {
            return $default;
        }
    }


    function uc_woo_display() {

$nonce = wp_create_nonce( 'thunder_update_actions_post' );
if ( 'POST' == $_SERVER['REQUEST_METHOD'] && isset( $_POST['thunder_post_options'] ) )
    echo "<div id='setting-error-settings_updated' class='updated settings-error'> <p><strong>Settings saved.</strong></p></div>";

		?>
        <form method="post" action="" name="cpformconf" enctype="multipart/form-data">
		<div id="metabox_basic_settings" class="postbox" >
  <h3 class='hndle' style="padding:5px;"><span>Form Builder</span></h3>
  <div class="inside">   
<input name="thunder_post_options" type="hidden" value="1" />
<input name="rsave" type="hidden" value="<?php echo $nonce; ?>" />
<input name="thunder_id" type="hidden" value="<?php echo $this->item; ?>" />

      <input type="hidden" name="form_structure" id="form_structure" size="180" value="<?php echo esc_attr($this->cleanJSON($this->get_dboption('form_structure', ''))); ?>" />
        
     <script type="text/javascript">                 
       /*if (typeof jQuery === "undefined") {
          document.write ("<"+"script type='text/javascript' src='https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js'></"+"script>");
          document.write ("<"+"script type='text/javascript' src='https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.20/jquery-ui.min.js'></"+"script>");
       }*/
       $easyFormQuery = jQuery.noConflict();
       
     </script> 
             
     <script>
         
         $easyFormQuery(document).ready(function() {
            var f = $easyFormQuery("#fbuilder").fbuilder();
            console.log(f.fBuild);
            f.fBuild.loadData("form_structure");
            
            $easyFormQuery("#saveForm").click(function() {       
                f.fBuild.saveData("form_structure");
            });  
                 
            $easyFormQuery(".itemForm").click(function() {
               f.fBuild.addItem($easyFormQuery(this).attr("id"));
           });  
          
           $easyFormQuery( ".itemForm" ).draggable({revert1: "invalid",helper: "clone",cursor: "move"});
           $easyFormQuery( "#fbuilder" ).droppable({
               accept: ".button",
               drop: function( event, ui ) {
                   f.fBuild.addItem(ui.draggable.attr("id"));               
               }
           });
                
         }); 
        var randcaptcha = 1;
        /*
        hhhhhhhhhhhhhhhhhhhhh
        */

     </script>
     
     <div style="background:#fafafa;width:780px;" class="form-builder">
     
         <div class="column width50">
             <div id="tabs">              
                <ul>
                    <li><a href="#tabs-1">Add a Field</a></li>
                    <li><a href="#tabs-2">Field Settings</a></li>
                    <li><a href="#tabs-3">Form Settings</a></li>
                </ul>
                <div id="tabs-1">
                    
                </div>
                <div id="tabs-2"></div>
                <div id="tabs-3"></div>
            </div>  
         </div>
         <div class="columnr width50 padding10" id="fbuilder">
             <div id="formheader"></div>
             <div id="fieldlist"></div>
             <div class="button" id="saveForm">Save Form</div>
         </div>
         <div class="clearer"></div>
         
     </div>   


          
        <p class="submit"><input type="submit" name="submit" id="submit" class="button-primary" value="Save Changes"  /></p>
        </form>
        <?php
        echo trailingslashit( THUNDER_REG_URL ).'js/jQuery.stringify.js';
        echo "<br>"; 
              
        $results = str_replace('"','&quot;',str_replace("\r","",str_replace("\n","",esc_attr($this->get_dboption('form_structure', '')))));
        //var_dump($results);
        echo THUNDER_FORM_STRUCTURE;
        //print_r($this->settings_api);
        //print_r(Thunder_Registration());

	}

			/**
		 * Returns the instance.
		 *		 
		 * @return object
		 */
		public static function get_instance() {

			// If the single instance hasn't been set, set it now.
			if ( null == self::$instance )
				self::$instance = new Main_Settings();

			return self::$instance;
		}
	
} // end class
	Main_Settings::get_instance();
endif;


 ?>