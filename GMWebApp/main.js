$(document).ready(function($) {

    //// Globals ////
    var username = $('h2#name')[0].innerHTML;
    var availability;
    var availabilityToggle = $('#availabilityToggle');

    var skillSelect = $('select#skillSelect').chosen();
    var form = $('#skillForm');
    var override = $('#overrideCheckbox')[0].checked;

    var inputsLength = 0;
    var start_array = [];
    var end_array = [];

    if (override == true){
        overrideSwitch();
    }


    //// Initialize availability toggle ////
    availabilityToggle.toggles({
        drag: true,         // allow dragging the toggle between positions
        click: true,        // allow clicking on the toggle

        text: {
            on: 'Online',   // text for the ON position
            off: 'Offline'  // text for the OFF position
        },

        on: false,          // is the toggle ON on init
        animate: 250,       // animation time (ms)
        easing: 'swing',    // animation transition easing function

        checkbox: null,     // the checkbox to toggle (for use in forms)
        clicker: null,      // element that can be clicked on to toggle; removes binding from the toggle itself (use nesting)

        width: 150,         // width used if not set in css
        height: 40,         // height if not set in css
        type: 'compact'     // if this is set to 'select' then the select style toggle will be used
    });


    //// Update the input length variable by number of selected skills ////
    $('div.chosen-container').click( function() {
        inputsLength = $('ul.chosen-results li').length;
    });


    //// Push skill updates to the database ////
    form.submit( function (event) {
        event.preventDefault();
        var experts = getExpertSkills();

        // Open modal
        $('[data-remodal-id=skillsModal]').remodal().open();

        // Post skills to the user profile
        $.ajax({
            type: "POST",
            url: "server.php",
            data: { username: username, availability: availability, override: override, ExpertSkills: experts }
        });
    });


    //// Availability status changing ////
    availabilityToggle.on( 'toggle', function(e, active) {
        var experts = getExpertSkills();

        //update variables in the database, post to server.php page to handle query
        if (availability) {
            $.ajax({
                type: "POST",
                url: "server.php",
                data: { username: username, availability: "1", override: "1", ExpertSkills: experts }
            });
        }

        else {
            $.ajax({
                type: "POST",
                url: "server.php",
                data: { username: username, availability: "0", override: "1", ExpertSkills: experts }
            });
        }
    });


    //// 'Leaderboard' Button Functionality ////
    $('button#leaderboard').click(function() {
        window.location.href = window.location.origin + "/GMWebApp/leaderboard.php";
    });


    //// Override Checkbox Functionality ////
    $('input#overrideCheckbox').click( function() {
        override = $('#overrideCheckbox')[0].checked;
        var experts = getExpertSkills();

        //post to server.php, server hands all of the queries to update the database
        if (this.checked) {
            $.ajax({
                type: "POST",
                url: "server.php",
                data: { username: username, availability: "1", override: override, ExpertSkills: experts }
            });
        }

        else {
            $.ajax({
                type: "POST",
                url: "server.php",
                data: { username: username, availability: "0", override: override, ExpertSkills: experts }
            });
        }

        availabilityToggle.toggle( availability );
    });


    //// 'Show More Best Feedback' button ////
    $('button#showMoreBestFeedback').click( function() {

        if( $(this).text() == 'Show More Feedback' ) {
            // Change button text
            $(this).text( 'Show Less Feedback' );

            // Change button positioning
            $(this).css({
                position: 'static',
                'margin-bottom': 15 + 'px'
            });

            // Make scrollable
            $('div#calendarFeedbackBox div#bestReviews').css({
                overflow: 'auto'
            });

            // Display other feedback
            $('div#calendarFeedbackBox div#bestReviews div.bestReview:nth-child(n+5)').css({
                display: 'block'
            })
        }

        else if( $(this).text() == 'Show Less Feedback' ) {
            // Change button text
            $(this).text( 'Show More Feedback' );

            // Change button positioning
            $(this).css({
                position: 'absolute',
                'margin-bottom': 25 + 'px'
            });

            // Make un-scrollable
            $('div#calendarFeedbackBox div#bestReviews').css({
                overflow: 'hidden'
            });

            // Hide other feedback
            $('div#calendarFeedbackBox div#bestReviews div.bestReview:nth-child(n+5)').css({
                display: 'none'
            })
        }
    });


    //// 'Show More Worst Feedback' button ////
    $('button#showMoreWorstFeedback').click( function() {

        if( $(this).text() == 'Show More Feedback' ) {
            // Change button text
            $(this).text(
                'Show Less Feedback'
            );

            // Change button positioning
            $(this).css({
                position: 'static',
                'margin-bottom': 15 + 'px'
            });

            // Make scrollable
            $('div#calendarFeedbackBox div#worstReviews').css({
                overflow: 'auto'
            });

            // Display other feedback
            $('div#calendarFeedbackBox div#worstReviews div.worstReview:nth-child(n+5)').css({
                display: 'block'
            })
        }

        else if( $(this).text() == 'Show Less Feedback' ) {
            // Change button text
            $(this).text(
                'Show More Feedback'
            );

            // Change button positioning
            $(this).css({
                position: 'absolute',
                'margin-bottom': 25 + 'px'
            });

            // Make un-scrollable
            $('div#calendarFeedbackBox div#worstReviews').css({
                overflow: 'hidden'
            });

            // Hide other feedback
            $('div#calendarFeedbackBox div#worstReviews div.worstReview:nth-child(n+5)').css({
                display: 'none'
            })
        }
    });


    //// 'Log Out' button functionality ////
    $('#logOut').click( function() {
        window.location.href = 'post/logout-post.php';
    });


    //// "Add to Calendar" widget functionality ////
    (function () {
        if (window.addtocalendar)if(typeof window.addtocalendar.start == "function")return;
        if (window.ifaddtocalendar == undefined) { window.ifaddtocalendar = 1;
            var d = document, s = d.createElement('script'), g = 'getElementsByTagName';
            s.type = 'text/javascript';s.charset = 'UTF-8';s.async = true;
            s.src = ('https:' == window.location.protocol ? 'https' : 'http')+'://addtocalendar.com/atc/1.5/atc.min.js';
            var h = d[g]('body')[0];h.appendChild(s); }
    })();


    //// Refresh the Access Token ////
    $.ajax({
        type: "POST",
        url: "refresh_api.php",
        data: { username: username },

        success: function( result ) {
            //console.log("Refresh token worked!");
            //console.log(result);
            if (result){
                var outputStr = result.split(',');
                var accessTokenLocated = outputStr[3].split(':');
                var resultAccessToken = accessTokenLocated[1];

                authorizeCalendar(resultAccessToken);
            }
        },

        error: function() {
            console.log("Error occurred while trying to refresh the access token.")
        }
    });


    //// Override switch function on refresh (pulls value from database) ////
    function overrideSwitch() {
        override = $('#overrideCheckbox')[0].checked;
        availabilityToggle.toggle( this.checked );
    }


    //// Authorizes use of Outlook API with refresh token ////
    function authorizeCalendar(token) {
        var start = '';
        var end = '';

        $.ajax({
            type: "GET",
            url: "https://outlook.office.com/api/v2.0/me/events?$select=Subject,Organizer,Start,End",
            headers: {
                Authorization: 'Bearer ' + token
            },
            dataType: 'json',

            success: function (result) {
                //console.log('Get Calendar - Success!');
                //console.log(result);

                for (var i = 0; i < result.value.length; i++) {
                    if (result.value[i].Subject == "Office Hours") {
                        start = result.value[i].Start.DateTime;
                        end = result.value[i].End.DateTime;

                        var startDate = new Date(start);
                        startDate.setHours(startDate.getHours() - 4);

                        var endDate = new Date(end);
                        endDate.setHours(endDate.getHours() - 4);

                        start_array.push(startDate.toISOString());
                        end_array.push(endDate.toISOString());
                        //console.log("\nSubject = " + result.value[i].Subject);
                        //console.log("Start DateTime = " + result.value[i].Start.DateTime);
                        //console.log("End DateTime = " + result.value[i].End.DateTime);
                    }
                }
                pushHours();
            },

            error: function( error ) {
                console.log( 'Get Calendar - Error...' );
                console.log( error );
            }
        });
    }

    function pushHours() {
        $.ajax({
            type: "POST",
            url: "add_hours.php",
            data: { expert: username, in_time: start_array, out_time: end_array }
        });
    }


    //// Retrieve expert skills ////
    function getExpertSkills() {
        var columns = [];
        inputsLength = $('#skillSelect option').length;

        if (inputsLength == 0){
            for (var x=0; x<5; x++){
                columns.push("0");
            }
        }

        for (var i = 1; i <= inputsLength-1; i++) {
            if ($('#skillSelect option:nth-child('+i+')').is(':selected')){
                columns.push("1");
            }
            else {
                columns.push("0");
            }
        }

        // Notification of changes and new state
        if($(".toggle-on").hasClass("active")) {
            availability = 1;
        }
        else {
            availability = 0;
        }

        var experts = '';

        for(var y =0; y<columns.length;y++){
            experts = experts.concat(columns[y]);
        }

        return experts
    }

});
