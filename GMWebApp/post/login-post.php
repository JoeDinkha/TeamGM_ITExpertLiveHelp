<?php
/**
 * Created by PhpStorm.
 * User: cse498
 * Date: 2/4/16
 * Time: 4:55 PM
 */

$login=True;
ob_start();
$serverName = "35.9.22.109, 1433";

$myusername = $_REQUEST['username'];
$mypassword = $_REQUEST['password'];

$developer = $_POST['developerMode'];

if ($developer == "Developer Mode"){
    session_start();
    $_SESSION['user']="Team GM Dev";

    header('Location: ../index.php');
    exit();
}

echo $developer;







if( $myusername != NULL && $mypassword != NULL ) {
    // To protect MySQL injection (more detail about MySQL injection)
    $myusername = stripslashes($myusername);
    $mypassword = stripslashes($mypassword);


    //more server details
    $connectionInfo = array("Database" => "db", "UID" => "priceja7", "PWD" => "teamgm16");

    //connection
    //establish connection to database
    $conn = sqlsrv_connect($serverName, $connectionInfo);

//    //Check connection, connection works
    if ($conn) {
        echo "connected\r\n<br>";
    }
    else{
        echo "Failed connected  \r\n";
    }

//*******************Query SQL Server for the login of the user accessing the database*******************

    $sql = "SELECT * FROM dbo.Mocktable1 WHERE Username = '".$myusername."' AND Password = '".$mypassword."'";

    //query is a resource ID
    $result =  sqlsrv_query($conn,$sql);

    //get database name
//    $name = sqlsrv_get_field($result, 1);
//    echo "name:$name: ";echo "<br><br>";

    //get password
//    $password = sqlsrv_get_field($result, 2);
//    echo "password:$password:";echo "<br><br>";

    //Need to fetch_array
    $count=sqlsrv_fetch_array($result);

    echo "sqlsrv_fetch_array: "; var_dump($count); echo "<br><br>";
    // echo $count;

    if ($count!=NULL) {

        session_start();
        $_SESSION['user']=$myusername;

        header('Location: ../index.php');
    }
    else {
        header('Location: ../login.php');
    }


}
else {
    //echo "Please enter Username and Password" ;
    header('Location: ../login.php');

}