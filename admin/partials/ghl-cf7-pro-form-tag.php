<?php
// $checkboxValue = get_option('ghlcf7_checkbox_' . $post->id, 0);
$inputValue = get_option('ghlcf7pro_tag_' . $post->id, '');
$settings_url = (isset($_GET['post'])) ? urlencode(admin_url('admin.php?page=wpcf7&post=' . $_GET['post'].'&action=edit')) : urlencode(admin_url('admin.php?page=ib-ghlcf7pro'));
$server_url = "https://server.ibsofts.com/one-extension/market_app.php";
$connect_url= add_query_arg(array('redirect_page' => $settings_url), $server_url);
$loc_id=get_option('ghlcf7pro_locationId_' . $post->id);
$loc_name=(!empty(get_option("ghlcf7pro_location_name_" . $post->id))) ? get_option("ghlcf7pro_location_name_" . $post->id) : get_option("ghlcf7pro_location_name");


$oppcheck = get_option('ghlcf7pro-opp-checkbox', 'no');

?>
<div class="ghlcf7pro-tab-content">
    <?php
    
    //$saved_mapping = get_option('ghl_fields_map_' . $post->id , []);
    //retrive the value from our table.
    global $wpdb;
    $form_id=$post->id;
    $table_name = $wpdb->prefix  . "ghlcf7pro_formSpecMapping";

    // Retrieve the JSON from the 'Form_fields' column where the 'Form_option_name' matches
    $form_fields_json = $wpdb->get_var(
        $wpdb->prepare(
            "SELECT Form_fields FROM $table_name WHERE Form_option_name = %s",
            'ghl_fields_map_' . $post->id
        )
    );
    //Custom fields value retrive from the db.
    $custom_fields_json = $wpdb->get_var(
        $wpdb->prepare(
            "SELECT Custom_fields FROM $table_name WHERE Form_option_name = %s",
            'ghl_fields_map_' . $post->id
        )
    );
  //Opp Custom fields value retrive from the db.
    $opp_custom_fields_json = $wpdb->get_var(
        $wpdb->prepare(
            "SELECT Opportunity_fields FROM $table_name WHERE Form_option_name = %s",
            'ghl_fields_map_' . $post->id
        )
    );
    // Check if data was found
    if ($form_fields_json) {
        // Decode the JSON into a PHP array
       $saved_mapping = json_decode($form_fields_json, true);
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
    
   
    // Retrieve custom fields from API
    
    $location_id = (!empty(get_option("ghlcf7pro_locationId_" . $form_id))) ? get_option("ghlcf7pro_locationId_" . $form_id) : get_option("ghlcf7pro_locationId");
    $ghlcf7pro_access_token = (!empty(get_option("ghlcf7pro_access_token_" . $form_id))) ? get_option("ghlcf7pro_access_token_" . $form_id) : get_option("ghlcf7pro_access_token");
    $custom_fields = ghlcf7pro_get_custom_fields($location_id, $ghlcf7pro_access_token);
    $opp_custom_fields=ghlcf7pro_get_opp_custom_fields($location_id, $ghlcf7pro_access_token);
    
    // Example GHL fields (replace with actual GHL fields)
    $ghl_fields = [
    'firstName' => 'First Name',
    'lastName' => 'Last Name',
    'name' => 'Name',
    'email' => 'Email',
    'phone' => 'Phone',
    'address1' => 'Address',
    'city' => 'City',
    'state' => 'State',
    'postalCode' => 'Postal Code',
    'country' => 'Country',
    'companyName' => 'Business Name',
    'website' => 'Website',
    'timezone' => 'Time Zone'
    ];

    ?>
    <div class="mapping-fields-container">
        <p>
            <strong>GHL defined fields mapping with Form fields</strong>
        </p>
        <?php
        // Loop through saved mapping and render the fields
        if (!empty($saved_mapping)) {
            foreach ($saved_mapping as $map) {
                ?>
        <div class="mapping-fields-row">
            <div class="field-group">
                <label>GHL Fields:</label>
                <select name="ghl_field[]">
                    <option value="">Select GHL Field</option>
                    <?php foreach ($ghl_fields as $key => $label) { ?>
                    <option value="<?php echo esc_attr($key); ?>" <?php selected($map['key'], $key); ?>>
                        <?php echo esc_html($label); ?>
                    </option>
                    <?php } ?>
                </select>
            </div>
            <div class="field-group">
                <label>Form Fields:</label>

                <input type="text" name="form_field[]" value="<?php echo esc_attr($map['value']); ?>" />
            </div>
            <button type="button" class="add-row">+</button>
            <button type="button" class="remove-row">-</button>
        </div>
        <?php
            }
        } else {
            // If no saved mapping, show a default empty row
            ?>
        <div class="mapping-fields-row">
            <div class="field-group">
                <label>GHL Fields:</label>
                <select name="ghl_field[]">
                    <option value="">Select GHL Field</option>
                    <?php foreach ($ghl_fields as $key => $label) { ?>
                    <option value="<?php echo esc_attr($key); ?>">
                        <?php echo esc_html($label); ?>
                    </option>
                    <?php } ?>
                </select>
            </div>
            <div class="field-group">
                <label>Form Fields:</label>
                <input type="text" name="form_field[]" value="" />
            </div>
            <button type="button" class="add-row">+</button>
            <button type="button" class="remove-row">-</button>

        </div>
        <?php
        }
        
        ?>
    </div>
    <!-- custom fields container -->
    <div class="mapping-custom-fields-container">
        <p>
            <strong>GHL Custom fields mapping with Form fields</strong>
        </p>
        <?php
        //for custom fields and form fields mapping.
        if (!empty($customfields_mapping)) {
            foreach ($customfields_mapping as $map) {
                ?>
        <div class="custom-mapping-fields-row">
            <div class="custom-field-group">
                <label>GHL Custom Fields:</label>
                <select name="ghl_custom_field[]">
                    <option value="">Select GHL Custom Field</option>
                    <?php foreach ($custom_fields as $key => $label) { ?>
                    <option value="<?php echo esc_attr($label->id); ?>"
                        <?php selected($map['key'], $label->id); // Check if the key matches and mark it as selected ?>>
                        <?php echo esc_html($label->name); // Display the name from the custom fields ?>
                    </option>
                    <?php } ?>
                </select>
            </div>
            <div class="field-group">
                <label>Form Fields:</label>
                <input type="text" name="custom_form_field[]" value="<?php echo esc_attr($map['value']); ?>" />
            </div>
            <button type="button" class="add-custom-row">+</button>
            <button type="button" class="remove-custom-row">-</button>
        </div>
        <?php
            }
        } else {
            // If no saved mapping, show a default empty row
            ?>
        <div class="custom-mapping-fields-row">
            <div class="custom-field-group">
                <label>GHL Custom Fields:</label>
                <select name="ghl_custom_field[]">
                    <option value="">Select GHL Custom Field</option>
                    <?php foreach ($custom_fields as $custom_field) { ?>
                    <option value="<?php echo esc_attr($custom_field->id); ?>">
                        <?php echo esc_html($custom_field->name); ?>
                    </option>
                    <?php } ?>
                </select>
            </div>
            <div class="field-group">
                <label>Form Fields:</label>
                <input type="text" name="custom_form_field[]" value="" />
            </div>
            <button type="button" class="add-custom-row">+</button>
            <button type="button" class="remove-custom-row">-</button>

        </div>
        <?php
        }
        ?>
    </div>
    <div class="hide_opp">
        <p class="hide_opp_text">
            <strong>Want to send Opportunity Custom Fields value in GHL?</strong>
        </p>
        <input type="checkbox" name="opp_check" value="yes" <?php checked($oppcheck, 'yes'); ?>>
    </div>
    <!-- opportunity custom fields container -->
    <div class="ghlcf7pro_opp_hide">
        <div class="mapping-opp-custom-fields-container">
            <p>
                <strong>GHL Opportunity custom fields mapping with Form fields</strong>
            </p>
            <?php
        //for custom fields and form fields mapping.
        if (!empty($opp_customfields_mapping)) {
            foreach ($opp_customfields_mapping as $map) {
                ?>
            <div class="opp-custom-mapping-fields-row">
                <div class="opp-custom-field-group">
                    <label>GHL Opportunity Custom Fields:</label>
                    <select name="ghl_opp_custom_field[]">
                        <option value="">Select a Field</option>
                        <?php foreach ($opp_custom_fields as $key => $label) { ?>
                        <option value="<?php echo esc_attr($label->id); ?>"
                            <?php selected($map['key'], $label->id); // Check if the key matches and mark it as selected ?>>
                            <?php echo esc_html($label->name); // Display the name from the custom fields ?>
                        </option>
                        <?php } ?>
                    </select>
                </div>
                <div class="field-group">
                    <label>Form Fields:</label>
                    <input type="text" name="opp_custom_form_field[]" value="<?php echo esc_attr($map['value']); ?>" />
                </div>
                <button type="button" class="add-opp-custom-row">+</button>
                <button type="button" class="remove-opp-custom-row">-</button>
            </div>
            <?php
            }
        } else {
            // If no saved mapping, show a default empty row
            ?>
            <div class="opp-custom-mapping-fields-row">
                <div class="opp-custom-field-group">
                    <label>GHL Opportunity Custom Fields:</label>
                    <select name="ghl_opp_custom_field[]">
                        <option value="">Select a Field</option>
                        <?php foreach ($opp_custom_fields as $custom_field) { ?>
                        <option value="<?php echo esc_attr($custom_field->id); ?>">
                            <?php echo esc_html($custom_field->name); ?>
                        </option>
                        <?php } ?>
                    </select>
                </div>
                <div class="field-group">
                    <label>Form Fields:</label>
                    <input type="text" name="opp_custom_form_field[]" value="" />
                </div>
                <button type="button" class="add-opp-custom-row">+</button>
                <button type="button" class="remove-opp-custom-row">-</button>

            </div>
            <?php
        }
        ?>
        </div>
        <?php
    // Simulate API response
    $get_stages = ghlcf7pro_get_pipeline($location_id, $ghlcf7pro_access_token);
    $saved_pipeline_name = get_option('ghl_pipeline_name_'.$post->id, ''); // Default empty if not set
    $saved_pipeline_stage = get_option('ghl_pipeline_stage_'.$post->id, ''); // Default empty if not set
    $connected_location = get_option('ghlcf7pro_location_connected_'.$post->id, '');
    $connected_global_location = get_option('ghlcf7pro_location_connected', '');
   
    // If API response contains data
    if (!empty($get_stages)) {
    ?>
        <div class="pipeline-row">
            <!-- Pipeline Name Dropdown -->
            <div class="pipeline-name">
                <label for="pipeline">Select Pipeline Name:</label>
                <select id="pipeline" name="pipeline">
                    <option value="">Select Pipeline</option>
                    <?php foreach ($get_stages as $pipeline) { ?>
                    <option value="<?php echo esc_attr($pipeline->id); ?>"
                        data-stages='<?php echo json_encode($pipeline->stages); ?>'
                        <?php selected($pipeline->id, $saved_pipeline_name); ?>>
                        <?php echo esc_html($pipeline->name); ?>
                    </option>
                    <?php } ?>
                </select>
            </div>
            <div class="pipeline-stage">
                <!-- Pipeline Stages Dropdown -->
                <label for="pipeline-stage" id="pipeline-stage-label">Select Pipeline Stage:</label>
                <select id="pipeline-stage" name="pipeline_stage">
                    <option value="">Select Stage</option>
                    <!-- Options will be populated dynamically using JavaScript -->
                </select>
            </div>
        </div>
    </div>
    <?php
} else {
    echo "No pipelines available.";
}
?>
    <div class="ghlcf7_setting_tag">
        <label for="ghlcf7pro_tag">Enter Tags to Send:</label>
        <input type="text" id="ghlcf7pro_tag" name="ghlcf7pro_tag" value="<?php echo esc_attr($inputValue); ?>">
    </div>
    <?php if ($connected_location) { ?>
    <div class="form-spe">
        <label for="ghlcf7_tag">Connect GHL Subaccount: </label>
        <a class="ghl_cf7pro_btn button" href="<?php echo esc_url($connect_url); ?>">Connect GHL
            Subaccount</a>
        <p>Connected Location Name: <?php echo esc_html($loc_name); ?></p>
    </div>
    <?php } else { ?>
    <div class="form-spe">
        <label for="ghlcf7_tag">Connect GHL Subaccount: </label>
        <a class="ghl_cf7pro_btn button" href="<?php echo esc_url($connect_url); ?>">Connect GHL
            Subaccount</a>
        <?php if($connected_global_location){?>
        <p>
            This connection will override the global connection. Currently <?php echo esc_html($loc_name); ?> is
            connected.
        </p>
        <?php }?>
    </div>
    <?php } ?>
</div>