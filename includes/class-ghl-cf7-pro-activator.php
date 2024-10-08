<?php

/**
 * Fired during plugin activation
 *
 * @link       https://www.ibsofts.com
 * @since      1.0.0
 *
 * @package    Ghl_Cf7_Pro
 * @subpackage Ghl_Cf7_Pro/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Ghl_Cf7_Pro
 * @subpackage Ghl_Cf7_Pro/includes
 * @author     iB Softs <ibsofts@gmail.com>
 */
class Ghl_Cf7_Pro_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
       global $wpdb;
		if (is_multisite()) {
			// Get all site IDs in the network
			$sites = get_sites();
			
			foreach ($sites as $site) {
				$site_id = $site->blog_id;
				$table_name = $wpdb->get_blog_prefix($site_id) . "ghlcf7pro_formSpecMapping";
				self::create_table_for_site($table_name);
				// Create the ghlcf7pro_GlobalMapping table for each site
				$mapping_table_name = $wpdb->get_blog_prefix($site_id) . "ghlcf7pro_GlobalMapping";
				self::create_table_for_site($mapping_table_name, true);
			}
		} else {
			// For non-Multisite installations, perform activation for the main site.
			$table_name = $wpdb->prefix . "ghlcf7pro_formSpecMapping";
			self::create_table_for_site($table_name);
			// // Create the ghlcf7pro_GlobalMapping table for the main site
			$mapping_table_name = $wpdb->prefix . "ghlcf7pro_GlobalMapping";
			self::create_table_for_site($mapping_table_name, true);
		}
	}
	public static function create_table_for_site($table_name, $isMappingTable = false){
		global $wpdb;
		$charset_collate = $wpdb->get_charset_collate();
		
		// Check if the table already exists
		$table_exists = $wpdb->get_var("SHOW TABLES LIKE '$table_name'");
		
		if ($table_exists !== $table_name) {
			// Create the table structure
			$sql = "CREATE TABLE $table_name (
				 `id` mediumint(9) NOT NULL AUTO_INCREMENT,
				 `Form_option_name` varchar(255) NOT NULL,
				 `Form_fields` JSON NOT NULL,
				 `Custom_fields` JSON NOT NULL,
				 `Opportunity_fields` JSON NOT NULL,
				 PRIMARY KEY (id)
			) $charset_collate;";
			
			// // If it's the mapping table, adjust the table structure
			if ($isMappingTable) {
				$sql = "CREATE TABLE $table_name (	
					 `Form_fields` JSON NOT NULL,
				     `Custom_fields` JSON NOT NULL,
				     `Opportunity_fields` JSON NOT NULL,
				) $charset_collate;";
			}
			
			$wpdb->query($wpdb->prepare($sql));
		}
	}

}