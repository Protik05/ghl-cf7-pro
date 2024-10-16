<?php

if ( ! function_exists( 'ghlcf7pro_create_contact' ) ) {
    
    function ghlcf7pro_create_contact($ghl_args,$api_key,$form_id,$form_name) {
        //implement auth V2 GHL API
		$location_name = get_option("ghlcf7pro_location_name_" . $form_id);

        if (empty($location_name)) {
            $location_name = get_option("ghlcf7pro_location_name");

            if (empty($location_name)) {
                $location_name = 'Not Connected';
            }
        }
		
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
            $ghl_log = new GHLCF7PRO_Log();
            $ghl_log->log_action('Contact Created( Form name: ' . $form_name . ' , Form ID: ' . $form_id . ' , Connected Location: ' . $location_name . ' ): Contact ID: ' . $contact->id);
			return $contact->id;
		}
		else {
            $ghl_log = new GHLCF7PRO_Log();
            $ghl_log->log_error('API Error (Form name: ' . $form_name . ' , Form ID: ' . $form_id . ' , Connected Location: ' . $location_name . '): ' . $response);
        }
    }
}