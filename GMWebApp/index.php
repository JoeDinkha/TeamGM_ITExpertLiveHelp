<?php
/**
 * Author: Jacob Price
 */
session_start();
if( !isset($_SESSION['user']) ) {
    header('Location: login.php');
    exit;
}

$username = $_SESSION['user'];

//server details
$serverName = "35.9.22.109, 1433";

//more server details
$connectionInfo = array("Database" => "db", "UID" => "priceja7", "PWD" => "teamgm16");

//establish connection to database
$conn = sqlsrv_connect($serverName, $connectionInfo);


//// User Query - Retrieve current values in the database, given a user
$query_string = "SELECT * FROM dbo.MockTable1 WHERE Username='".$username."'";

//run the query and store results for future use
$results = sqlsrv_query($conn,$query_string);
$results = sqlsrv_fetch_array($results,SQLSRV_FETCH_ASSOC);

//grabbing all of these variables for the user and displaying them throughout the page
$userID = $results['UID'];
$available = $results['Availability'];
$word = $results['SkillWord'];
$outlook = $results['SkillOutlook'];
$powerpoint = $results['SkillPowerPoint'];
$explorer = $results['SkillExplorer'];
$skype = $results['SkillSkype'];
$avg_rating = $results['AverageRating'];
$expert_skills = $results['ExpertSkills'];


//// Feedback Query - Retrieve feedback for a user
$query_string2 = "SELECT Username, Date, Feedback, Rating FROM dbo.FeedbackTable"; //WHERE UID=".$userID;

// Run query and store results
$results2 = sqlsrv_query( $conn, $query_string2 );
$results2 = sqlsrv_fetch_array( $results2, SQLSRV_FETCH_ASSOC );

// Store result variables
$fUsername = $results2['Username'];
$fDate = $results2['Date'];
$fFeedback = $results2['Feedback'];
$fRating = $results2['Rating'];

// Format DateTime object
//$fDate = $fDate->format( 'M d, Y' );

//****************grab all of the skills dynamically*************//
$query_string_skills = "SELECT * FROM dbo.SkillTable ORDER BY IndexPosition";
$skill_results = sqlsrv_query($conn,$query_string_skills);
$skill_array = array();
while ($row = sqlsrv_fetch_array($skill_results,SQLSRV_FETCH_ASSOC)){
    $temp_val = $row['Skills'];
    array_push($skill_array,$temp_val);
}


//echo $skill_array;
?>

<!DOCTYPE html>
<html lang="en">


