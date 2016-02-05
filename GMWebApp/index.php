<?php

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
    <link rel="shortcut icon" href="?\favicon.ico">

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

                <h2 id="name">Team GM</h2>

                <div id="rating">
                    <img src="images/star.png" width="500" height="472" alt="Star" />
                    <img src="images/star.png" width="500" height="472" alt="Star" />
                    <img src="images/star.png" width="500" height="472" alt="Star" />
                    <img src="images/star.png" width="500" height="472" alt="Star" />
                    <img src="images/star.png" width="500" height="472" alt="Star" />
                </div>

                <div id="availabilityToggle" class="toggle-modern"></div>
                <hr/>

                <h3>Skills</h3>
                <form id="skillForm" action="#" method="get">
                    <input class="Checkbox" type="checkbox" id="word" value="Microsoft Word" />
                    <label for="word">Microsoft Word</label>
                    <br/>

                    <input class="Checkbox" type="checkbox" id="outlook" value="Microsoft Outlook" />
                    <label for="outlook">Microsoft Outlook</label>
                    <br/>

                    <input class="Checkbox" type="checkbox" id="powerpoint" value="Microsoft PowerPoint" />
                    <label for="powerpoint">Microsoft PowerPoint</label>
                    <br/>

                    <input class="Checkbox" type="checkbox" id="IE" value="Internet Explorer" />
                    <label for="IE">Internet Explorer</label>
                    <br/>

                    <input class="Checkbox" type="checkbox" id="skype" value="Skype for Business" />
                    <label for="skype">Skype for Business</label>
                    <br/>

                    <input type="submit" id="saveButton" value="Save" />

                </form>
            </div>


            <div id="calendarFeedbackBox">
                <div id="calendar">
                    <h3>Calendar Sync</h3>
                </div>


                <div id="feedbackBox">
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
        </div>


        <footer>
            <p>&copy; Team GM - Spring 2016</p>
        </footer>
    </div>
</body>
</html>