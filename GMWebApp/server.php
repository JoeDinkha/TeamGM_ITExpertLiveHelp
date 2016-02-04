<?php
/**
 * Created by PhpStorm.
 * User: cse498
 * Date: 2/3/16
 * Time: 7:26 PM
 * Author: Jacob Price
 */

//server details
$serverName = "35.9.22.109, 1433";

//more server details
$connectionInfo = array("Database" => "db", "UID" => "priceja7", "PWD" => "teamgm16");

//establish connection to databse
$conn = sqlsrv_connect($serverName,$connectionInfo);

//retrieve username and availability
$username = $_POST['username'];
$value = $_POST['availability'];

//update database with given username and availability
$query_string = "UPDATE dbo.Mocktable1 SET Availability='".$value."' WHERE Username='".$username."'";

//run the query and store results for future use
$results = sqlsrv_query($conn,$query_string);

?>