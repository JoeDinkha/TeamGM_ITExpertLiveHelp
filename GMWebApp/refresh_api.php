<?php

$curl = curl_init();

curl_setopt_array($curl, array(
    CURLOPT_URL => "https://login.microsoftonline.com/common/oauth2/v2.0/token",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS => "client_id=df5c3f43-84b2-4444-a0e8-3022d364f53b&scope=openid%20https%3A%2F%2Foutlook.office.com%2Fcalendars.read%20offline_access&refresh_token=MCYWfBo3Q7kHPdGaOTJMfHwLbiD47gvqevwB4i4rqSl8ZyTqw6S2*kpdVtv6R7O1qV95yB0kBtmmxxxrXVnyAeKn2bNJ7DGFkcXlAqfbYfuenm08m7UGUgpNtIo5KhTA7LHGxU6dqBpuVtY7vDQkrlBrLHFCTUHTAP6Mtz*hSo7IddyaWcFvgGh44XqFNKGivqtt6kMtgCnB*1RRpKaV5Abe23tFCiyXGd66dn0DVHeYIBkysby6pyqimeV7aAIX4mhqAy3kJOx2hG80i!NbMxl6iHXVsHF9CxgVoIJR7SlD7fN3gPouCV!S4eLKxYdhL8T8mTspthqXXviSYVlUnCmI6hjS6UFXtuZUIJLPx8IXJFvT3V1WVrZUZJR3C7OYctMxxlSjKblilaOAMUl1g8hx2fdHaN4eD5Xlp3tWqp46f&redirect_uri=https%3A%2F%2F35.9.22.109%2FGMWebApp%2Findex.php&grant_type=refresh_token&client_secret=APfpVLBgOLKhNrD7W1SpuXR",
    CURLOPT_HTTPHEADER => array(
        "cache-control: no-cache",
        "content-type: application/x-www-form-urlencoded",
        "postman-token: 780d610a-68a6-e6e5-4631-ed26a6e70555"
    ),
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
    echo "cURL Error #:" . $err;
} else {
    echo $response;
}

?>