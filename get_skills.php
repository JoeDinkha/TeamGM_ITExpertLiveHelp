<?php
/**
 * Created by PhpStorm.
 * User: zkeit
 * Date: 2/3/2016
 * Time: 6:27 PM
 */

//$expertise = $_GET['expertise'];
$expertise = "SkillOutlook";

$serverName = "35.9.22.109, 1433"; //serverName\instanceName

// Since UID and PWD are not specified in the $connectionInfo array,
// The connection will be attempted using Windows Authentication.
$connectionInfo = array("Database" => "db","UID"=>"priceja7","PWD"=>"teamgm16");
$fire=$_POST['Fire'];
$conn = sqlsrv_connect( $serverName, $connectionInfo);
$query_string = "SELECT Skills FROM dbo.SkillTable ORDER BY IndexPosition";

$results = sqlsrv_query($conn,$query_string);

while ($array = sqlsrv_fetch_array($results,SQLSRV_FETCH_ASSOC)){
    echo $array['Skills'].';';
}



?>