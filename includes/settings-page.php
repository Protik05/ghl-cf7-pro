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
			$menu_slug 		= 'ib-ghlcf7pro';
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

<div class="wrap main-con-ghlcf7pro">
    <div class="ghlcf7pro-header">
        <!-- logo-ghlcf7pro-->
        <div class="logo-ghlcf7pro">
            <img src="<?php echo esc_url(plugins_url('images/GHLCF7.png', __DIR__)); ?>" alt="GHLCF7-Logo" />

        </div>

        <h1>GHL for Contact Form 7 Pro </h1>
    </div>
    <div class="ghlcf7pro-container">

        <div class="ghlcf7pro-content">
            <div class="ghlcf7pro-tabs">
                <h2 class="nav-tab-wrapper-vertical">
                    <a href="?page=ib-ghlcf7pro&tab=lickey"
                        class="nav-tab <?php if($tab==='lickey'):?>nav-tab-active<?php endif; ?>">License
                        Key</a>
                    <a href="?page=ib-ghlcf7pro"
                        class="nav-tab <?php if($tab===null):?>nav-tab-active<?php endif; ?>">Connect with GHL</a>
                    <a href="?page=ib-ghlcf7pro&tab=global"
                        class="nav-tab <?php if($tab==='global'):?>nav-tab-active<?php endif; ?>">Global Tags</a>
                    <a href="?page=ib-ghlcf7pro&tab=support"
                        class="nav-tab <?php if($tab==='support'):?>nav-tab-active<?php endif; ?>">Help/Support</a>
                    <a href="?page=ib-ghlcf7pro&tab=log"
                        class="nav-tab <?php if($tab==='log'):?>nav-tab-active<?php endif; ?>">Log</a>
                </h2>
            </div>


            <div class="tab-content-ghlcf7pro">
                <?php switch($tab) :
					case 'lickey':
						require_once plugin_dir_path( __FILE__ )."/ghl-cf7-pro-lic-keys.php";
						break;	
					case 'global':
						require_once plugin_dir_path( __FILE__ )."/ghl-cf7-pro-global-tags.php";
						break;
					case 'support':
						require_once plugin_dir_path( __FILE__ )."/help-page.php";
					break;
					case 'log':
						require_once plugin_dir_path( __FILE__ )."/ghl-cf7-pro-log-viewer.php";
					break;
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
	        $newlink = sprintf( "<a href='%s'>%s</a>" , admin_url( 'admin.php?page=ib-ghlcf7pro' ) , __( 'Settings' , 'ghl-cf7-pro' ) );
	        $links[] = $newlink;
	        return $links;
	    }
    }
    new GHLCF7PRO_Settings_Page();
}