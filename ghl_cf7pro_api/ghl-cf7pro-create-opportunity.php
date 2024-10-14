<?php

if ( ! function_exists( 'ghlcf7pro_create_opportunity' ) ) {
    
    function ghlcf7pro_create_opportunity($data,$api_key) {
        //implement auth V2 GHL API
		$endpoint = "https://services.leadconnectorhq.com/opportunities/upsert";
		$ghl_version = '2021-07-28';

		$request_args = array(
			'body' 		=> $data,
			'headers' 	=> array(
				'Authorization' => "Bearer {$api_key}",
				'Version' 		=> $ghl_version
			),
		);

		$response = wp_remote_post( $endpoint, $request_args );
		$http_code = wp_remote_retrieve_response_code( $response );

		if ( 200 === $http_code || 201 === $http_code ) {

			$body = json_decode( wp_remote_retrieve_body( $response ) );
			return $body;
		}
    }
}