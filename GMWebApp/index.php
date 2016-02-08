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
        $query_string = "SELECT Availability,SkillWord,SkillOutlook,SkillPowerPoint,SkillExplorer,SkillSkype,AverageRating FROM dbo.MockTable1 WHERE Username='".$username."'";

        //run the query and store results for future use
        $results = sqlsrv_query($conn,$query_string);
        $results = sqlsrv_fetch_array($results,SQLSRV_FETCH_ASSOC);

        //grabbing all of these variables for the user and displaying them throughout the page
        $available = $results['Availability'];
        $word = $results['SkillWord'];
        $outlook = $results['SkillOutlook'];
        $powerpoint = $results['SkillPowerPoint'];
        $explorer = $results['SkillExplorer'];
        $skype = $results['SkillSkype'];
        $avg_rating = $results['AverageRating'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">


<head>
    <meta charset="UTF-8">
    <title>GM | IT Expert Live Help</title>

    <!-- Stylesheets -->
    <link rel="stylesheet" href="style.css" type="text/css" />
    <link rel="stylesheet" href="jquery-toggles-master/css/toggles.css">
    <link rel="stylesheet" href="jquery-toggles-master/css/themes/toggles-modern.css">

    <!-- Script Imports -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
    <script src="main.js" type="text/javascript"></script>
    <script src="jquery-toggles-master/toggles.js" type="text/javascript"></script>
</head>


<body>
    <div class="container">
        <header>
            <img src="images/Logo_of_General_Motors.png" width="2000" height="1989" alt="General Motors Logo" />
            <h1>IT Expert Live Help</h1>
        </header>


        <div id="content">
            <div id="profilePanel">
                <img id="profilePic" src="images/team-photo.jpeg" width="4256" height="2832" alt="Profile Picture" />

                <h2 id="name"><?php echo $username; ?></h2>

                <div id="rating">
                    <?php

                    /***Author: Jacob Price***/
                    //code for displaying the amount of average stars you have, rounded to the nearest integer
                    for ($x = 0; $x < $avg_rating; $x++) {
                        echo "<img src=\"images/star.png\" width=\"500\" height=\"472\" alt=\"Star\" />";
                    }
                    ?>
                </div>

                <div id="availabilityToggle" class="toggle-modern"></div>

                <script type="text/javascript">
                    //toggle availability if the user is available in the database
                    $(document).ready( function($) {
                        var availability = "<?php echo $available; ?>";
                        if (availability == "1"){
                            $('.toggle-modern').click();
                        }
                    });
                </script>

                <h3>Skills</h3>

                <form id="skillForm" action="" method="post">
                    <input class="Checkbox" type="checkbox" id="word" value="Microsoft Word" name="<?php echo $word; ?>"/>
                    <label for="word">Microsoft Word</label>
                    <br/>

                    <input class="Checkbox" type="checkbox" id="outlook" value="Microsoft Outlook" name="<?php echo $outlook; ?>"/>
                    <label for="outlook">Microsoft Outlook</label>
                    <br/>

                    <input class="Checkbox" type="checkbox" id="powerpoint" value="Microsoft PowerPoint" name="<?php echo $powerpoint; ?>"/>
                    <label for="powerpoint">Microsoft PowerPoint</label>
                    <br/>

                    <input class="Checkbox" type="checkbox" id="IE" value="Internet Explorer" name="<?php echo $explorer; ?>"/>
                    <label for="IE">Internet Explorer</label>
                    <br/>

                    <input class="Checkbox" type="checkbox" id="skype" value="Skype for Business" name="<?php echo $skype; ?>"/>
                    <label for="skype">Skype for Business</label>
                    <br/>

                    <input type="submit" id="saveSkills" value="Save" />
                </form>

                <button id="logOut">Log Out</button>
            </div>


            <div id="calendarFeedbackBox">
                <div id="calendar">
                    <h3>Calendar Sync</h3>
                </div>


                <div id="bestReviews">
                    <h3>Best Feedback</h3>

                    <div class="bestReview">
                        <img src="images/star.png" width="500" height="472" alt="Star" />
                        <img src="images/star.png" width="500" height="472" alt="Star" />
                        <img src="images/star.png" width="500" height="472" alt="Star" />
                        <img src="images/star.png" width="500" height="472" alt="Star" />
                        <img src="images/star.png" width="500" height="472" alt="Star" />
                        <br/>
                        <h4>Joe Dinkha</h4>
                        <span class="date">(Jan. 27, 2016)</span>
                        <p>Thanks for helping me with my browser display issues! That was fast.</p>
                    </div>

                    <div class="bestReview">
                        <img src="images/star.png" width="500" height="472" alt="Star" />
                        <img src="images/star.png" width="500" height="472" alt="Star" />
                        <img src="images/star.png" width="500" height="472" alt="Star" />
                        <img src="images/star.png" width="500" height="472" alt="Star" />
                        <br/>
                        <h4>Jenna Sanocki</h4>
                        <span class="date">(Jan. 15, 2016)</span>
                        <p>I appreciate you helping me set up my Outlook signature settings.</p>
                    </div>

                    <div class="bestReview">
                        <img src="images/star.png" width="500" height="472" alt="Star" />
                        <img src="images/star.png" width="500" height="472" alt="Star" />
                        <img src="images/star.png" width="500" height="472" alt="Star" />
                        <img src="images/star.png" width="500" height="472" alt="Star" />
                        <br/>
                        <h4>Zack Keith</h4>
                        <span class="date">(Jan. 15, 2016)</span>
                        <p>Quick and simple help towards fixing my Skype issues! Thanks again.</p>
                    </div>

                    <button id="showMoreFeedback">Show More Feedback</button>
                </div>


                <div id="worstReviews">
                    <h3>Worst Feedback</h3>

                    <div class="worstReview">
                        <img src="images/star.png" width="500" height="472" alt="Star" />
                        <img src="images/star.png" width="500" height="472" alt="Star" />
                        <img src="images/star.png" width="500" height="472" alt="Star" />
                        <br/>
                        <h4>Shuhao Zhang</h4>
                        <span class="date">(Feb. 1, 2016)</span>
                        <p>You weren't as knowledgeable with Skype as I thought you'd be...</p>
                    </div>

                    <div class="worstReview">
                        <img src="images/star.png" width="500" height="472" alt="Star" />
                        <img src="images/star.png" width="500" height="472" alt="Star" />
                        <br/>
                        <h4>Jake Price</h4>
                        <span class="date">(Jan. 6, 2016)</span>
                        <p>You weren't able to help me fix the issue I was having with Microsoft Word.</p>
                    </div>

                    <div class="worstReview">
                        <img src="images/star.png" width="500" height="472" alt="Star" />
                        <br/>
                        <h4>Dr. D</h4>
                        <span class="date">(Jan. 30, 2016)</span>
                        <p>You were significantly late on responding in the Skype call.</p>
                    </div>
                    <button id="showMoreFeedback">Show More Feedback</button>
                </div>

            </div>
        </div>


        <footer>
            <p>&copy; Team GM - Spring 2016</p>
        </footer>
    </div>
</body>
</html>