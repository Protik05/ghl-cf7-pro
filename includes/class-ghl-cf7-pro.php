<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://www.ibsofts.com
 * @since      1.0.0
 *
 * @package    Ghl_Cf7_Pro
 * @subpackage Ghl_Cf7_Pro/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Ghl_Cf7_Pro
 * @subpackage Ghl_Cf7_Pro/includes
 * @author     iB Softs <ibsofts@gmail.com>
 */
class Ghl_Cf7_Pro {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Ghl_Cf7_Pro_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'GHL_CF7_PRO_VERSION' ) ) {
			$this->version = GHL_CF7_PRO_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'ghl-cf7-pro';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Ghl_Cf7_Pro_Loader. Orchestrates the hooks of the plugin.
	 * - Ghl_Cf7_Pro_i18n. Defines internationalization functionality.
	 * - Ghl_Cf7_Pro_Admin. Defines all hooks for the admin area.
	 * - Ghl_Cf7_Pro_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-ghl-cf7-pro-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-ghl-cf7-pro-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-ghl-cf7-pro-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-ghl-cf7-pro-public.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/settings-page.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'ghl_cf7pro_api/ghl-cf7pro-all-apis.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-ghl-cf7-pro-updater.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-ghl-cf7-pro-log.php';
		$this->loader = new Ghl_Cf7_Pro_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Ghl_Cf7_Pro_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Ghl_Cf7_Pro_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Ghl_Cf7_Pro_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		$this->loader->add_filter('wpcf7_editor_panels', $plugin_admin, 'ghlcf7pro_form_settings_tab');
		$this->loader->add_action('wpcf7_save_contact_form', $plugin_admin, 'ghlcf7pro_save_form_settings');
	
	    $this->loader->add_action('wp_loaded',$plugin_admin,'connect_to_ghlcf7pro');
	    $this->loader->add_action('wp_loaded',$plugin_admin,'refresh_ghl_token_ghlcf7pro');
		$this->loader->add_action('wpcf7_submit',$plugin_admin, 'ghlcf7pro_send_form_data_to_api');
        $this->loader->add_action( 'wp_ajax_ghlcf7pro_check_form_data', $plugin_admin, 'ghlcf7pro_check_form_data'); 
		
		//need to add the original server license file
		// $updater = new Ghl_Cf7_Pro_Updater();

        // $this->loader->add_filter('plugins_api', $updater, 'ghlcf7pro_info', 20, 3);
        // $this->loader->add_filter('site_transient_update_plugins', $updater,'ghlcf7pro_update');
        // $this->loader->add_action('upgrader_process_complete', $updater, 'ghlcf7pro_purge', 10, 2);
		// $this->loader->add_action('in_plugin_update_message-' . GHLCF7PRO_PATH, $updater, 'ghlcf7pro_update_message', 10, 2);
	
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Ghl_Cf7_Pro_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Ghl_Cf7_Pro_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}