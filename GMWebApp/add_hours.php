<?php
/**
 * Author: Zack Keith
 */


$expert = $_POST['expert'];
$in = $_POST['in_time'];
$out = $_POST['out_time'];

$serverName = "35.9.22.109, 1433"; //serverName\instanceName

// Since UID and PWD are not specified in the $connectionInfo array,
// The connection will be attempted using Windows Authentication.
$connectionInfo = array("Database" => "db","UID"=>"priceja7","PWD"=>"teamgm16");
$conn = sqlsrv_connect( $serverName, $connectionInfo);

for($x = 0; $x<sizeof($in); $x++){

    //hard coded outlook to work with only joe's calendar, in future use, if you want to make it work with multiple users,
    //you'll need a column in the sql database to grab each user's refresh token, didn't have time to implement

    // CHANGE THIS PART TO BE DYNAMIC
    $query_string = "INSERT INTO dbo.OfficeHours VALUES ('" . $expert . "', '" . substr($in[$x], 0, -1) . "', '"
        . substr($out[$x], 0, -1) . "')";

    $results = sqlsrv_query($conn, $query_string);

}

echo("SUCCESS");

?>

