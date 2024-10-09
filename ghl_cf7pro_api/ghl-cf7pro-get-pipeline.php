<?php

if ( ! function_exists( 'ghlcf7pro_get_pipeline' ) ) {
    
    function ghlcf7pro_get_pipeline($loc,$api_key) {

        $url = 'https://services.leadconnectorhq.com/opportunities/pipelines';
        $params = array(
            'locationId' => $loc
           
        );

        // Append query parameters to the URL
        $url .= '?' . http_build_query($params);
        $ch = curl_init();
        
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');



        $headers = array();
        $headers[] = 'Authorization: Bearer ' .$api_key;
        $headers[] = 'Version: 2021-07-28';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        // if (curl_errno($ch)) {
        //     echo 'Error:' . curl_error($ch);
        //     $this->log->log_error("Error: ".curl_error($ch));
        // }
        curl_close($ch);
        $pipelines = json_decode($result);
        $pipelines = (isset($pipelines->pipelines)) ? $pipelines->pipelines : "";

        return $pipelines;
    }
}