<?php
	 if ( ! defined( 'ABSPATH' ) ) exit;
	
	$ghlcf7pro_location_connected	= get_option( 'ghlcf7pro_location_connected', GHLCF7PRO_LOCATION_CONNECTED );
	$ghlcf7pro_client_id 			= get_option( 'ghlcf7pro_client_id' );
	$ghlcf7pro_client_secret 		= get_option( 'ghlcf7pro_client_secret' );
	$ghlcf7pro_locationId 		    = get_option( 'ghlcf7pro_locationId' );
	$ghlcf7pro_locationName 		= get_option( 'ghlcf7pro_location_name' );
	$redirect_page 				    = get_site_url(null, '/wp-admin/admin.php?page=ib-ghlcf7pro');
	$redirect_uri 				    = get_site_url();
	$client_id_and_secret 		    = '';

    $settings_url = (isset($_GET['post'])) ? urlencode(admin_url('admin.php?page=wpcf7&post=' . $_GET['post'].'&action=edit')) : urlencode(admin_url('admin.php?page=ib-ghlcf7pro'));
    $server_url = "https://server.ibsofts.com/one-extension/market_app.php";
    $connect_url= add_query_arg(array('redirect_page' => $settings_url), $server_url);

    //perform the delete operation
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST['disconnect_glob_loc'])) {
            delete_option('ghlcf7pro_access_token');
            delete_option('ghlcf7pro_refresh_token');
            delete_option('ghlcf7pro_locationId');
            delete_option('ghlcf7pro_client_id');
            delete_option('ghlcf7pro_client_secret');
            delete_option('ghlcf7pro_location_name');
            delete_option('ghlcf7pro_token_expire');
            wp_redirect('admin.php?page=ib-ghlcf7pro');        
        }
    }
	
?>

<div id="ib-ghlcf7">
    <h1> <?php esc_html_e('Connect With Your GHL Subaccount', 'ghl-cf7pro'); ?> </h1>
    <hr />
    <table class="form-table" role="presentation">
        <tbody>
            <tr>
                <th scope="row">
                    <label> <?php esc_html_e('Connect GHL Subaccount Location', 'ghl-cf7pro'); ?> </label>
                </th>
                <td>
                    <?php if ($ghlcf7pro_locationId) { ?>
                    <div class="connected-location">
                        <form method="post">
                            <button class="ghl_cf7pro_btn button" type="submit" name="disconnect_glob_loc">Disconnect
                                from
                                GHL</button>
                        </form>
                    </div>
                    <?php } else { ?>
                    <div class="not-connected-location">
                        <p class="description">You're not connected to any subaccount location yet.</p>
                        <a class="ghl_cf7pro_btn button" href="<?php echo esc_url($connect_url); ?>">Connect to
                            Subaccount</a>
                    </div>
                    <?php } ?>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label><?php esc_html_e('Your Connected GHL Subaccount LocationID', 'ghl-cf7pro'); ?></label>
                </th>
                <td>
                    <?php if ($ghlcf7pro_locationId) { ?>
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