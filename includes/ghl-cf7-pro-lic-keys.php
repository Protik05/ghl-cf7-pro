<?php
if ( ! defined( 'ABSPATH' ) ) exit;

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
if (isset($_POST['ghlcf7pro_lickey'])) {
    // Sanitize and save the form value
    $lickey = sanitize_text_field($_POST['ghlcf7pro_lickey']);
    update_option('ghlcf7pro-lic-keys',  $lickey);
}
}

// Retrieve the saved value to display in the form
$Checklickey = get_option('ghlcf7pro-lic-keys', '');
?>

<div id="ghlcf7-options">
    <h1><?php esc_html_e('Set Your Extension License Key', 'ghl-cf7pro'); ?></h1>
    <hr />
    <div class="license_key_container">

        <h2>Enter License Key </h2>
        <form id="ghl-license-key-form1" method="post" action="">
            <input type="text" id="license-key-input" name="ghlcf7pro_lickey" required
                value="<?php echo esc_attr($Checklickey); ?>">
            <input type="submit" value="Activate" class="lic button">
        </form>
    </div>
</div>