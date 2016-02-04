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
$conn = sqlsrv_connect($serverName, $connectionInfo);

//retrieve username and availability
$username = $_POST['username'];
$value = $_POST['availability'];

//update database with given username and availability
$query_string = "UPDATE dbo.Mocktable1 SET Availability='".$value."' WHERE Username='".$username."'";

$word = $_POST['SkillWord'];
$outlook = $_POST['SkillOutlook'];
$powerpoint = $_POST['SkillPowerPoint'];
$explorer = $_POST['SkillExplorer'];
$business = $_POST['SkillSkype'];

//$query_string2 = "UPDATE dbo.Mocktable1 SET SkillWord=".$business.", SkillOutlook='1', SkillPowerPoint='1' WHERE Username='priceja7'";
$query_string2 = "UPDATE dbo.Mocktable1
SET SkillWord=".$word.", SkillOutlook=".$outlook.", SkillPowerPoint=".$powerpoint."
, SkillExplorer = ".$explorer.", SkillSkype=".$business."
WHERE Username='".$username."'";

//run the query and store results for future use
$results = sqlsrv_query($conn,$query_string);
$results = sqlsrv_query($conn,$query_string2);

?>