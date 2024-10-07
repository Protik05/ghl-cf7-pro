<?php
	 if ( ! defined( 'ABSPATH' ) ) exit;
	// if ( isset( $_GET['one_connection'] ) && sanitize_text_field($_GET['one_connection']) === 'success' ) {
	// 	$ghlcf7pro_access_token 	= sanitize_text_field( $_GET['atoken'] );
	// 	$ghlcf7pro_refresh_token 	= sanitize_text_field( $_GET['rtoken'] );
	// 	$ghlcf7pro_locationId 	    = sanitize_text_field( $_GET['loctid'] );
	// 	$ghlcf7pro_client_id 		= sanitize_text_field( $_GET['clntid'] );
	// 	$ghlcf7pro_client_secret 	= sanitize_text_field( $_GET['clntsct'] );
	// 	// Save data
	//     update_option( 'ghlcf7pro_access_token', $ghlcf7pro_access_token );
	//     update_option( 'ghlcf7pro_refresh_token', $ghlcf7pro_refresh_token );
	//     update_option( 'ghlcf7pro_locationId', $ghlcf7pro_locationId );
	//     update_option( 'ghlcf7pro_client_id', $ghlcf7pro_client_id );
	//     update_option( 'ghlcf7pro_client_secret', $ghlcf7pro_client_secret );
	//     update_option( 'ghlcf7pro_location_connected', 1 );
    //     update_option( 'ghlcf7pro_loc_name', ghlcf7pro_location_name($ghlcf7pro_locationId)->name);
	//     //  (delete if any old transient  exists )
	//     delete_transient('ghlcf7pro_location_tags');
	//     delete_transient('ghlcf7pro_location_wokflow');

	//     wp_redirect('admin.php?page=ib-ghlcf7pro');
	// }
    
	$ghlcf7pro_location_connected	= get_option( 'ghlcf7pro_location_connected', GHLCF7PRO_LOCATION_CONNECTED );
	$ghlcf7pro_client_id 			= get_option( 'ghlcf7pro_client_id' );
	$ghlcf7pro_client_secret 		= get_option( 'ghlcf7pro_client_secret' );
	$ghlcf7pro_locationId 		    = get_option( 'ghlcf7pro_locationId' );
	$ghlcf7pro_locationName 		= get_option( 'ghlcf7pro_location_name' );
	$redirect_page 				    = get_site_url(null, '/wp-admin/admin.php?page=ib-ghlcf7pro');
	$redirect_uri 				    = get_site_url();
	$client_id_and_secret 		    = '';

	$auth_end_point = GHLCF7PRO_AUTH_END_POINT;
	$scopes = "workflows.readonly contacts.readonly contacts.write campaigns.readonly conversations/message.readonly conversations/message.write forms.readonly locations.readonly locations/customValues.readonly locations/customValues.write locations/customFields.readonly locations/customFields.write opportunities.readonly opportunities.write users.readonly links.readonly links.write surveys.readonly users.write locations/tasks.readonly locations/tasks.write locations/tags.readonly locations/tags.write locations/templates.readonly calendars.write calendars/groups.readonly calendars/groups.write forms.write medias.readonly medias.write";

    
    //  $connect_url = GHLCF7PRO_AUTH_URL . "?get_code=1&redirect_page={$redirect_page}";

	// if ( ! empty( $ghlcf7pro_client_id ) && ! str_contains( $ghlcf7pro_client_id, 'm1t4xu6d' ) ) {	
	// 	$connect_url = $auth_end_point . "?response_type=code&redirect_uri={$redirect_uri}&client_id={$ghlcf7pro_client_id}&scope={$scopes}";
	// }

    $settings_url = (isset($_GET['post'])) ? urlencode(admin_url('admin.php?page=wpcf7&post=' . $_GET['post'].'&action=edit')) : urlencode(admin_url('admin.php?page=ib-ghlcf7pro'));
    $server_url = "https://server.ibsofts.com/one-extension/market_app.php";
    $connect_url= add_query_arg(array('redirect_page' => $settings_url), $server_url);
	
?>

<div id="ib-ghlcf7">
    <h1> <?php esc_html_e('Connect With Your GHL Subaccount', 'ghl-cf7'); ?> </h1>
    <hr />
    <table class="form-table" role="presentation">
        <tbody>
            <tr>
                <th scope="row">
                    <label> <?php esc_html_e('Connect GHL Subaccount Location', 'ghl-cf7'); ?> </label>
                </th>
                <td>
                    <?php if ($ghlcf7pro_location_connected) { ?>
                    <div class="connected-location">
                        <button class="button button-connected" disabled>Connected</button>
                        <!-- Show success message after connection -->
                        <?php if (isset($_GET['connected']) && sanitize_text_field($_GET['connected']) === 'true') { ?>
                        <p class="success-message">You have successfully connected to Subaccount Location ID:
                            <?php echo esc_html($ghlcf7pro_locationId); ?></p>
                        <?php } ?>
                        <p class="description">To connect another subaccount location, click below:</p>
                        <a class="ghl_connect button" href="<?php echo esc_url($connect_url); ?>">Connect Another
                            Subaccount</a>
                    </div>
                    <?php } else { ?>
                    <div class="not-connected-location">
                        <p class="description">You're not connected to any subaccount location yet.</p>
                        <a class="ghl_connect button" href="<?php echo esc_url($connect_url); ?>">Connect GHL
                            Subaccount</a>
                    </div>
                    <?php } ?>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label><?php esc_html_e('Your Connected GHL Subaccount LocationID', 'ghl-cf7'); ?></label>
                </th>
                <td>
                    <?php if ($ghlcf7pro_location_connected) { ?>
                    <p class="description">Location ID: <?php echo esc_html($ghlcf7pro_locationId); ?></p>
                    <p class="description">Location Name: <?php echo esc_html($ghlcf7pro_locationName); ?></p>
                    <?php } else { ?>
                    <p class="description">You are not connected yet. Please connect by clicking the above button</p>
                    <?php } ?>
                </td>
            </tr>

        </tbody>
    </table>

</div>