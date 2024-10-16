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
		// wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/ghl-cf7-pro-pipeline.js', array( 'jquery' ), $this->version, false );
        wp_localize_script($this->plugin_name, 'ghlcf7pro_form_data', array('ajaxurl' => admin_url( 'admin-ajax.php' )));
	}

	//send the value to ajax
function ghlcf7pro_check_form_data(){
      $form_id = sanitize_text_field($_POST['form_id']);
      if(!empty($form_id)){
		  $oppcheck = get_option('ghlcf7pro-opp-checkbox_'.$form_id, 'no');
          $saved_pipeline_stage = get_option('ghl_pipeline_stage_'.$form_id , '');
         wp_send_json_success(array("success" => true, 'pipeline_stages' =>$saved_pipeline_stage,'opp_check'=>$oppcheck));
        }
        wp_send_json_success(array("success" => false));
        die();
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
	//disconnect location operation for form specific part
	 if (isset($_POST['disconnect_form_loc'])) {
        delete_option('ghlcf7pro_access_token_'.$contact_form->id);
        delete_option('ghlcf7pro_refresh_token_'.$contact_form->id);
        delete_option('ghlcf7pro_locationId_'.$contact_form->id);
        delete_option('ghlcf7pro_client_id_'.$contact_form->id);
        delete_option('ghlcf7pro_client_secret_'.$contact_form->id);
        delete_option('ghlcf7pro_location_name_'.$contact_form->id);
        delete_option('ghlcf7pro_token_expire_'.$contact_form->id);      
    }	
	// Retrieve values from the form
	$inputValue = isset($_POST['ghlcf7pro_tag']) ? sanitize_text_field($_POST['ghlcf7pro_tag']) : '';
 
	update_option( 'ghlcf7pro_tag_'.$contact_form->id, $inputValue);
	// Retrieve GHL-Form field mappings
    $ghl_fields_map = array();
	// Retrive GHL Custom-Form fields mapping
	$ghl_custom_fields_map = array();
    // Retrive GHL Custom-Form fields mapping
	$ghl_opp_custom_fields_map = array();
	
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
	
		//mapping for custom fields
      if(isset($_POST['ghl_custom_field']) && isset($_POST['custom_form_field']))
	 {
		 $ghlCustomFields = $_POST['ghl_custom_field']; 
         $formCustomFields = $_POST['custom_form_field']; 
		  foreach ($ghlCustomFields as $index => $ghlCustomField) {
            if (!empty($ghlCustomField) && !empty($formCustomFields[$index])) {
                $ghl_custom_fields_map[] = array(
                    'key'   => sanitize_text_field($ghlCustomField),  // GHL field
                    'value' => sanitize_text_field($formCustomFields[$index])  // Form field
                );
            }
        }	
	 }
	  
	 //mapping for opp custom fields
      if(isset($_POST['ghl_opp_custom_field']) && isset($_POST['opp_custom_form_field']))
	 {
		 $ghlOppCustomFields = $_POST['ghl_opp_custom_field']; 
         $formOppCustomFields = $_POST['opp_custom_form_field']; 
		 
		  foreach ($ghlOppCustomFields as $index => $ghlOppCustomField) {
            if (!empty($ghlOppCustomField) && !empty($formOppCustomFields[$index])) {
                $ghl_opp_custom_fields_map[] = array(
                    'key'   => sanitize_text_field($ghlOppCustomField),  // GHL field
                    'value' => sanitize_text_field($formOppCustomFields[$index])  // Form field
                );
            }
        }	
	 }
     //save the pipeline name and stages
	  if(isset($_POST['pipeline']) && isset($_POST['pipeline_stage']))
	 {
		 $pipeline_name = $_POST['pipeline']; 
         $pipeline_stage = $_POST['pipeline_stage']; 
		 update_option( 'ghl_pipeline_name_'.$contact_form->id, $pipeline_name );
		 update_option( 'ghl_pipeline_stage_'.$contact_form->id, $pipeline_stage );
	 }
	
	 //save the opportunity checkbox value.
	$opp_checkbox = isset($_POST['opp_check']) ? 'yes' : 'no'; 
	update_option('ghlcf7pro-opp-checkbox_'.$contact_form->id, $opp_checkbox);
	
    //save it inside our own table.
    global $wpdb;
    $table_name = $wpdb->prefix . "ghlcf7pro_formSpecMapping";
    $form_option_name = 'ghl_fields_map_' . $contact_form->id;
   // Convert the $ghl_fields_map array to JSON
    $form_fields_json = json_encode($ghl_fields_map);
	$custom_fields_json = json_encode($ghl_custom_fields_map);
	$opp_custom_fields_json = json_encode($ghl_opp_custom_fields_map);
    $existing_row = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE Form_option_name	= %s", $form_option_name));
    //  var_dump($existing_row);
    if ($existing_row) {
		// Update the existing record
		$result = $wpdb->update(
			$table_name,
			array(
				'Form_option_name' => $form_option_name,
				'Form_fields' => $form_fields_json,
                'Custom_fields'       => $custom_fields_json, // Insert empty JSON object
                'Opportunity_fields'  => $opp_custom_fields_json// Insert empty JSON object
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
                'Custom_fields'       => $custom_fields_json, // Insert empty JSON object
                'Opportunity_fields'  => $opp_custom_fields_json  // Insert empty JSON object
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
                // update_option( 'ghlcf7pro_location_connected_'. $form_id, 1 );
            } 
			else {
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
				//  update_option( 'ghlcf7pro_location_connected', 1 );
               
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
						else {
                            $ghl_log = new GHLCF7PRO_Log();
                            $ghl_log->log_error('Refresh Token Error for Form ID ' . $form->ID . ': ' . $response['message']);
                        }
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
                } else {
                    $ghl_log = new GHLCF7PRO_Log();
                    $ghl_log->log_error('Refresh Token Error: ' . $response['message']);
                }
				
            }
        }

    }

    //send data to ghl crm
    //cf7 after form submission hook.
   function ghlcf7pro_send_form_data_to_api($contact_form) {
		// Get the submitted form data
		$ghl_log = new GHLCF7PRO_Log();
		global $wpdb;
		$table_name = $wpdb->prefix  . "ghlcf7pro_formSpecMapping";
		$submission = WPCF7_Submission::get_instance();
		$form_id = $contact_form->id();
		$form_name = $contact_form->name();
        $location_id = (!empty(get_option("ghlcf7pro_locationId_" . $form_id))) ? get_option("ghlcf7pro_locationId_" . $form_id) : get_option("ghlcf7pro_locationId"); 
		$ghlcf7pro_access_token = (!empty(get_option("ghlcf7pro_access_token_" . $form_id))) ? get_option("ghlcf7pro_access_token_" . $form_id) : get_option("ghlcf7pro_access_token");
		if (empty($ghlcf7pro_access_token) || empty($location_id)) {
            $ghl_log->log_error('Error: Ensure access token and location ID is configured.');
            return;
        }
		
		//$tags= (!empty(get_option("ghlcf7pro_tag_" . $form_id))) ? get_option("ghlcf7pro_tag_" . $form_id) : get_option("ghlcf7pro-global-tag-names");
		$tags= get_option("ghlcf7pro_tag_" . $form_id);
		$globalcheck = get_option('ghlcf7pro-global-checkbox', 'no');
		if($globalcheck==='yes' && !empty(get_option("ghlcf7pro-global-tag-names"))){
			$tags=get_option("ghlcf7pro-global-tag-names").','.$tags;
		}
		
		if (!$submission) {
			return;
		}
		$posted_data = $submission->get_posted_data();
		// Retrieve the JSON from the 'Form_fields' column where the 'Form_option_name' matches
		$form_fields_json = $wpdb->get_var(
			$wpdb->prepare(
				"SELECT Form_fields FROM $table_name WHERE Form_option_name = %s",
				'ghl_fields_map_' . $form_id
			)
		);
		//Custom fields value retrive from the db.
		$custom_fields_json = $wpdb->get_var(
			$wpdb->prepare(
				"SELECT Custom_fields FROM $table_name WHERE Form_option_name = %s",
				'ghl_fields_map_' . $form_id
			)
		);

		//Opp Custom fields value retrive from the db.
		$opp_custom_fields_json = $wpdb->get_var(
			$wpdb->prepare(
				"SELECT Opportunity_fields FROM $table_name WHERE Form_option_name = %s",
				'ghl_fields_map_' . $form_id
			)
		);

		// Check if data was found
		if ($form_fields_json) {
			// Decode the JSON into a PHP array
		$defined_fields = json_decode($form_fields_json, true);
		}
		
		// Check if data was found for custom fields
		if ($custom_fields_json) {
			// Decode the JSON into a PHP array
		$customfields_mapping = json_decode($custom_fields_json, true);
		}
        // Check if data was found for opp custom fields
		if ($opp_custom_fields_json) {
			// Decode the JSON into a PHP array
		 $opp_customfields_mapping = json_decode($opp_custom_fields_json, true);
		}
		// Initialize the ghl_args array
        $ghl_args = [];
		$ghl_opp_args = [];

		//prepare the mapped value of ghl fields with the form fields
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
		//prepare the mapped value of ghl custom fields with the form fields
		if (!empty($customfields_mapping)) {
            // Loop through defined_fields and map values from posted_data
            foreach ($customfields_mapping as $field) {
				$customField = [];
                $customField['id'] = $field['key'];      // e.g., 'firstName'
                // $customField['field_value'] = $field['value'];  // e.g., 'full_name'
                
                // Check if the value exists in the posted_data
                if (isset($posted_data[$field['value']])) {
                   $customField['field_value'] = $posted_data[$field['value']];
                }
				 $ghl_args['customFields'][] = $customField;
            }
        }
		//prepare the mapped value of ghl opp custom fields with the form fields
		if (!empty($opp_customfields_mapping)) {
            // Loop through defined_fields and map values from posted_data
            foreach ($opp_customfields_mapping as $field) {
				$oppcustomField = [];
                $oppcustomField['id'] = $field['key'];      // e.g., 'firstName'
                // $customField['field_value'] = $field['value'];  // e.g., 'full_name'
                
                // Check if the value exists in the posted_data
                if (isset($posted_data[$field['value']])) {
                   $oppcustomField['field_value'] = $posted_data[$field['value']];
                }
				 $ghl_opp_args['customFieldsopp'][] = $oppcustomField;
            }
        }
		
        //add the location_id inside the array.
         $ghl_args['locationId'] = $location_id;
		 $ghl_opp_args['locationId'] = $location_id;
         $ghl_args['tags']=$tags;
		 $contact_id=ghlcf7pro_create_contact($ghl_args,$ghlcf7pro_access_token,$form_id,$form_name);
		
		//to save the opp as a name
        $email_pipeline = !empty($ghl_args['email']) ? $ghl_args['email'] : "NULL";
        $saved_pipeline_name = get_option('ghl_pipeline_name_'.$form_id, ''); // Default empty if not set
        $saved_pipeline_stage = get_option('ghl_pipeline_stage_'.$form_id, ''); // Default empty if not set
		//if click the opp checkbox then send the value.
		$oppcheck = get_option('ghlcf7pro-opp-checkbox_'.$form_id, 'no');
		if($oppcheck==='yes'){
			$data=array(
            'pipelineId' => $saved_pipeline_name,
            'name' => $email_pipeline,
            'locationId' => $ghl_opp_args['locationId'],
            'pipelineStageId'=> $saved_pipeline_stage,
            'contactId' =>$contact_id,
            'status'=> "open",
            'customFields' =>isset($ghl_opp_args['customFieldsopp']) ? $ghl_opp_args['customFieldsopp'] : ''
            );
		//create opporrtunity
		$opp=ghlcf7pro_create_opportunity($data,$ghlcf7pro_access_token);
		}
	}

}