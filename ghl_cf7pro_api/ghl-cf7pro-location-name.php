<?php

if ( ! function_exists( 'ghlcf7pro_location_name' ) ) {
    
    function ghlcf7pro_location_name($loc,$api_key) {

    	// $key = 'ghlcf7pro_location_name';
    	// $expiry = 60  * 60 * 24; // 1 day

    	// $name = get_transient($key);

    	// if ( !empty( $name ) ) {
    	// 	//delete_transient($key);
    	// 	return $name;
    	// }

		// $ghlcf7pro_locationId = get_option( 'ghlcf7pro_locationId' );
		// $ghlcf7pro_access_token = get_option( 'ghlcf7pro_access_token' );

		$endpoint = "https://services.leadconnectorhq.com/locations/{$loc}";
		$ghl_version = '2021-07-28';

		$request_args = array(
			'headers' => array(
				'Authorization' => "Bearer {$api_key}",
				'Content-Type' => 'application/json',
				'Version' => $ghl_version,
			),
		);

		$response = wp_remote_get( $endpoint, $request_args );
		$http_code = wp_remote_retrieve_response_code( $response );

		if ( 200 === $http_code ) {

			$body = wp_remote_retrieve_body( $response );
			$name = json_decode( $body )->location;
			// set_transient( $key, $name, $expiry );
			return $name;

		}
		// elseif( 401 === $http_code ){
		// 	ghlcf7pro_get_new_access_token();
		// }
    }
}