<?php

if ( ! function_exists( 'ghlcf7pro_get_access_token' ) ) {
    
    function ghlcf7pro_get_access_token($body) {
        $url = 'https://services.leadconnectorhq.com/oauth/token';

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "client_id=" . $body['client_id'] . "&client_secret=" . $body['client_secret'] . "&grant_type=refresh_token&code=&refresh_token=" . $body['refresh_token'] . "&user_type=Location&redirect_uri=",
            CURLOPT_HTTPHEADER => [
                "Accept: application/json",
                "Content-Type: application/x-www-form-urlencoded"
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        // if ($err) {
        //     $this->log->log_error("Error: ".$err);
        // } else {
            return $response;
        // }

    }
}