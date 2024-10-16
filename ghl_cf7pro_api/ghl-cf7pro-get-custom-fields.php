<?php

if ( ! function_exists( 'ghlcf7pro_get_custom_fields' ) ) {
    
    function ghlcf7pro_get_custom_fields($loc,$api_key) {

        if (empty($api_key)) {

           $ghl_log = new GHLCF7PRO_Log();
            $ghl_log->log_error('Error: Ensure API key is configured in plugin setting or form setting');

            return;
        }
		
		$endpoint = "https://services.leadconnectorhq.com/locations/{$loc}/customFields";
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
            $custom_fields = json_decode($body);
            $custom_fields = (isset($custom_fields->customFields)) ? $custom_fields->customFields : "";
			
			// set_transient( $key, $name, $expiry );
			return $custom_fields;
		}
    }
}