<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GM | IT Expert Live Help</title>

    <!-- Stylesheets -->
    <link rel="stylesheet" href="style.css" type="text/css" />
    <link rel="stylesheet" href="jquery-toggles-master/css/toggles.css" type="text/css" />
    <link rel="stylesheet" href="jquery-toggles-master/css/themes/toggles-modern.css" type="text/css" />
    <link rel="stylesheet" href="chosen_v1.5.0/chosen.css" type="text/css" />

    <!-- Script Imports -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
    <script src="main.js" type="text/javascript"></script>
    <script src="jquery-toggles-master/toggles.min.js" type="text/javascript"></script>
    <script src="chosen_v1.5.0/chosen.jquery.min.js" type="text/javascript"></script>

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
        <img src="images/Logo_of_General_Motors.png" width="2000" height="1989" alt="General Motors Logo" />
        <h1>IT Expert Live Help</h1>
    </header>


    <div class="content">
        <div id="profilePanel">
            <img id="profilePic" src="images/team-photo.jpeg" width="4256" height="2832" alt="Profile Picture" />

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


            <div id="availabilityToggle" class="toggle-modern"></div>

            <script type="text/javascript">
                //toggle availability if the user is available in the database
                $(document).ready( function($) {

                    var li = document.createElement('li');
                    var li_search = document.createElement('li');

                    li.className = 'search-choice';
                    li_search.className = 'search-field';
                    li.innerHTML = '<span>Microsoft Word</span><a class="search-choice-close" data-option-array-index="0"></a>';
                    li_search.innerHTML = '<input type="text" value="Select Your Skills" class autocomplete="off" style="width: 150px;">';


                    //$('.chosen-choices').empty();
                    //$('.chosen-choices').append(li);
                    //$('.chosen-choices').append(li_search);

                    //('li.active-result').click();

                    var word = "<?php echo $word; ?>";
                    var outlook = "<?php echo $outlook; ?>";
                    var powerpoint = "<?php echo $powerpoint; ?>";
                    var explorer = "<?php echo $explorer; ?>";
                    var skype = "<?php echo $skype; ?>";

                    //console.log(word);
                    //console.log(outlook);
                    //console.log(powerpoint);
                    //console.log(explorer);
                    //console.log(skype);


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

                    $('#logOut').click (function() {
                        window.location.href = 'post/logout-post.php';
                    });
                });
            </script>


            <h3>Skills</h3>

            <form id="skillForm" action="" method="post">
                <label for="skillSelect" hidden>Select Your Skills</label>

                <select id="skillSelect" name="skillSelect" data-placeholder="Select Your Skills" multiple>
                    <?php

                        /**
                        //check for word
                        if ($word == "1"){
                            echo '<option value="word" selected="selected" >Microsoft Word</option>';
                        }
                        else{
                            echo '<option value="word" >Microsoft Word</option>';
                        }

                        //check for outlook
                        if ($outlook == "1"){
                            echo '<option value="outlook" selected="selected">Microsoft Outlook</option>';
                        }
                        else{
                            echo '<option value="outlook">Microsoft Outlook</option>';
                        }

                        //check for powerpoint
                        if ($powerpoint == "1"){
                            echo '<option value="powerpoint" selected="selected">Microsoft PowerPoint</option>';
                        }
                        else{
                            echo '<option value="powerpoint">Microsoft PowerPoint</option>';
                        }

                        //check for explorer
                        if ($explorer == "1"){
                            echo '<option value="IE" selected="selected">Internet Explorer</option>';
                        }
                        else{
                            echo '<option value="IE">Internet Explorer</option>';
                        }

                        //check for skype
                        if ($skype == "1"){
                            echo '<option value="skype" selected="selected">Skype for Business</option>';
                        }
                        else{
                            echo '<option value="skype">Skype for Business</option>';
                        }
                         * **/
                        for($x=0; $x<= count($skill_array); $x++){
                            echo "<p>".$x."</p>";
                            if ($expert_skills[$x] == "1"){
                                echo '<option value="word" selected="selected" >'.$skill_array[$x].'</option>';
                            }
                            else{
                                echo '<option value="word" >'.$skill_array[$x].'</option>';

                            }
                        }

                    ?>;
                </select>

                <input type="submit" id="saveSkills" value="Save" />
            </form>

            <button id="leaderboards">Leaderboards</button>
            <button id="logOut">Log Out</button>
        </div>


        <div id="calendarFeedbackBox">
            <div id="calendar">
                <h3>Calendar</h3>
                <button>Add Office Hours</button>
            </div>


            <div id="bestReviews">
                <h3>Best Feedback</h3>

                <div class="bestReview">
                    <?php
                        // Test for getting feedback data from database - working!
                        for( $i = 1; $i <= $fRating; $i++ ) {
                            //echo $fRating;
                            echo '<img src="images/star.png" width="500" height="472" alt="Star" />';
                        }
                    ?>
                    <br/>

                    <h4><?php echo $fUsername; ?></h4>
                    <span class="date"><?php echo $fDate; ?></span>
                    <p><?php echo $fFeedback; ?></p>
                </div>

                <div class="bestReview">
                    <img src="images/star.png" width="500" height="472" alt="Star" />
                    <img src="images/star.png" width="500" height="472" alt="Star" />
                    <img src="images/star.png" width="500" height="472" alt="Star" />
                    <img src="images/star.png" width="500" height="472" alt="Star" />
                    <br/>
                    <h4>Jenna Sanocki</h4>
                    <span class="date">Jan. 15, 2016</span>
                    <p>I appreciate you helping me set up my Outlook signature settings.</p>
                </div>

                <div class="bestReview">
                    <img src="images/star.png" width="500" height="472" alt="Star" />
                    <img src="images/star.png" width="500" height="472" alt="Star" />
                    <img src="images/star.png" width="500" height="472" alt="Star" />
                    <img src="images/star.png" width="500" height="472" alt="Star" />
                    <br/>
                    <h4>Zack Keith</h4>
                    <span class="date">Jan. 15, 2016</span>
                    <p>Quick and simple help towards fixing my Skype issues! Thanks again.</p>
                </div>

                <div class="bestReview">
                    <img src="images/star.png" width="500" height="472" alt="Star" />
                    <img src="images/star.png" width="500" height="472" alt="Star" />
                    <img src="images/star.png" width="500" height="472" alt="Star" />
                    <img src="images/star.png" width="500" height="472" alt="Star" />
                    <img src="images/star.png" width="500" height="472" alt="Star" />
                    <br/>
                    <h4>Dr. Dyksen</h4>
                    <span class="date">Mar. 10, 2016</span>
                    <p>You were the best! Thank you so much! :D</p>
                </div>

                <div class="bestReview">
                    <img src="images/star.png" width="500" height="472" alt="Star" />
                    <img src="images/star.png" width="500" height="472" alt="Star" />
                    <img src="images/star.png" width="500" height="472" alt="Star" />
                    <img src="images/star.png" width="500" height="472" alt="Star" />
                    <br/>
                    <h4>Angie</h4>
                    <span class="date">Mar. 12, 2016</span>
                    <p>99%</p>
                </div>

                <button id="showMoreBestFeedback">Show More Feedback</button>
            </div>


            <div id="worstReviews">
                <h3>Worst Feedback</h3>

                <div class="worstReview">
                    <img src="images/star.png" width="500" height="472" alt="Star" />
                    <img src="images/star.png" width="500" height="472" alt="Star" />
                    <img src="images/star.png" width="500" height="472" alt="Star" />
                    <br/>
                    <h4>Shuhao Zhang</h4>
                    <span class="date">Feb. 1, 2016</span>
                    <p>You weren't as knowledgeable with Skype as I thought you'd be...</p>
                </div>

                <div class="worstReview">
                    <img src="images/star.png" width="500" height="472" alt="Star" />
                    <img src="images/star.png" width="500" height="472" alt="Star" />
                    <br/>
                    <h4>Jake Price</h4>
                    <span class="date">Jan. 6, 2016</span>
                    <p>You weren't able to help me fix the issue I was having with Microsoft Word.</p>
                </div>

                <div class="worstReview">
                    <img src="images/star.png" width="500" height="472" alt="Star" />
                    <br/>
                    <h4>UofM Fan</h4>
                    <span class="date">Jan. 30, 2016</span>
                    <p>I'm mad that MSU is better at literally everything.</p>
                </div>

                <div class="worstReview">
                    <img src="images/star.png" width="500" height="472" alt="Star" />
                    <img src="images/star.png" width="500" height="472" alt="Star" />
                    <br/>
                    <h4>Fox</h4>
                    <span class="date">Mar. 10, 2016</span>
                    <p>Needs more foxes</p>
                </div>

                <div class="worstReview">
                    <img src="images/star.png" width="500" height="472" alt="Star" />
                    <br/>
                    <h4>Cat</h4>
                    <span class="date">Mar. 12, 2016</span>
                    <p>I want to sleep...</p>
                </div>

                <button id="showMoreWorstFeedback">Show More Feedback</button>
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