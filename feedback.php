<?php
/**
 * Created by PhpStorm.
 * User: cse498
 * Date: 2/2/16
 * Time: 7:17 PM
 */


//values for database
$expert = $_POST['Expert'];
$author = $_POST['Author'];
$stars = $_POST['StarCount'];
$comments = $_POST['Comments'];
$skill = $_POST['Skill'];
$date = date("Y-m-d");
echo $date;

$serverName = "35.9.22.109, 1433"; //serverName\instanceName

// Since UID and PWD are not specified in the $connectionInfo array,
// The connection will be attempted using Windows Authentication.
$connectionInfo = array("Database" => "db","UID"=>"priceja7","PWD"=>"teamgm16");
$conn = sqlsrv_connect( $serverName, $connectionInfo);
$query_string = "INSERT INTO dbo.Feedback VALUES('".$expert."','".$author."',".$stars.",'".$comments."','".$date."',
'".$skill."')";

$results = sqlsrv_query($conn,$query_string);


?>