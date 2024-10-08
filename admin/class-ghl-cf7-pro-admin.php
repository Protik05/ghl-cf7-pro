<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.ibsofts.com
 * @since      1.0.0
 *
 * @package    Ghl_Cf7_Pro
 * @subpackage Ghl_Cf7_Pro/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Ghl_Cf7_Pro
 * @subpackage Ghl_Cf7_Pro/admin
 * @author     iB Softs <ibsofts@gmail.com>
 */
class Ghl_Cf7_Pro_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Ghl_Cf7_Pro_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Ghl_Cf7_Pro_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/ghl-cf7-pro-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Ghl_Cf7_Pro_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Ghl_Cf7_Pro_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/ghl-cf7-pro-admin.js', array( 'jquery' ), $this->version, false );

	}

	function ghlcf7pro_form_settings_tab($panels) {
        $panels['custom_tab'] = array(
            'title'    => __('GHL For CF7 Pro', 'ghl-cf7'),
            'callback' => array($this, 'ghlcf7pro_form_settings_tab_content'),
        );
        return $panels;
    }
	
	function ghlcf7pro_form_settings_tab_content($post) {
			// Retrieve values from the options table
			if($post->id){
				require plugin_dir_path(__FILE__) . 'partials/ghl-cf7-pro-form-tag.php';
			}
		}

	function ghlcf7pro_save_form_settings($contact_form) {
	// Retrieve values from the form
	$inputValue = isset($_POST['ghlcf7pro_tag']) ? sanitize_text_field($_POST['ghlcf7pro_tag']) : '';
 
	update_option( 'ghlcf7pro_tag_'.$contact_form->id, $inputValue);
	// Retrieve GHL-Form field mappings
    $ghl_fields_map = array();
    
    if (isset($_POST['ghl_field']) && isset($_POST['form_field'])) {
        $ghlFields = $_POST['ghl_field']; // Array of GHL fields
        $formFields = $_POST['form_field']; // Array of Form fields

        // Loop through the fields and create the mapping array
        foreach ($ghlFields as $index => $ghlField) {
            if (!empty($ghlField) && !empty($formFields[$index])) {
                $ghl_fields_map[] = array(
                    'key'   => sanitize_text_field($ghlField),  // GHL field
                    'value' => sanitize_text_field($formFields[$index])  // Form field
                );
            }
        }
    // update_option( 'ghl_fields_map_'.$contact_form->id, $ghl_fields_map );

    
    //save it inside our own table.
    global $wpdb;
    $table_name = $wpdb->prefix . "ghlcf7pro_formSpecMapping";
    $form_option_name = 'ghl_fields_map_' . $contact_form->id;
   // Convert the $ghl_fields_map array to JSON
    $form_fields_json = json_encode($ghl_fields_map);
    $existing_row = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE Form_option_name	= %s", $form_option_name));
    //  var_dump($existing_row);
    if ($existing_row) {
		// Update the existing record
		$result = $wpdb->update(
			$table_name,
			array(
				'Form_option_name' => $form_option_name,
				'Form_fields' => $form_fields_json,
                'Custom_fields'       => '{}', // Insert empty JSON object
                'Opportunity_fields'  => '{}'  // Insert empty JSON object
			),
			array('id' => $existing_row->id), 
			array('%s', '%s' ,'%s', '%s'),
			array('%d') 
		);
	}
    else{
        //insert
        $result_new = $wpdb->insert(
			$table_name,
			array(
				'Form_option_name' => $form_option_name,
				'Form_fields' => $form_fields_json,
                'Custom_fields'       => '{}', // Insert empty JSON object
                'Opportunity_fields'  => '{}'  // Insert empty JSON object
			),
			array('%s', '%s' ,'%s', '%s')
		);
    }
    }
    
        
}


