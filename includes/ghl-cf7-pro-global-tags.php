<?php
if ( ! defined( 'ABSPATH' ) ) exit;

// Check if the form is submitted
if (isset($_POST['ghl_global'])) {
    // Sanitize and save the form value
    $global_tags = sanitize_text_field($_POST['global-tags']);
    update_option('ghlcf7pro-global-tag-names', $global_tags);
}

// Retrieve the saved value to display in the form
$CheckglobalTag = get_option('ghlcf7pro-global-tag-names', '');
// $globalcheck=get_option('ghlcf7pro-global-tag-names', '');
?>

<div id="ghlcf7-options">
    <h1><?php esc_html_e('Set Your Global Tags For Form', 'ghl-cf7'); ?></h1>
    <hr />

    <form id="ghlcf7-settings-form" method="POST">

        <table class="form-table" role="presentation">
            <tbody>
                <tr>
                    <th scope="row">
                        <label><?php esc_html_e('Global Tags: ', 'ghl-cf7'); ?></label>
                    </th>
                    <td>
                        <input type="text" name="global-tags" value="<?php echo esc_attr($CheckglobalTag); ?>">
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label><?php esc_html_e('Send global tags with form specific tags: ', 'ghl-cf7'); ?></label>
                    </th>
                    <td>
                        <input type="checkbox" name="glob_check">
                    </td>
                </tr>

            </tbody>
        </table>

        <div>
            <button class="ghl_cf7 button" type="submit" name="ghl_global">Update Settings</button>
        </div>
    </form>
</div>