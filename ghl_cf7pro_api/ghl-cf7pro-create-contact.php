<?php

if ( ! function_exists( 'ghlcf7pro_create_contact' ) ) {
    
    function ghlcf7pro_create_contact($ghl_args,$api_key) {
        //implement auth V2 GHL API
		$endpoint = "https://services.leadconnectorhq.com/contacts/upsert";
		$ghl_version = '2021-07-28';

		$request_args = array(
			'body' 		=> $ghl_args,
			'headers' 	=> array(
				'Authorization' => "Bearer {$api_key}",
				'Version' 		=> $ghl_version
			),
		);

		$response = wp_remote_post( $endpoint, $request_args );
		$http_code = wp_remote_retrieve_response_code( $response );

		if ( 200 === $http_code || 201 === $http_code ) {

			$body = json_decode( wp_remote_retrieve_body( $response ) );
			$contact = $body->contact;

			return $contact->id;
		}
    }
}