<?php
/**
 * Author: Zack Keith
 */


$expert = $_POST['expert'];

$serverName = "35.9.22.109, 1433"; //serverName\instanceName

// Since UID and PWD are not specified in the $connectionInfo array,
// The connection will be attempted using Windows Authentication.
$connectionInfo = array("Database" => "db","UID"=>"priceja7","PWD"=>"teamgm16");
$conn = sqlsrv_connect( $serverName, $connectionInfo);

$expert = "'".$expert."'";

// Change this to "update the status of the user with this name to Offline"
$query_string = "UPDATE dbo.Mocktable1 SET Availability='0' WHERE SkypeName=".$expert;

$results = sqlsrv_query($conn,$query_string);

echo($query_string);
?>

