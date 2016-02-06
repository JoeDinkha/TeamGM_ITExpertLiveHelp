<?php

ob_start();
$host="35.9.22.109"; // Host name
//$username=""; // Mysql username
//$password=""; // Mysql password
//$db_name="test"; // Database name
//$tbl_name="members"; // Table name


// Connect to server and select databse.
//server details
$serverName = "35.9.22.109, 1433";

//more server details
$connectionInfo = array("Database" => "db", "UID" => "priceja7", "PWD" => "teamgm16");

//establish connection to database
$conn = sqlsrv_connect($serverName,$connectionInfo);


// Define $myusername and $mypassword
$myusername=$_POST['myusername'];
$mypassword=$_POST['mypassword'];

// To protect MySQL injection (more detail about MySQL injection)
$myusername = stripslashes($myusername);
$mypassword = stripslashes($mypassword);

$sql="SELECT * FROM $tbl_name WHERE Username='$myusername' and Password='$mypassword'";
$result=mysqli_query($sql);

// Mysql_num_row is counting table row
$count=mysql_num_rows($result);

// If result matched $myusername and $mypassword, table row must be 1 row
if($count==1){

// Register $myusername, $mypassword and redirect to file "login_success.php"
    session_register("myusername");
    session_register("mypassword");
    header("location:login_success.php");
}
else {
    echo "Wrong Username or Password";
}
ob_end_flush();
?>


