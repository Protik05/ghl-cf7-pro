<?php
 if ( ! defined( 'ABSPATH' ) ) exit;
if ( ! class_exists( 'GHLCF7PRO_Settings_Page' ) ) {
	class GHLCF7PRO_Settings_Page {

        public function __construct() {
			add_action( 'admin_menu', array( $this, 'ghlcf7pro_create_menu_page' ) );
			//add_action( 'admin_post_ghlcf7_admin_settings', array( $this, 'ghlcf7_save_settings' ) );
			add_filter( 'plugin_action_links_' . GHLCF7PRO_PLUGIN_BASENAME , array( $this , 'ghlcf7pro_add_settings_link' ) );
		
		}

        public function ghlcf7pro_create_menu_page() {
	    
			$page_title 	= __( 'GHL for CF7 Pro', 'ghl-cf7' );
			$menu_title 	= __( 'GHL for CF7 Pro', 'ghl-cf7' );
			$capability 	= 'manage_options';
			$menu_slug 		= 'ib-ghlcf7-pro';
			$callback   	= array( $this, 'ghlcf7pro_page_content' );
			$icon_url   	= 'dashicons-admin-plugins';
			add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $callback, $icon_url );
	
		}	
		
        public function ghlcf7pro_page_content() {
            // check user capabilities to access the setting page.
			if ( ! current_user_can( 'manage_options' ) ) {
				return;
			}
			$default_tab = null;
			$tab = isset( $_GET['tab'] ) ? sanitize_text_field( $_GET['tab'] ) : $default_tab;
			?>

<div class="wrap main-con">
    <div class="ghl-header">
        <!-- Logo -->
        <div class="logo">
            <img src="<?php echo esc_url(plugins_url('images/GHLCF7.png', __DIR__)); ?>" alt="GHLCF7-Logo" />

        </div>

        <h1>GHL for Contact Form 7</h1>
    </div>
    <div class="ghl-container">

        <div class="ghl-content">
            <div class="ghl-tabs">
                <h2 class="nav-tab-wrapper-vertical">
                    <a href="?page=ib-ghlcf7"
                        class="nav-tab <?php if($tab===null):?>nav-tab-active<?php endif; ?>">Connect with GHL</a>
                </h2>
            </div>


            <div class="tab-content">
                <?php switch($tab) :
					default:
						require_once plugin_dir_path( __FILE__ )."/settings-form.php"; 
						break;
				    endswitch; ?>
            </div>
        </div>
    </div>
</div>

<?php	
	    		
		}
		

		public function ghlcf7pro_add_settings_link( $links ) {
	        $newlink = sprintf( "<a href='%s'>%s</a>" , admin_url( 'admin.php?page=ib-ghlcf7-pro' ) , __( 'Settings' , 'ghl-cf7-pro' ) );
	        $links[] = $newlink;
	        return $links;
	    }
    }
    new GHLCF7PRO_Settings_Page();
}