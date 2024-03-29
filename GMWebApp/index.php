<?php

/**
 * Author: Jacob Price (PHP)
 * Author: Jenna Sanocki (HTML/CSS/JS)
 * Author: Joe Dinkha (HTML/JS)
 */

//check if user has logged in properly, if not, go back to login
session_start();
if( !isset($_SESSION['user']) ) {
    header('Location: login.php');
    exit;
}

//grab the username
$username = $_SESSION['user'];

//server details
$serverName = "35.9.22.109, 1433";

//more server details
$connectionInfo = array("Database" => "db", "UID" => "priceja7", "PWD" => "teamgm16");

//establish connection to database
$conn = sqlsrv_connect($serverName, $connectionInfo);


//// User Query - Retrieve current values in the database, given a user
$query_string = "SELECT * FROM dbo.MockTable1 WHERE Username='".$username."'";

// run the query and store results for future use
$results = sqlsrv_query($conn,$query_string);
$results = sqlsrv_fetch_array($results,SQLSRV_FETCH_ASSOC);

// grabbing all of these variables for the user and displaying them throughout the page
$username = $results['FullName'];
$available = $results['Availability'];
$override = $results['Override'];
$word = $results['SkillWord'];
$outlook = $results['SkillOutlook'];
$powerpoint = $results['SkillPowerPoint'];
$explorer = $results['SkillExplorer'];
$skype = $results['SkillSkype'];
$expert_skills = $results['ExpertSkills'];
$userSkype = $results['SkypeName'];


/****** Code to calculate average rating *******/
$query_stringCalc = "SELECT Author, StarCount, Comment, Date, Skill FROM dbo.Feedback WHERE Expert=";
$query_stringCalc = $query_stringCalc."'".$userSkype."' ORDER By StarCount DESC";

// Run query and store results
$totalStars = 0;
$numFeedback = 0;
$results3_query = sqlsrv_query( $conn, $query_stringCalc );

//loop through each row in the result from the query
while ($results3 = sqlsrv_fetch_array($results3_query, SQLSRV_FETCH_ASSOC)) {
    $fRating = $results3['StarCount'];
    $totalStars = $totalStars + $fRating;
    $numFeedback++;
}

// Update average rating in database
$avg_rating = null;

// If user has no feedback, set average rating to 0
if( $numFeedback <= 0 ) {
    $avg_rating = 0;
}

// Else, calculate average rating
else {
    $avg_rating = $totalStars / $numFeedback;       // Example: 5.25
    $beforeDecimal = floor( $avg_rating );          // Example: 5
    $afterDecimal = $avg_rating - $beforeDecimal;   // Example: 0.25

    // Round down
    if( $afterDecimal <= 0.44) {
        $avg_rating = floor( $avg_rating );
    }

    // Round up
    else {
        $avg_rating = ceil( $avg_rating );
    }
}


/**************** Grab all of the skills dynamically *************/
$query_string_skills = "SELECT * FROM dbo.SkillTable ORDER BY IndexPosition";
$skill_results = sqlsrv_query($conn,$query_string_skills);
$skill_array = array();

while ($row = sqlsrv_fetch_array($skill_results,SQLSRV_FETCH_ASSOC)){
    $temp_val = $row['Skills'];
    array_push($skill_array,$temp_val);
}
?>

<!DOCTYPE html>
<html lang="en">


