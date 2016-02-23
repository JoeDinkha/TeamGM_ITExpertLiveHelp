<?php

/**
 * Author: Jacob Price
 */
session_start();

if( !isset($_SESSION['user']) ){
    header('Location: login.php');
}

else{
    $username = $_SESSION['user'];

    //server details
    $serverName = "35.9.22.109, 1433";

    //more server details
    $connectionInfo = array("Database" => "db", "UID" => "priceja7", "PWD" => "teamgm16");

    //establish connection to database
    $conn = sqlsrv_connect($serverName, $connectionInfo);

    //Retrieving current values in the database given a user
    if (isset($username)){
        //update database with given username and availability
        $query_string = "SELECT * FROM dbo.MockTable1 ORDER BY AverageRating DESC";

        //run the query and store results for future use
        $results = sqlsrv_query($conn,$query_string);

        //initialize arrays to hold usernames and average ratings
        $usernames = array();
        $passwords = array();
        $skypenames = array();
        $average_ratings = array();
        $availabilities = array();
        $skillwords = array();
        $skilloutlooks = array();
        $skillpowerpoints = array();
        $skillexplorers = array();
        $skillskypes = array();

        //push each username,average_rating pair into database
        while ($row = sqlsrv_fetch_array($results, SQLSRV_FETCH_ASSOC)){
            array_push($usernames,$row['Username']) ;
            array_push($passwords,$row['Password']);
            array_push($skypenames,$row['SkypeName']);
            array_push($average_ratings,$row['AverageRating']);
            array_push($availabilities,$row['Availability']);
            array_push($skillwords,$row['SkillWord']);
            array_push($skilloutlooks,$row['SkillOutlook']);
            array_push($skillpowerpoints,$row['SkillPowerPoint']);
            array_push($skillexplorers,$row['SkillExplorer']);
            array_push($skillskypes,$row['SkillSkype']);
        }


        $results = sqlsrv_fetch_array($results,SQLSRV_FETCH_ASSOC);


        //grabbing the average rating of the user and displaying them throughout the page

        $avg_rating = $results['AverageRating'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">


<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GM | IT Expert Live Help - Leaderboard</title>

    <!-- Stylesheets -->
    <link rel="stylesheet" href="style.css" type="text/css" />

    <!-- Script Imports -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
    <script src="leaderboard.js" type="text/javascript"></script>

    <!-- Favicons -->
    <link rel="shortcut icon" href="favicons/favicon.ico">
    <link rel="icon" sizes="16x16 32x32 64x64" href="favicons/favicon.ico">
    <link rel="icon" type="image/png" sizes="196x196" href="favicons/favicon-192.png">
    <link rel="icon" type="image/png" sizes="160x160" href="favicons/favicon-160.png">
    <link rel="icon" type="image/png" sizes="96x96" href="favicons/favicon-96.png">
    <link rel="icon" type="image/png" sizes="64x64" href="favicons/favicon-64.png">
    <link rel="icon" type="image/png" sizes="32x32" href="favicons/favicon-32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="favicons/favicon-16.png">
    <link rel="apple-touch-icon" href="favicons/favicon-57.png">
    <link rel="apple-touch-icon" sizes="114x114" href="favicons/favicon-114.png">
    <link rel="apple-touch-icon" sizes="72x72" href="favicons/favicon-72.png">
    <link rel="apple-touch-icon" sizes="144x144" href="favicons/favicon-144.png">
    <link rel="apple-touch-icon" sizes="60x60" href="favicons/favicon-60.png">
    <link rel="apple-touch-icon" sizes="120x120" href="favicons/favicon-120.png">
    <link rel="apple-touch-icon" sizes="76x76" href="favicons/favicon-76.png">
    <link rel="apple-touch-icon" sizes="152x152" href="favicons/favicon-152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="favicons/favicon-180.png">
    <meta name="msapplication-TileColor" content="#FFFFFF">
    <meta name="msapplication-TileImage" content="favicons/favicon-144.png">
    <meta name="msapplication-config" content="favicons/browserconfig.xml">
</head>


<body>
<div class="container">
    <header>
        <img src="images/Logo_of_General_Motors.png" width="2000" height="1989" alt="General Motors Logo" />
        <h1>IT Expert Live Help - Database</h1>
    </header>


    <div class="content" id="leaderboard">
        <h2>Database</h2>

        <table>
            <tr>
                <th>Username</th>
                <th>Password</th>
                <th>Skype Name</th>
                <th>Rating</th>
                <th>Availability</th>
                <th>Word</th>
                <th>Outlook</th>
                <th>PowerPoint</th>
                <th>Explorer</th>
                <th>Skype</th>
            </tr>


            <?php

            //php script to write our query results to the page
            for ($x=1; $x<=count($usernames); $x++){
                $name = $usernames[$x-1];
                echo "<tr id='..'>";
                echo '<td>'.$name.'</td>';
                echo '<td>'.$passwords[$x-1].'</td>';
                echo '<td>'.$skypenames[$x-1].'</td>';
                echo '<td>'.$average_ratings[$x-1].'</td>';
                echo '<td>'.$availabilities[$x-1].'</td>';
                echo '<td>'.$skillwords[$x-1].'</td>';
                echo '<td>'.$skilloutlooks[$x-1].'</td>';
                echo '<td>'.$skillpowerpoints[$x-1].'</td>';
                echo '<td>'.$skillexplorers[$x-1].'</td>';
                echo '<td>'.$skillskypes[$x-1].'</td>';
                echo '</tr>';
            }
            ?>

        </table>
    </div>


    <footer>
        <p>&copy; Team GM - Spring 2016</p>
    </footer>
</div>
</body>
</html>