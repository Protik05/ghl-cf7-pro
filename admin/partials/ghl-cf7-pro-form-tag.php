<?php
// $checkboxValue = get_option('ghlcf7_checkbox_' . $post->id, 0);
$inputValue = get_option('ghlcf7_tag_' . $post->id, '');
$settings_url = (isset($_GET['post'])) ? urlencode(admin_url('admin.php?page=wpcf7&post=' . $_GET['post'].'&action=edit')) : urlencode(admin_url('admin.php?page=ib-ghlcf7pro'));
$server_url = "https://server.ibsofts.com/one-extension/market_app.php";
$connect_url= add_query_arg(array('redirect_page' => $settings_url), $server_url);
$loc_id=get_option('ghlcf7pro_locationId_' . $post->id);
$loc_name=get_option('ghlcf7pro_location_name_' . $post->id);

?>
<div class="ghlcf7-tab-content">
    <?php
    
    $saved_mapping = get_option('ghl_fields_map_' . $post->id , []);

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
    </div>

    <div class="ghlcf7_setting_tag">
        <label for="ghlcf7_tag">Enter Tags to Send:</label>
        <input type="text" id="ghlcf7_tag" name="ghlcf7_tag" value="<?php echo esc_attr($inputValue); ?>">
    </div>

    <div class="form-spe">
        <label for="ghlcf7_tag">Connect GHL Subaccount: </label>
        <a class="ghl_connect button" href="<?php echo esc_url($connect_url); ?>">Connect Another
            Subaccount</a>
        <p>Location ID: <?php echo esc_html($loc_id); ?></p>
        <p>Location Name: <?php echo esc_html($loc_name); ?></p>
    </div>
</div>