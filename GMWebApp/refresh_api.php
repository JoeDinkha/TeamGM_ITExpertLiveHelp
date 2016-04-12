<?php

    $url = "https://login.microsoftonline.com/common/oauth2/v2.0/token";

//    $data = "grant_type=refresh_token&client_id=df5c3f43-84b2-4444-a0e8-3022d364f53b&client_secret=APfpVLBgOLKhNrD7W1SpuXR&refresh_token=MCYWfBo3Q7kHPdGaOTJMfHwLbiD47gvqevwB4i4rqSl8ZyTqw6S2*kpdVtv6R7O1qV95yB0kBtmmxxxrXVnyAeKn2bNJ7DGFkcXlAqfbYfuenm08m7UGUgpNtIo5KhTA7LHGxU6dqBpuVtY7vDQkrlBrLHFCTUHTAP6Mtz*hSo7IddyaWcFvgGh44XqFNKGivqtt6kMtgCnB*1RRpKaV5Abe23tFCiyXGd66dn0DVHeYIBkysby6pyqimeV7aAIX4mhqAy3kJOx2hG80i!NbMxl6iHXVsHF9CxgVoIJR7SlD7fN3gPouCV!S4eLKxYdhL8T8mTspthqXXviSYVlUnCmI6hjS6UFXtuZUIJLPx8IXJFvT3V1WVrZUZJR3C7OYctMxxlSjKblilaOAMUl1g8hx2fdHaN4eD5Xlp3tWqp46f
//        &scope=openid https://outlook.office.com/calendars.read offline_access&redirect_uri=https://35.9.22.109/GMWebApp/index.php";

    $data = array(
        'client_id'      =>   'df5c3f43-84b2-4444-a0e8-3022d364f53b',
        'grant_type'     =>   'refresh_token',
        'scope'          =>   'openid https://outlook.office.com/calendars.read offline_access',
        'refresh_token'  =>   'MCYWfBo3Q7kHPdGaOTJMfHwLbiD47gvqevwB4i4rqSl8ZyTqw6S2*kpdVtv6R7O1qV95yB0kBtmmxxxrXVnyAeKn2bNJ7DGFkcXlAqfbYfuenm08m7UGUgpNtIo5KhTA7LHGxU6dqBpuVtY7vDQkrlBrLHFCTUHTAP6Mtz*hSo7IddyaWcFvgGh44XqFNKGivqtt6kMtgCnB*1RRpKaV5Abe23tFCiyXGd66dn0DVHeYIBkysby6pyqimeV7aAIX4mhqAy3kJOx2hG80i!NbMxl6iHXVsHF9CxgVoIJR7SlD7fN3gPouCV!S4eLKxYdhL8T8mTspthqXXviSYVlUnCmI6hjS6UFXtuZUIJLPx8IXJFvT3V1WVrZUZJR3C7OYctMxxlSjKblilaOAMUl1g8hx2fdHaN4eD5Xlp3tWqp46f',
        'redirect_uri'   =>   'https://35.9.22.109/GMWebApp/index.php',
        'client_secret'  =>   'APfpVLBgOLKhNrD7W1SpuXR'
    );

    //$content = json_encode( $data );
    //echo $content;


    //// Try 1 ////
//    $options = array(
//        'http' => array(
//            'method'  => 'POST',
//            'content' => json_encode( $data ),
//            'header'=>  "Content-Type: application/json\r\n" .
//                "Accept: application/json\r\n",
//
//            'data'  => "client_id: df5c3f43-84b2-4444-a0e8-3022d364f53b\r\n" .
//             "scope: openid https://outlook.office.com/calendars.read offline_access\r\n" .
//            "refresh_token: MCYWfBo3Q7kHPdGaOTJMfHwLbiD47gvqevwB4i4rqSl8ZyTqw6S2*kpdVtv6R7O1qV95yB0kBtmmxxxrXVnyAeKn2bNJ7DGFkcXlAqfbYfuenm08m7UGUgpNtIo5KhTA7LHGxU6dqBpuVtY7vDQkrlBrLHFCTUHTAP6Mtz*hSo7IddyaWcFvgGh44XqFNKGivqtt6kMtgCnB*1RRpKaV5Abe23tFCiyXGd66dn0DVHeYIBkysby6pyqimeV7aAIX4mhqAy3kJOx2hG80i!NbMxl6iHXVsHF9CxgVoIJR7SlD7fN3gPouCV!S4eLKxYdhL8T8mTspthqXXviSYVlUnCmI6hjS6UFXtuZUIJLPx8IXJFvT3V1WVrZUZJR3C7OYctMxxlSjKblilaOAMUl1g8hx2fdHaN4eD5Xlp3tWqp46f\r\n" .
//            "redirect_uri: https://35.9.22.109/GMWebApp/index.php\r\n" .
//            "grant_type: refresh_token" . "client_secret: APfpVLBgOLKhNrD7W1SpuXR\r\n"
//        )
//    );

//    $context  = stream_context_create( $options );
//    $result = file_get_contents( $url, false, $context );
//    $response = json_decode( $result );
//
//    var_dump( $response);


    //// Try 2 ////
//    $curl = curl_init($url);
//
//    curl_setopt( $curl, CURLOPT_HEADER, false );
//    curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );
//    curl_setopt( $curl, CURLOPT_HTTPHEADER, array("Content-type: application/json") );
//    curl_setopt( $curl, CURLOPT_POST, true );
//    curl_setopt( $curl, CURLOPT_POSTFIELDS, $content );
//
//    $json_response = curl_exec($curl);
//
//    $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
//
//    if ( $status != 201 ) {
//        die("Error: call to URL $url failed with status $status, response $json_response, curl_error " .
//            curl_error($curl) . ", curl_errno " . curl_errno($curl));
//    }
//
//    curl_close($curl);
//
//    $response = json_decode($json_response, true);
//
//    var_dump( $response);


    //// Try 3 ////
    //Initiate cURL.
    $ch = curl_init($url);

    //Encode the array into JSON.
    $jsonDataEncoded = json_encode($data);

    //Tell cURL that we want to send a POST request.
    curl_setopt($ch, CURLOPT_POST, 1);

    //Attach our encoded JSON string to the POST fields.
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonDataEncoded);

    //Set the content type to application/json
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

    //Execute the request
    $result = curl_exec($ch);

    // Close curl
    curl_close($ch);

    var_dump( $result );

?>