public function connect_to_ghlcf7pro()
    {
        if (isset($_GET["one_connection"]) && $_GET["one_connection"] === "success") {
            if (isset($_GET['page']) && $_GET['page'] === "wpcf7" && isset($_GET['post'])) {
                $form_id = $_GET['post'];
                $ghl_data['ghlcf7pro_access_token_' . $form_id] = $_GET["atoken"];
                $ghl_data['ghlcf7pro_refresh_token_' . $form_id] = $_GET["rtoken"];
                $ghl_data['ghlcf7pro_locationId_' . $form_id] = $_GET["loctid"];
                $ghl_data['ghlcf7pro_client_id_' . $form_id] = $_GET["clntid"];
                $ghl_data['ghlcf7pro_client_secret_' . $form_id] = $_GET["clntsct"];
                $hours = 20 * 60 * 60;
                $ghl_data['ghlcf7pro_token_expire_' . $form_id] = time() + $hours;
    
				$location_name=ghlcf7pro_location_name($_GET["loctid"], $_GET["atoken"])->name;
				if($location_name){
					 $ghl_data['ghlcf7pro_location_name_'. $form_id] = $location_name;
				}

            } else {
				$ghl_data['ghlcf7pro_access_token'] = $_GET["atoken"];
                $ghl_data['ghlcf7pro_refresh_token'] = $_GET["rtoken"];
                $ghl_data['ghlcf7pro_locationId'] = $_GET["loctid"];
                $ghl_data['ghlcf7pro_client_id'] = $_GET["clntid"];
                $ghl_data['ghlcf7pro_client_secret'] = $_GET["clntsct"];
                $hours = 20 * 60 * 60;
                $ghl_data['ghlcf7pro_token_expire'] = time() + $hours;
				$location_name=ghlcf7pro_location_name($_GET["loctid"], $_GET["atoken"])->name;
				if($location_name){
					 $ghl_data['ghlcf7pro_location_name'] = $location_name;
				}
				update_option( 'ghlcf7pro_location_connected', 1 );
               
            }
			
		

            foreach ($ghl_data as $ghl_key => $ghl_value) {
                update_option($ghl_key, $ghl_value);
            }
        }
    }
	
