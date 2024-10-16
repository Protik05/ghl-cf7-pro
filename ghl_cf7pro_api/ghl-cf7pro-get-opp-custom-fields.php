<?php

if ( ! function_exists( 'ghlcf7pro_get_opp_custom_fields' ) ) {
    
    function ghlcf7pro_get_opp_custom_fields($loc,$api_key) {

        if (empty($api_key)) {

            $ghl_log = new GHLCF7PRO_Log();
            $ghl_log->log_error('Error: Ensure API key is configured in plugin setting or form setting');

            return;
        }
		$url = "https://services.leadconnectorhq.com/locations/{$loc}/customFields";
		// Data to be sent as query parameters
        $params = array(
            'model' => 'opportunity'
           
        );

        // Append query parameters to the URL
        $url .= '?' . http_build_query($params);
        $ch = curl_init();
        
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');



        $headers = array();
        $headers[] = 'Authorization: Bearer ' . $api_key;
        $headers[] = 'Version: 2021-07-28';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
            $ghl_log = new GHLCF7PRO_Log();
            $ghl_log->log_error("Error: ".curl_error($ch));
        }
        curl_close($ch);

        $custom_fields = json_decode($result);

        $custom_fields = (isset($custom_fields->customFields)) ? $custom_fields->customFields : "";

        return $custom_fields;
    }
}