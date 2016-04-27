<?php

// JOEY DIESEL SAYS: Create connection to DB in this file to grab RefreshToken and PostmanToken columns
// there is only calendars for Joe and Jenna accounts

//grab the username
$username = $_POST['username'];
echo $username;

//server details
$serverName = "35.9.22.109, 1433";

//more server details
$connectionInfo = array("Database" => "db", "UID" => "priceja7", "PWD" => "teamgm16");

//establish connection to database
$conn = sqlsrv_connect($serverName, $connectionInfo);


//// User Query - Retrieve current values in the database, given a user
$query_string = "SELECT * FROM dbo.MockTable1 WHERE FullName='".$username."'";

// run the query and store results for future use
$results = sqlsrv_query($conn,$query_string);
$results = sqlsrv_fetch_array($results,SQLSRV_FETCH_ASSOC);

// grabbing all of these variables for the user and displaying them throughout the page


$refresh_token = $results['RefreshToken'];
$postman_token = $results['PostmanToken'];

if ($refresh_token == NULL){
    exit();
}

if ($postman_token == NULL){
    exit();
}

$curl = curl_init();

curl_setopt_array($curl, array(
    CURLOPT_URL => "https://login.microsoftonline.com/common/oauth2/v2.0/token",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "POST",

    // JOEY DIESEL SAYS: See where it says &refresh_token= you need to use the $refreshtoken variable there and
    // concatenate it into the string, make sure to not remove any part of the part where it says &redirect_uri=....
    CURLOPT_POSTFIELDS => "client_id=df5c3f43-84b2-4444-a0e8-3022d364f53b&scope=openid%20https%3A%2F%2Foutlook.office.com%2Fcalendars.read%20offline_access&refresh_token=".$refresh_token."&redirect_uri=https%3A%2F%2F35.9.22.109%2FGMWebApp%2Findex.php&grant_type=refresh_token&client_secret=APfpVLBgOLKhNrD7W1SpuXR",
    CURLOPT_HTTPHEADER => array(
        "cache-control: no-cache",
        "content-type: application/x-www-form-urlencoded",

        // JOEY DIESEL SAYS: Use the $postmantoken variable here after the 'postman-token: '
        "postman-token: ".$postman_token
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

// FINALLY: Change the hardcoded Joe Dinkha line in add_hours.php to work with this dynamic calendar script
// Remove all the Joey Diesel comments lol

?>