// Method to refresh GHL access token after every 20 hours

    public function refresh_ghl_token_ghlcf7pro()
    {
		$posts = get_posts(array(
        'post_type'     => 'wpcf7_contact_form',
        'numberposts'   => -1
		));
		foreach ( $posts as $form ) {
           $current_time = time();
                $expire_time = get_option("ghlcf7pro_token_expire_" . $form->ID);
                if (!isset($expire_time) || empty($expire_time)) {
                    continue;
                }
                $client_id = get_option("ghlcf7pro_client_id_" . $form->ID);
                $client_secret = get_option("ghlcf7pro_client_secret_" . $form->ID);
                $refresh_token = get_option("ghlcf7pro_refresh_token_" . $form->ID);
                $body = [
                    "client_id" => $client_id,
                    "client_secret" => $client_secret,
                    "grant_type" => "refresh_token",
                    "refresh_token" => $refresh_token,
                    "user_type" => "Location"
                ];
                if ($client_id && $client_secret && $refresh_token) {
                    if ($current_time > $expire_time) {
                        $response = ghlcf7pro_get_access_token($body);
                        $response = json_decode($response, true);
                        if (isset($response['access_token'])) {
                            $hours = 20 * 60 * 60;
                            $ghl_token_expire = time() + $hours;
                            update_option("ghlcf7pro_access_token_" . $form->ID, $response["access_token"]);
                            update_option("ghlcf7pro_refresh_token_" . $form->ID, $response["refresh_token"]);
                            update_option("ghlcf7pro_token_expire_" . $form->ID, $ghl_token_expire);
                        } 
						// else {
                        //     $ghl_log = new GFGHLExProAddOn_Log();
                        //     $ghl_log->log_error('Refresh Token Error for Form ID ' . $form->ID . ': ' . $response['message']);
                        // }
                    }
                }
			
			
		}

		$current_time = time();
        $expire_time = get_option("ghlcf7pro_token_expire");
        $client_id = get_option("ghlcf7pro_client_id");
        $client_secret = get_option("ghlcf7pro_client_secret");
        $refresh_token = get_option("ghlcf7pro_refresh_token");

        $body = [
            "client_id" => $client_id,
            "client_secret" => $client_secret,
            "grant_type" => "refresh_token",
            "refresh_token" => $refresh_token,
            "user_type" => "Location"
        ];
        if ($client_id && $client_secret && $refresh_token) {
            if ($current_time > $expire_time) {
                // $ghl_api = new GFGHLExProAddOn_API();
                $response = ghlcf7pro_get_access_token($body);
                $response = json_decode($response, true);
                if (isset($response['access_token'])) {
                    $hours = 20 * 60 * 60;
                    $ghl_token_expire = time() + $hours;
                    update_option("ghlcf7pro_access_token", $response["access_token"]);
                    update_option("ghlcf7pro_refresh_token", $response["refresh_token"]);
                    update_option("ghlcf7pro_token_expire", $ghl_token_expire);
                } 
				// else {
                //     $ghl_log = new GFGHLExProAddOn_Log();
                //     $ghl_log->log_error('Refresh Token Error: ' . $response['message']);
                // }
            }
        }

    }

    //send data to ghl crm
    //cf7 after form submission hook.
   function ghlcf7pro_send_form_data_to_api($contact_form) {
		// Get the submitted form data
		$submission = WPCF7_Submission::get_instance();
		$form_id = $contact_form->id();
        $location_id = (!empty(get_option("ghlcf7pro_locationId_" . $form_id))) ? get_option("ghlcf7pro_locationId_" . $form_id) : get_option("ghlcf7pro_locationId"); 
		$tags= (!empty(get_option("ghlcf7pro_tag_" . $form_id))) ? get_option("ghlcf7pro_tag_" . $form_id) : get_option("ghlcf7pro-global-tag-names");
		
		if (!$submission) {
			return;
		}
		$posted_data = $submission->get_posted_data();
        $defined_fields=get_option('ghl_fields_map_'.$form_id);
		// Initialize the ghl_args array
        $ghl_args = [];

        if (!empty($defined_fields)) {
            // Loop through defined_fields and map values from posted_data
            foreach ($defined_fields as $field) {
                $key = $field['key'];      // e.g., 'firstName'
                $value = $field['value'];  // e.g., 'full_name'
                
                // Check if the value exists in the posted_data
                if (isset($posted_data[$value])) {
                    $ghl_args[$key] = $posted_data[$value];
                }
            }
        }
        //add the location_id inside the array.
         $ghl_args['locationId'] = $location_id;
         $ghl_args['tags']=$tags;
        // Output the result
        // echo '<pre>';
        // print_r($ghl_args);
        // echo '</pre>';
        // die('final');
            
		//$locationId = get_option( 'ghlcf7_locationId' );
		// if (!$posted_data) {
		// 	return;
		// }
		// //get te mapped value
		// $CheckNameFields = get_option('ghlcf7_name_fields', []);
		// $CheckEmailValue = get_option('ghlcf7_email_field');
		// $CheckPhoneValue = get_option('ghlcf7_phne_field');
		

			//dynamically fetch the field name.
			// $name_field_value = '';
			// $email_field_value = '';
			// $phone_field_value = '';
			// // Loop through the posted data from Contact Form 7
			// foreach ($posted_data as $key => $value) {
			// 	// Loop through the saved name fields
			// 	foreach ($CheckNameFields as $field) {
			// 		// Check if the key matches any of the saved name field values
			// 		if ($key === $field['value']) {
			// 			// Append the posted data value to the name field value
			// 			$name_field_value .= ' ' . $value;
			// 			break; // Exit the inner loop once a match is found
			// 		}
			// 	}
			// 	if (strcmp($key, $CheckEmailValue) == 0) {
			// 		$email_field_value = $value;
			// 	} 
			// 	elseif (strcmp($key, $CheckPhoneValue) == 0) {
			// 		$phone_field_value = $value;
			// 	}
			// }

       // Trim any leading or trailing whitespace
		// $name_field_value = trim($name_field_value);
        //if checkbox is checked.
		// if(get_option('ghlcf7_checkbox_'.$form_id)){
			//check the particular location tag and the global tags.
			// $tags = (!empty(get_option("ghlcf7_tag_".$form_id)) && get_option("ghlcf7_tag_".$form_id) !== false) ? get_option("ghlcf7_tag_".$form_id) : ((get_option("ghlcf7-global-tag-names") !== false) ? get_option("ghlcf7-global-tag-names") : "");
			// // Prepare the data to be sent to the API
			// $contact_data = array(
			// 	"locationId"  => $locationId,
			// 	'name' =>  $name_field_value,
			// 	'email' => $email_field_value,
			// 	'phone' => $phone_field_value,
			// 	'tags'  => $tags,
				
			// );
			
			//implement auth V2 GHL API
			$ghlcf7pro_access_token = (!empty(get_option("ghlcf7pro_access_token_" . $form_id))) ? get_option("ghlcf7pro_access_token_" . $form_id) : get_option("ghlcf7pro_access_token");
			$endpoint = "https://services.leadconnectorhq.com/contacts/upsert";
			$ghl_version = '2021-07-28';

			$request_args = array(
				'body' 		=> $ghl_args,
				'headers' 	=> array(
					'Authorization' => "Bearer {$ghlcf7pro_access_token}",
					'Version' 		=> $ghl_version
				),
			);

			$response = wp_remote_post( $endpoint, $request_args );
			$http_code = wp_remote_retrieve_response_code( $response );

			if ( 200 === $http_code || 201 === $http_code ) {

				$body = json_decode( wp_remote_retrieve_body( $response ) );
				$contact = $body->contact;

				return $contact;
			}
		// }
	}


}