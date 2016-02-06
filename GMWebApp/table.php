<html>
<body>

<?php
/**
 * Created by PhpStorm.
 * User: cse498
 * Date: 2/1/16
 * Time: 7:16 PM
 */
$serverName = "35.9.22.109, 1433"; //serverName\instanceName

// Since UID and PWD are not specified in the $connectionInfo array,
// The connection will be attempted using Windows Authentication.
$connectionInfo = array("Database" => "db","UID"=>"priceja7","PWD"=>"teamgm16");
$conn = sqlsrv_connect( $serverName, $connectionInfo);


if( $conn ) {
    echo "Connection established.<br />";
}else{
    echo "Connection could not be established.<br />";
    die( print_r( sqlsrv_errors(), true));
}



?>

Welcome <?php echo $_POST["name"]; ?><br>
Your information has been successfully submitted.

</body>
</html>