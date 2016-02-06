<html>

<head>
    <title>HTML5 Login</title>
    <link rel="stylesheet" href="normalize.css">
    <link rel="stylesheet" href="style.css">
</head>

<?php
/**
 * Created by PhpStorm.
 * User: cse498
 * Date: 2/1/16
 * Time: 7:16 PM
 */


if ($_GET['username']){


    $serverName = "35.9.22.109, 1433"; //serverName\instanceName

    // Since UID and PWD are not specified in the $connectionInfo array,
    // The connection will be attempted using Windows Authentication.
    $connectionInfo = array("Database" => "db","UID"=>"priceja7","PWD"=>"teamgm16");
    $conn = sqlsrv_connect( $serverName, $connectionInfo);

    $sql = "SELECT Password FROM dbo.MockTable1 WHERE Username='".$_GET['username']."'";



    $stmt = sqlsrv_query($conn,$sql);

    if ($stmt == true){
        $results = sqlsrv_fetch_array($stmt,SQLSRV_FETCH_ASSOC);

        for ($x=0; $x < strlen($results['Password']); $x++){
            $char = substr($results['Password'],$x,1);
            echo "<p>$char</p>";
        }

        //echo strlen($_GET['pass']);
        if ($results['Password'] == $_GET['pass']){
            echo "<script type='text/javascript'>alert('Successfully Logged in as: ".$_GET['username']."');</script>";
        }

    }





//if( $conn ) {
    //echo "Connection established.<br />";
//}else{
    //echo "Connection could not be established.<br />";
    //die( print_r( sqlsrv_errors(), true));
//}
}


?>


<body>
<section class="loginform cf">



    <form name="login"" method="get">
    <ul>
        <li>
            <label>Username</label>
            <input type="text" name="username" placeholder="name" required>
        </li>
        <li>
            <label>Password</label>
            <input type="password" name="pass" placeholder="pass" required></li>
        <li>
            <input type="submit" value="Login">
        </li>
    </ul>
    </form>



</section>


</body>
</html>

