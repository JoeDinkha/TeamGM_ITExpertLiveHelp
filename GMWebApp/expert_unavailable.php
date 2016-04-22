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

// Change this to "update the status of the user with this name to Offline"
$query_string2 = "SELECT * FROM dbo.Mocktable1 WHERE SkypeName=".$expert;

$results = sqlsrv_query($conn,$query_string);

$results2 = sqlsrv_query($conn, $query_string2);
$results3 = sqlsrv_fetch_array($results2, SQLSRV_FETCH_ASSOC);


//Email information
$admin_email = "noreplyGMITLiveHelp@gmail.com";
$email = $results3['Email'];
$name = $results3['FullName'];
$subject = "IT Expert Live Help: Availability Notice";
$comment = "Dear ".$name.",\n
You were marked unavailable when a user tried to reach you for help.
Your office hours have been overridden and you are now set to offline.\n
Thanks, \n
GM IT Expert Live Help
";

//send email
mail($email, "$subject", $comment, "From:" . $admin_email);


echo($query_string);
echo ($results3['Email']);
?>

