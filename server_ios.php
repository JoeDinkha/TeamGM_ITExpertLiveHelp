<?php
/**
 * Created by PhpStorm.
 * User: cse498
 * Date: 2/2/16
 * Time: 7:17 PM
 */

$user_app_signin = $_POST['Username'];
$pass_app_signin = $_POST['Password'];

$serverName = "35.9.22.109, 1433"; //serverName\instanceName

// Since UID and PWD are not specified in the $connectionInfo array,
// The connection will be attempted using Windows Authentication.
$connectionInfo = array("Database" => "db","UID"=>"priceja7","PWD"=>"teamgm16");
$conn = sqlsrv_connect( $serverName, $connectionInfo);
$variable = $user_app_signin;
$query_string = "SELECT UserName, Password FROM dbo.Mocktable1 WHERE UserName='$user_app_signin'";

$results = sqlsrv_query($conn,$query_string);
$array = sqlsrv_fetch_array($results,SQLSRV_FETCH_ASSOC) ;

if (count($array['Password'])==0 ){
    // no user found
    echo "no such user";
}
else {
    //echo strlen($array['Password']);
    $array['Password'] = str_replace(' ', '', $array['Password']);
    //echo strlen($array['Password']);
    if ($pass_app_signin == $array['Password']){
        // good login, sign in
        echo "login success";
    }
    else {
        // bad login, return fail
        echo "login failed";
    }
}

$user_equiv = "False";
if ($user_app_signin == $array['UserName']) {
    $user_equiv = "True";
}
//echo ($user_equiv);

?>