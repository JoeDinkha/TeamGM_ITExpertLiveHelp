<?php
/**
 * Created by PhpStorm.
 * User: zkeit
 * Date: 2/3/2016
 * Time: 6:27 PM
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
    
    if($expert=="Joe Dinkha") {

        $query_string = "INSERT INTO dbo.OfficeHours VALUES ('" . $expert . "', '" . substr($in[$x], 0, -4) . "', '" . substr($out[$x], 0, -4) . "')";

        $results = sqlsrv_query($conn, $query_string);
    }

}



echo("SUCCESS");
?>

