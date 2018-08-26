<?php 
class Thunder_Shortcodes extends Thunder_Db {

	public static $name = 'register';

	private static $instance = null;

	public function __construct() {
		// Register shortcode on 'init'.
		add_action( 'init', array( $this, 'register_shortcode' ) );
	}

	public function register_shortcode() {
		$tag = apply_filters( self::$name . '_shortcode_name', self::$name );
		add_shortcode( 'thunder_' . $tag, array( $this, 'content_shortcode' ) );
	}

	public function content_shortcode()
	{
		$d = '<p>'."LOrem ipsum dolor".'</p>';
		//return $d;
		/*function cp_contactformtoemail_pform_doValidate_1(form) {
                document.cp_contactformtoemail_pform_1.cp_ref_page.value = document.location;
                $dexQuery = jQuery.noConflict();            
                if (document.cp_contactformtoemail_pform_1.hdcaptcha_cp_contactformtoemail_post.value == '') 
                	{ setTimeout( "cp_contactformtoemail_cerror_1()", 100); return false; }
                var result = $dexQuery.ajax({
                 type: "GET", 
                 url: "/?ps=_1&cp_contactformtoemail_pform_process=2&inAdmin=1&ps=_1&hdcaptcha_cp_contactformtoemail_post="+document.cp_contactformtoemail_pform_1.hdcaptcha_cp_contactformtoemail_post.value, async: false }).responseText;
                if (result.indexOf("captchafailed") != -1) {
                    $dexQuery("#captchaimg_1").attr('src', $dexQuery("#captchaimg_1").attr('src')+'&'+Date());
                    setTimeout( "cp_contactformtoemail_cerror_1()", 100);
                    return false;
                } else             {
                    document.getElementById("form_structure_1").value = '';    
                    return true;
                }  */  
                ob_start();                
        if ( ! is_user_logged_in() ) {
        	
            require_once dirname( __FILE__ ) .'/login.php';
        	?>
        	
        	<script type='text/javascript'>
			/* <![CDATA[ */
			var cp_contactformtoemail_fbuilder_config_1 = {"obj":"{\"pub\":true,\"identifier\":\"_1\",\"messages\": {\n            \t                \t\"required\": \"This field is required.\",\n            \t                \t\"email\": \"Please enter a valid email address.\",\n            \t                \t\"datemmddyyyy\": \"Please enter a valid date with this format(mm\/dd\/yyyy)\",\n            \t                \t\"dateddmmyyyy\": \"Please enter a valid date with this format(dd\/mm\/yyyy)\",\n            \t                \t\"number\": \"Please enter a valid number.\",\n            \t                \t\"digits\": \"Please enter only digits.\",\n            \t                \t\"max\": \"Please enter a value less than or equal to %0%.\",\n            \t                \t\"min\": \"Please enter a value greater than or equal to %0%.\",\n    \t                    \t    \"previous\": \"Previous\",\n    \t                    \t    \"next\": \"Next\"\n            \t                }}"};
			/* ]]> */
			</script>
			
        	<?php
           /* if ( isset( $_GET['enter'] ) ) {
            	if ( $_GET['enter'] == 'failed' ) {
            		echo '<div id="message" class="thunder-notification error"><p>' . __( "Wrong credentials", 'thunder-registration' ) . '</p></div>';
            	}
            }*/
        }        
    return ob_get_clean();

	}


		/**
	 * Returns the instance.
	 *
	 * @since  1.0.0
	 * @return object
	 */
	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance )
			self::$instance = new self;

		return self::$instance;
	}
}
Thunder_Shortcodes::get_instance();
 ?>