<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0" />
    <title>GM | IT Expert Live Help</title>

    <!-- Stylesheets -->
    <link rel="stylesheet" href="style.css" type="text/css" />
    <link rel="stylesheet" href="jquery-toggles-master/css/toggles.css" type="text/css" />
    <link rel="stylesheet" href="jquery-toggles-master/css/themes/toggles-modern.css" type="text/css" />
    <link rel="stylesheet" href="chosen_v1.5.0/chosen.css" type="text/css" />
    <link rel="stylesheet" href="https://addtocalendar.com/atc/1.5/atc-base.css" type="text/css" />
    <link rel="stylesheet" href="remodal_v1.0.7/dist/remodal.css" />
    <link rel="stylesheet" href="remodal_v1.0.7/dist/remodal-default-theme.css" />

    <!-- Script Imports -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
    <script src="main.js" type="text/javascript"></script>
    <script src="jquery-toggles-master/toggles.min.js" type="text/javascript"></script>
    <script src="chosen_v1.5.0/chosen.jquery.min.js" type="text/javascript"></script>
    <script src="https://addtocalendar.com/atc/1.5/atc.min.js" type="text/javascript"></script>
    <script src="remodal_v1.0.7/dist/remodal.min.js"></script>

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
<div class="container" id="index">
    <header>
        <img src="images/Logo_of_General_Motors.png" width="713" height="717" alt="General Motors Logo" />
        <h1>IT Expert Live Help</h1>
    </header>


    <div class="content">
        <div id="profilePanel">
            <img id="profilePic" src="images/team-photo.png" width="1064" height="708"
                 alt="<?echo $username; ?>'s Profile Picture" />

            <h2 id="name"><?php echo $username; ?></h2>

            <div id="rating">
                <?php
                    /***Author: Jacob Price***/
                    //code for displaying the amount of average stars you have,
                    //rounded to the nearest integer
                    for ($x = 0; $x < $avg_rating; $x++) {
                        echo "<img src='images/star.png' width='500' height='472' alt='Star' />";
                    }
                ?>
            </div>


            <script type="text/javascript">
                //toggle availability if the user is available in the database
                $(document).ready( function($) {

                    var li = document.createElement('li');
                    var li_search = document.createElement('li');

                    li.className = 'search-choice';
                    li_search.className = 'search-field';
                    li.innerHTML = '<span>Microsoft Word</span><a class="search-choice-close" data-option-array-index="0"></a>';
                    li_search.innerHTML = '<input type="text" value="Select Your Skills" class autocomplete="off" style="width: 150px;">';

                    var availability = "<?php echo $available; ?>";

                    if (availability == "1"){
                        $('.toggle-modern').toggles({
                            on: true,
                            text: {on:'Online', off:'Offline'}
                        });
                    }

                    else if(availability == "0") {
                        $('.toggle-modern').toggles({
                            on: false,
                            text: {on:'Online', off:'Offline'}
                        });
                    }
                });
            </script>


            <h3>Skills</h3>

            <form id="skillForm" action="" method="post">
                <label for="skillSelect" hidden>Select Your Skills</label>

                <select id="skillSelect" name="skillSelect" data-placeholder="Select Your Skills" multiple>
                    <?php
                        for($x=0; $x<= count($skill_array); $x++){
                            echo "<p>".$x."</p>";

                            if ($expert_skills[$x] == "1"){
                                echo '<option value="word" selected="selected" >'.$skill_array[$x].'</option>';
                            }

                            else{
                                echo '<option value="word" >'.$skill_array[$x].'</option>';
                            }
                        }
                    ?>
                </select>

                <input type="submit" id="saveSkills" value="Save" />
            </form>


            <div class="remodal" data-remodal-id="skillsModal">
                <button data-remodal-action="close" class="remodal-close"></button>

                <h3>Your skills have been updated!</h3>
                <br/>

                <button data-remodal-action="confirm" class="remodal-confirm">OK</button>
            </div>


            <button id="leaderboard" value="Leaderboard">Leaderboard</button>
            <button id="logOut" value="Log Out">Log Out</button>
        </div>


        <div id="calendarFeedbackBox">
            <div id="calendarBox">
                <h3>Calendar</h3>

                <span class="addtocalendar" data-calendars="Outlook">
                    <a class="atcb-link">Set Office Hours in Outlook</a>

                    <var class="atc_event">
                        <var class="atc_date_start">2016-04-29 12:00:00</var>
                        <var class="atc_date_end">2016-04-29 14:00:00</var>
                        <var class="atc_timezone">America/New_York</var>
                        <var class="atc_title">Office Hours</var>
                        <var class="atc_description">Availability period to assist others</var>
                        <var class="atc_location">Online</var>
                        <var class="atc_organizer"><?php echo $username; ?></var>
                        <var class="atc_organizer_email"></var>
                    </var>
                </span>

                <button id="outlookOnline" value="Outlook Online">
                    <a target="_blank" href="https://bay02.calendar.live.com/calendar/calendar.aspx?rru=addevent&startdt=20160429T12%3a00%3a00Z&enddt=20160429T14%3a00%3a00Z&summary=Office+Hours&location=Online&description=IT+Expert+Live+Help&allday=false&uid=">
                        Set Office Hours in Outlook Online
                    </a>
                </button>

                <?php
                    if ($override == "1"){
                        echo '<input id="overrideCheckbox" type="checkbox" value="Override Office Hours" checked />';
                    }
                    else{
                        echo '<input id="overrideCheckbox" type="checkbox" value="Override Office Hours" />';
                    }
                ?>

                <label for="overrideCheckbox">Override Office Hours</label>
                <div id="availabilityToggle" class="toggle-modern"></div>
            </div>


            <div id="bestReviews">
                <h3>Positive Feedback</h3>

                <?php
                    $query_string2 = "SELECT Author,StarCount,Comment,Date, Skill FROM dbo.Feedback WHERE Expert=";
                    $query_string2=$query_string2."'".$userSkype."' ORDER BY StarCount DESC, Date DESC";


                    // Run query and store results
                    $results2_query = sqlsrv_query( $conn, $query_string2 );
                    $numPositiveFeedback = 0;

                    while ($results2= sqlsrv_fetch_array($results2_query,SQLSRV_FETCH_ASSOC)) {
                        // Store result variables
                        $fUsername = $results2['Author'];
                        $fDate = $results2['Date'];
                        $fFeedback = $results2['Comment'];
                        $fRating = $results2['StarCount'];
                        $fSkill = $results2['Skill'];

                        if ($fDate != NULL){
                            $fDate = $fDate->format( 'M d, Y' );
                        }

                        /********** Retrieve Actual Names rather than Usernames *********/
                        //// User Query - Retrieve current values in the database, given a user
                        $quick_query = "SELECT * FROM dbo.MockTable1 WHERE Username='".$fUsername."'";

                        //run the query and store results for future use
                        $quick_results = sqlsrv_query($conn,$quick_query);
                        $quick_results = sqlsrv_fetch_array($quick_results,SQLSRV_FETCH_ASSOC);

                        //grabbing all of these variables for the user and displaying them throughout the page
                        $fUsername = $quick_results['FullName'];

                        if ($fRating > 2){
                            echo '<div class="bestReview">';

                            // Test for getting feedback data from database - working!
                            for( $i = 1; $i <= $fRating; $i++ ) {
                                echo '<img src="images/star.png" width="500" height="472" alt="Star" />';
                            }
                            echo '<br/>';

                            echo '<h4>'.$fUsername.'</h4>';
                            echo '<span class="date">'.$fDate.'</span>';

                            echo '<span class="skill">Skill: '.$fSkill.'</span>';
                            echo '<p>'.$fFeedback.'</p>';

                            echo '</div>';

                            $numPositiveFeedback++;
                        }
                    }
                ?>

                <?php if( $numPositiveFeedback > 3 ) { ?>
                    <button id="showMoreBestFeedback">Show More Feedback</button>
                <?php } ?>
            </div>


            <div id="worstReviews">
                <h3>Negative Feedback</h3>

                <?php
                    $query_string2 = "SELECT Author,StarCount,Comment,Date, Skill FROM dbo.Feedback WHERE Expert=";
                    $query_string2=$query_string2."'".$userSkype."' ORDER BY StarCount, Date DESC";


                    // Run query and store results
                    $results2_query = sqlsrv_query( $conn, $query_string2 );
                    $numNegativeFeedback = 0;

                    while ($results2= sqlsrv_fetch_array($results2_query,SQLSRV_FETCH_ASSOC)){
                        // Store result variables
                        $fUsername = $results2['Author'];
                        $fDate = $results2['Date'];
                        $fFeedback = $results2['Comment'];
                        $fRating = $results2['StarCount'];
                        $fSkill = $results2['Skill'];
                        if ($fDate != NULL){
                            $fDate = $fDate->format( 'M d, Y' );
                        }

                        /********** Retrieve Actual Names rather than Usernames *********/
                        //// User Query - Retrieve current values in the database, given a user
                        $quick_query = "SELECT * FROM dbo.MockTable1 WHERE Username='".$fUsername."'";

                        //run the query and store results for future use
                        $quick_results = sqlsrv_query($conn,$quick_query);
                        $quick_results = sqlsrv_fetch_array($quick_results,SQLSRV_FETCH_ASSOC);

                        //grabbing all of these variables for the user and displaying them throughout the page
                        $fUsername = $quick_results['FullName'];

                        if ($fRating < 3){
                            echo '<div class="worstReview">';
                            // Test for getting feedback data from database - working!
                            for( $i = 1; $i <= $fRating; $i++ ) {
                                //echo $fRating;
                                echo '<img src="images/star.png" width="500" height="472" alt="Star" />';
                            }
                            echo '<br/>';

                            echo '<h4>'.$fUsername.'</h4>';
                            echo '<span class="date">'.$fDate.'</span>';

                            echo '<span class="skill">Skill: '.$fSkill.'</span>';
                            echo '<p>'.$fFeedback.'</p>';

                            echo '</div>';

                            $numNegativeFeedback++;
                        }
                    }
                ?>

                <?php if( $numNegativeFeedback > 3 ) { ?>
                    <button id="showMoreWorstFeedback">Show More Feedback</button>
                <?php } ?>
            </div>

        </div>
    </div>


    <form id="refreshForm">
        <input type="hidden" name="visited" value="" />
    </form>

    <footer>
        <p>&copy; Team GM - Spring 2016</p>
    </footer>
</div>
</body>
</html>