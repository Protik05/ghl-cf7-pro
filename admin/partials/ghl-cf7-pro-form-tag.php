<?php
// $checkboxValue = get_option('ghlcf7_checkbox_' . $post->id, 0);
$inputValue = get_option('ghlcf7pro_tag_' . $post->id, '');
$settings_url = (isset($_GET['post'])) ? urlencode(admin_url('admin.php?page=wpcf7&post=' . $_GET['post'].'&action=edit')) : urlencode(admin_url('admin.php?page=ib-ghlcf7pro'));
$server_url = "https://server.ibsofts.com/one-extension/market_app.php";
$connect_url= add_query_arg(array('redirect_page' => $settings_url), $server_url);
$loc_id=get_option('ghlcf7pro_locationId_' . $post->id);
$loc_name=get_option('ghlcf7pro_location_name_' . $post->id);

?>
<div class="ghlcf7-tab-content">
    <?php
    
    //$saved_mapping = get_option('ghl_fields_map_' . $post->id , []);
    //retrive the value from our table.
    global $wpdb;
    $table_name = $wpdb->prefix  . "ghlcf7pro_formSpecMapping";

    // Retrieve the JSON from the 'Form_fields' column where the 'Form_option_name' matches
    $form_fields_json = $wpdb->get_var(
        $wpdb->prepare(
            "SELECT Form_fields FROM $table_name WHERE Form_option_name = %s",
            'ghl_fields_map_' . $post->id
        )
    );

    // Check if data was found
    if ($form_fields_json) {
        // Decode the JSON into a PHP array
       $saved_mapping = json_decode($form_fields_json, true);
    }
   
    // Retrieve custom fields from API
    $form_id=$post->id;
    $location_id = (!empty(get_option("ghlcf7pro_locationId_" . $form_id))) ? get_option("ghlcf7pro_locationId_" . $form_id) : get_option("ghlcf7pro_locationId");
    $ghlcf7pro_access_token = (!empty(get_option("ghlcf7pro_access_token_" . $form_id))) ? get_option("ghlcf7pro_access_token_" . $form_id) : get_option("ghlcf7pro_access_token");
    $custom_fields = ghlcf7pro_get_custom_fields($location_id, $ghlcf7pro_access_token);
  

    // Example form fields (replace with your actual form fields)
    $form_fields = [
    'full_name' => 'Full Name',
    'email' => 'Email',
    'phone' => 'Phone',
    'address' => 'Address'
    ];

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
                <select name="form_field[]">
                    <option value="">Select Form Field</option>
                    <?php foreach ($form_fields as $key => $label) { ?>
                    <option value="<?php echo esc_attr($key); ?>" <?php selected($map['value'], $key); ?>>
                        <?php echo esc_html($label); ?>
                    </option>
                    <?php } ?>
                </select>
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
                <select name="form_field[]">
                    <option value="">Select Form Field</option>
                    <?php foreach ($form_fields as $key => $label) { ?>
                    <option value="<?php echo esc_attr($key); ?>">
                        <?php echo esc_html($label); ?>
                    </option>
                    <?php } ?>
                </select>
            </div>
            <button type="button" class="add-row">+</button>
            <button type="button" class="remove-row">-</button>

        </div>
        <?php
        }
        ?>
        <!-- Custom Fields to Form Fields Mapping -->
        <h3>Custom Fields to Form Fields Mapping</h3>
        <div class="custom-fields-mapping">
            <?php
            
            if (!empty($custom_fields)) {
               
                    ?>
            <div class="custom-fields-mapping">
                <div class="custom-fields-row" style="display: flex; align-items: center; gap: 20px;margin-top: 10px;">
                    <label>Custom Fields:</label>
                    <select name="custom_field[]">
                        <option value="">Select Custom Field</option>
                        <?php foreach ($custom_fields as $custom_field) { ?>
                        <option value="<?php echo esc_attr($custom_field->id); ?>">
                            <?php echo esc_html($custom_field->name); ?>
                        </option>
                        <?php } ?>
                    </select>

                    <label>Form Fields:</label>
                    <select name="form_field[]">
                        <option value="">Select Form Field</option>
                        <?php foreach ($form_fields as $key => $label) { ?>
                        <option value="<?php echo esc_attr($key); ?>">
                            <?php echo esc_html($label); ?>
                        </option>
                        <?php } ?>
                    </select>

                    <button type="button" class="add-custom-row">+</button>
                    <button type="button" class="remove-custom-row">-</button>
                </div>
                <?php
            } else {
                echo '<p>No custom fields available.</p>';
            }
            ?>
            </div>

        </div>

        <div class="ghlcf7_setting_tag">
            <label for="ghlcf7pro_tag">Enter Tags to Send:</label>
            <input type="text" id="ghlcf7pro_tag" name="ghlcf7pro_tag" value="<?php echo esc_attr($inputValue); ?>">
        </div>

        <div class="form-spe">
            <label for="ghlcf7_tag">Connect GHL Subaccount: </label>
            <a class="ghl_connect button" href="<?php echo esc_url($connect_url); ?>">Connect Another
                Subaccount</a>
            <p>Location ID: <?php echo esc_html($loc_id); ?></p>
            <p>Location Name: <?php echo esc_html($loc_name); ?></p>
        </div>
    </div>