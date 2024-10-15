<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.ibsofts.com
 * @since             1.0.0
 * @package           Ghl_Cf7_Pro
 *
 * @wordpress-plugin
 * Plugin Name:       Go high level extension for Contact Form 7 pro
 * Plugin URI:        https://www.ibsofts.com/plugins/ghl-cf7-pro
 * Description:       This plugin send Contact Form 7 Data to Go High Level on form submission with other pro features.
 * Version:           1.0.0
 * Author:            iB Softs
 * Author URI:        https://www.ibsofts.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       ghl-cf7-pro
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'GHL_CF7_PRO_VERSION', '1.0.0' );
define( 'GHLCF7PRO_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
define( 'GHLCF7PRO_LOCATION_CONNECTED', false );
define( 'GHLCF7PRO_PATH', plugin_basename( __FILE__ ));
/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-ghl-cf7-pro-activator.php
 */
function activate_ghl_cf7_pro() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-ghl-cf7-pro-activator.php';
	Ghl_Cf7_Pro_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-ghl-cf7-pro-deactivator.php
 */
function deactivate_ghl_cf7_pro() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-ghl-cf7-pro-deactivator.php';
	Ghl_Cf7_Pro_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_ghl_cf7_pro' );
register_deactivation_hook( __FILE__, 'deactivate_ghl_cf7_pro' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-ghl-cf7-pro.php';

/**
 * Inclusion of definitions.php
 */
require_once plugin_dir_path( __FILE__ ) . 'definitions.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_ghl_cf7_pro() {

	$plugin = new Ghl_Cf7_Pro();
	$plugin->run();

}
run_ghl_cf7_pro();