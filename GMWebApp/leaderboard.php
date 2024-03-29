<?php

/**
 * Author: Jacob Price
 */
session_start();
$username = null;

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
        $query_string = "SELECT * FROM dbo.MockTable1 ORDER BY AverageRating DESC, TotalReviews DESC";

        // query to get username for leaderboard indication
        $queryUsers = "SELECT * FROM dbo.MockTable1 ORDER BY AverageRating DESC, TotalReviews DESC";

        // run the query and store results for future use
        $results = sqlsrv_query($conn,$query_string);
        $userResults = sqlsrv_query($conn, $queryUsers);

        // initialize arrays to hold usernames and average ratings
        $fullnames = array();
        $average_ratings = array();
        $usernames = array();
        $skypenames = array();
        $totalReviews = array();

        // push each username,average_rating pair into database
        while ($row = sqlsrv_fetch_array($results, SQLSRV_FETCH_ASSOC)){
            array_push($fullnames,$row['FullName']);
            array_push($average_ratings,$row['AverageRating']);
            array_push($skypenames, $row['SkypeName']);
            array_push($totalReviews, $row['TotalReviews']);
        }

        
        for ($x = 0; $x < count($skypenames); $x++){
            $skype = $skypenames[$x];
            $query_stringCalc = "SELECT Author, StarCount, Comment, Date, Skill FROM dbo.Feedback WHERE Expert=";
            $query_stringCalc = $query_stringCalc."'".$skype."' ORDER By StarCount DESC";

            // Run query and store results
            $totalStars = 0;
            $numFeedback = 0;
            $results3_query = sqlsrv_query( $conn, $query_stringCalc );

            while ($results3 = sqlsrv_fetch_array($results3_query, SQLSRV_FETCH_ASSOC)) {
                $fRating = $results3['StarCount'];
                $totalStars = $totalStars + $fRating;
                $numFeedback++;
            }

            if ($numFeedback > 0){
                $averageUpdate = $totalStars/$numFeedback;
            }
            else{
                $averageUpdate = 0;
            }

            //rounding
            if (($averageUpdate - floor($averageUpdate)) >= .5){
                $averageUpdate = ceil($averageUpdate);
            }
            else{
                $averageUpdate = floor($averageUpdate);
            }

            // query to get username for leaderboard indication
            $queryUpdate = "UPDATE dbo.MockTable1 SET TotalReviews ='".$numFeedback."', AverageRating = '"
                .$averageUpdate
                ."' WHERE SkypeName = '".$skype."'";
            $results4_query = sqlsrv_query( $conn, $queryUpdate);
        }


        // push each username,average_rating pair into database
        while ($row = sqlsrv_fetch_array($userResults, SQLSRV_FETCH_ASSOC)){
            array_push($usernames,$row['Username']);
        }

        $results = sqlsrv_fetch_array($results,SQLSRV_FETCH_ASSOC);

        // grabbing the average rating of the user and displaying them throughout the page
        $avg_rating = $results['AverageRating'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">


<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0" />
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
    <div class="container" id="leaderboard">
        <header>
            <img src="images/Logo_of_General_Motors.png" width="713" height="717" alt="General Motors Logo" />
            <h1>IT Expert Live Help | Leaderboard</h1>
        </header>


        <div class="content">
            <table>
                <tr>
                    <th>Rank</th>
                    <th>User</th>
                    <th>Rating</th>
                    <th>Total Reviews</th>
                </tr>

                <?php
                    // All tds retrieved from database
                    for ($x=0; $x<count($fullnames); $x++) {
                        if ($average_ratings[$x] > 2){

                            $name = $usernames[$x];

                            if ($name == $username){
                                echo "<tr id='currentUser'>";
                            }

                            else {
                                echo "<tr>";
                            }

                            // Rank
                            echo '<td>'.($x+1).'</td>';

                            // Username
                            echo '<td>'.$fullnames[$x].'</td>';

                            // Rating
                            echo '<td>';

                            for( $star = 0; $star < $average_ratings[$x]; $star++ ) {
                                echo '<img src="images/star.png" width="500" height="472" alt="Star" />';
                            }

                            echo '</td>';

                            // Total Reviews
                            echo '<td>'.$totalReviews[$x].'</td>';

                            echo '</tr>';
                        }
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