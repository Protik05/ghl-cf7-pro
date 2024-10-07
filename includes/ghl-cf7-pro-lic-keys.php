<?php
if ( ! defined( 'ABSPATH' ) ) exit;

// Check if the form is submitted
if (isset($_POST['ghl_lickey'])) {
    // Sanitize and save the form value
    $lickey = sanitize_text_field($_POST['global-lickey']);
    update_option('ghlcf7pro-lic-keys',  $lickey);
}

// Retrieve the saved value to display in the form
$Checklickey = get_option('ghlcf7pro-lic-keys', '');
?>

<div id="ghlcf7-options">
    <h1><?php esc_html_e('Set Your Extension License Key', 'ghl-cf7'); ?></h1>
    <hr />

    <form id="ghlcf7-settings-form" method="POST">

        <table class="form-table" role="presentation">
            <tbody>
                <tr>
                    <th scope="row">
                        <label><?php esc_html_e('License Keys: ', 'ghl-cf7'); ?></label>
                    </th>
                    <td>
                        <input type="text" name="global-lickey" value="<?php echo esc_attr($Checklickey); ?>">
                    </td>
                </tr>


            </tbody>
        </table>

        <div>
            <button class="ghl_cf7 button" type="submit" name="ghl_lickey">Update Settings</button>
        </div>
    </form>
</div>