<?php
if ( ! defined( 'ABSPATH' ) ) exit;


add_action('init', function() {

    $ghlcf7pro_locationId = get_option( 'ghlcf7pro_locationId' );
    $is_access_token_valid_cf7pro = get_transient('is_access_token_valid');

    if ( ! empty( $ghlcf7pro_locationId ) && ! $is_access_token_valid_cf7pro ) {
        
        // renew the access token
        ghlcf7pro_get_new_access_token();
    }

});

function ghlcf7pro_get_new_access_token()
{
	$key = 'is_access_token_valid_cf7pro';
    $expiry = 59  * 60 * 24; // almost 1 day

	$ghlcf7pro_client_id 		= get_option( 'ghlcf7pro_client_id' );
	$ghlcf7pro_client_secret 	= get_option( 'ghlcf7pro_client_secret' );
	$refreshToken 			    = get_option( 'ghlcf7pro_refresh_token' );
	
	$endpoint = GHLCF7PRO_GET_TOKEN_API;
	$body = array(
		'client_id' 	=> $ghlcf7pro_client_id,
		'client_secret' => $ghlcf7pro_client_secret,
		'grant_type' 	=> 'refresh_token',
		'refresh_token' => $refreshToken
	);

	$request_args = array(
		'body' 		=> $body,
		'headers' 	=> array(
			'Content-Type' => 'application/x-www-form-urlencoded',
		),
	);

	$response = wp_remote_post( $endpoint, $request_args );
	$http_code = wp_remote_retrieve_response_code( $response );

	if ( 200 === $http_code ) {

		$body = json_decode( wp_remote_retrieve_body( $response ) );
		$new_ghlcf7pro_access_token = $body->access_token;
		$new_ghlcf7pro_refresh_token = $body->refresh_token;

		update_option( 'ghlcf7pro_access_token', $new_ghlcf7pro_access_token );
		update_option( 'ghlcf7pro_refresh_token', $new_ghlcf7pro_refresh_token );

	
		set_transient( $key, true, $expiry );
	}

	return null;
}

function ghlcf7pro_get_first_auth_code($code, $client_id, $client_secret){

    $endpoint = GHLCF7PRO_GET_TOKEN_API;
    $body = array(
        'client_id'     => $client_id,
        'client_secret' => $client_secret,
        'grant_type'    => 'authorization_code',
        'code'          => $code
    );

    $request_args = array(
        'body'      => $body,
        'headers'   => array(
            'Content-Type' => 'application/x-www-form-urlencoded',
        ),
    );

    $response = wp_remote_post( $endpoint, $request_args );
    $http_code = wp_remote_retrieve_response_code( $response );

    if ( 200 === $http_code ) {

        $body = json_decode( wp_remote_retrieve_body( $response ) );
        return $body;
    }    
}