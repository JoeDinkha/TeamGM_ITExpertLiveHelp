<?php
/**
 * Created by PhpStorm.
 * User: zkeit
 * Date: 2/3/2016
 * Time: 6:27 PM
 */


//$expertise = $_GET['expertise'];
$expertise = $_POST['expertise'];

$serverName = "35.9.22.109, 1433"; //serverName\instanceName

// Since UID and PWD are not specified in the $connectionInfo array,
// The connection will be attempted using Windows Authentication.
$connectionInfo = array("Database" => "db","UID"=>"priceja7","PWD"=>"teamgm16");
$conn = sqlsrv_connect( $serverName, $connectionInfo);
$query_string = "SELECT Skills FROM dbo.SkillTable ORDER BY IndexPosition";

$results = sqlsrv_query($conn,$query_string);

$skillArray = [];

while ($array = sqlsrv_fetch_array($results,SQLSRV_FETCH_ASSOC)){
    array_push($skillArray,$array['Skills']);
}

//echo array_search($expertise,$skillArray);
$index = array_search($expertise,$skillArray)+1;


$query_expert = "SELECT SkypeName,FullName FROM dbo.Mocktable1 INNER JOIN dbo.OfficeHours ON dbo.Mocktable1.FullName=dbo.OfficeHours
.Expert
 WHERE SUBSTRING(ExpertSkills,
".$index.",1) = 
'1' AND 
(Availability='1' OR (Time_In < GETDATE() AND Time_Out > GETDATE()))";

$values = sqlsrv_query($conn,$query_expert);

$expert = sqlsrv_fetch_array($values,SQLSRV_FETCH_ASSOC);
$result = $expert['SkypeName'].";".$expert['FullName'];

echo $result;
?>

