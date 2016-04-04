$(document).ready(function($) {

    // Globals
    var username = $('h2#name')[0].innerHTML;
    var availability;
    var availabilityToggle = $('#availabilityToggle');
    var skillSelect = $('select#skillSelect').chosen();
    var form = $('#skillForm');
    var inputsLength = 0;


    // Initialize availability toggle
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


    // Update the input length variable by number of selected skills
    $('div.chosen-container').click( function() {
        inputsLength = $('ul.chosen-results li').length;
    });


    // Push skill updates to the database
    form.submit( function (event) {
        event.preventDefault();
        var experts = getExpertSkills();

        // Post skills to the user profile
        $.ajax({
            type: "POST",
            url: "server.php",
            data: { username: username, availability: availability, ExpertSkills: experts }
        });

        alert("Your skills have been updated.");
    });


    // Availability status changing
    availabilityToggle.on( 'toggle', function(e, active) {
        var experts = getExpertSkills();

        if (active) {
            $.ajax({
                type: "POST",
                url: "server.php",
                data: { username: username, availability: "1", ExpertSkills: experts }
            });
        }

        else {
            $.ajax({
                type: "POST",
                url: "server.php",
                data: { username: username, availability: "0", ExpertSkills: experts }
            });
        }
    });


    // Leaderboard button redirects to its page
    $('button#leaderboards').click(function() {
        window.location.href = window.location.origin + "/GMWebApp/leaderboard.php";
    });


    // 'Show More Best Feedback' button functionality
    $('button#showMoreBestFeedback').click( function() {
        // Hide button
        $(this).css({
            display: 'none'
        });

        // Make scrollable
        $('div#calendarFeedbackBox div#bestReviews').css({
            overflow: 'auto'
        });

        // Display other feedback
        $('div#calendarFeedbackBox div#bestReviews div.bestReview:nth-child(n+5)').css({
            display: 'block'
        })
    });


    // 'Show More Worst Feedback' button functionality
    $('button#showMoreWorstFeedback').click( function() {
        // Hide button
        $(this).css({
            display: 'none'
        });

        // Make scrollable
        $('div#calendarFeedbackBox div#worstReviews').css({
            overflow: 'auto'
        });

        // Display other feedback
        $('div#calendarFeedbackBox div#worstReviews div.worstReview:nth-child(n+5)').css({
            display: 'block'
        })
    });


    // Retrieve expert skills
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

        // Notification of changes and new state ////
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


    //// Get authorization code ////
    // $.ajax({
    //     type: "GET",
    //     url: "https://login.microsoftonline.com/common/oauth2/v2.0/authorize?" +
    //          "client_id=df5c3f43-84b2-4444-a0e8-3022d364f53b" +
    //          "&response_type=code" +
    //          "&redirect_uri=https%3A%2F%2F35.9.22.109%2FGMWebApp%2Findex.php" +
    //          "&scope=https%3A%2F%2Foutlook.office.com%2Fcalendars.read%20openid",
    //     headers: { 'Access-Control-Allow-Origin' : "https://35.9.22.109", 'Access-Control-Allow-Methods' : 'GET',
    //                'Access-Control-Allow-Headers' : 'Content-Type' },
    //     data: {},
    //
    //     success: function( result ) {
    //         console.log( result );
    //     },
    //
    //     error: function( error ) {
    //         console.log( error );
    //     }
    // });


    //// Post authorization code ////
    //$.ajax({
    //    type: "POST",
    //    url: "https://login.microsoftonline.com/common/oauth2/v2.0/token",
    //    headers: { 'Access-Control-Allow-Origin' : "https://35.9.22.109", 'Access-Control-Allow-Methods' : 'GET',
    //               'Access-Control-Allow-Headers' : 'Content-Type' },
    //    data: { 'client_id' : 'df5c3f43-84b2-4444-a0e8-3022d364f53b', 'redirect_uri' : 'https%3A%2F%2F35.9.22.109%2FGMWebApp%2Findex.php',
    //            'scope' : 'https%3A%2F%2Fgraph.microsoft.com%2Fcalendar.read',
    //            'grant_type' : 'authorization_code', 'client_secret' : '3RtybqY7YYKOnuLsdEzphRP',
    //            'code' : 'OAAABAAAAiL9Kn2Z27UubvWFPbm0gLXn9LFKWPhMC896EBq9EucUXuWY5iQLRnD_Cfp50VWd-TMTw7eCfR7m-DNvOTB5PdftldnYId137rXka9if0weKHJHzr5YWZtn9p24MvQBL-mtoW_AWuvSWJ13p-a4qzvx3vtb22dt_ovxshBbT4jfJ-kYYeCQnSIm7NLf5kpLM7ZV12N8NA4af4hPjTkD81Y4dLvFE_IYPimd_o4HIqFtUKZdv3j6BtlExcjm0dgmZJqZJOdIjJb5W5-8kkm1C3hkTb-NEaoHzubCXFhi-r6XmroU6SV1H-RzNBHOE-SyPm3OssrY8ZbgkU6EzqI5LOO87X7oT4ozMSKLj_SxHEPE029gIxIVnRBX4lMOldb_2fA1UIL2y-BkrvkkXEIdD8YDkT6wyPKLvUYxfV8YN8Ro-pM03S5OmE1Evs_8dR3KFZoQhR4umvfQzwlV5oW9H28fvS9_hNLWDnSPVmzsFEQOaabRM6TFTP9Fs48G7gMqJb2ba4L-y57_CTkeT2iF1kPBnxh_VP2zXRmmWE7iDbtoEZHfDmr3XpMAyKWRibuEeyIAA'
    //    },
    //
    //    success: function( result ) {
    //        console.log( result );
    //    },
    //
    //    error: function( error ) {
    //        console.log( error );
    //    }
    //});


    //// Get Outlook calendar ////
    $.ajax({
        type: "GET",
        url: "https://outlook.office.com/api/v2.0/me/calendar",
        headers: {
            Authorization : 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsIng1dCI6Ik1uQ19WWmNBVGZNNXBPWWlKSE1iYTlnb0VLWSIsImtpZCI6Ik1uQ19WWmNBVGZNNXBPWWlKSE1iYTlnb0VLWSJ9.eyJhdWQiOiJodHRwczovL291dGxvb2sub2ZmaWNlLmNvbSIsImlzcyI6Imh0dHBzOi8vc3RzLndpbmRvd3MubmV0LzIyMTc3MTMwLTY0MmYtNDFkOS05MjExLTc0MjM3YWQ1Njg3ZC8iLCJpYXQiOjE0NTk3NDA3MzgsIm5iZiI6MTQ1OTc0MDczOCwiZXhwIjoxNDU5NzQ0NjM4LCJhY3IiOiIxIiwiYW1yIjpbInB3ZCJdLCJhcHBpZCI6ImRmNWMzZjQzLTg0YjItNDQ0NC1hMGU4LTMwMjJkMzY0ZjUzYiIsImFwcGlkYWNyIjoiMSIsImZhbWlseV9uYW1lIjoiU2Fub2NraSIsImdpdmVuX25hbWUiOiJKZW5uYSBOaWNvbGUiLCJpcGFkZHIiOiI2Ny4xNjcuMTYxLjk1IiwibmFtZSI6IlNhbm9ja2ksIEplbm5hIE5pY29sZSIsIm9pZCI6IjhiMTEyMmJiLWI5ZmUtNDBlMi1iZDhkLTQ5YzAxZWM4Nzc1NiIsIm9ucHJlbV9zaWQiOiJTLTEtNS0yMS0xMzU0NDk4MzMtMjM2NTI5NzIyLTEzMDAzMDU1NjUtMTM1NTgwIiwicHVpZCI6IjEwMDMzRkZGOTI4RjQwMUYiLCJzY3AiOiJDYWxlbmRhcnMuUmVhZCIsInN1YiI6IjBrNGZLT2dGUVBMXzJBSXFiRnh2TjRxNy1XdW1XcXBOaG1aZjhpNHpIYkkiLCJ0aWQiOiIyMjE3NzEzMC02NDJmLTQxZDktOTIxMS03NDIzN2FkNTY4N2QiLCJ1bmlxdWVfbmFtZSI6InNhbm9ja2kxQG1zdS5lZHUiLCJ1cG4iOiJzYW5vY2tpMUBtc3UuZWR1IiwidmVyIjoiMS4wIn0.nB6DaEJfmliaPC3uB-VV-VOPAIQJLVAPMdEXqx8hGtN9zvsylXysQ_PC3RDNnoS7AY5lI6RRpnvxV7QivgvBPG1SJWbdZb2arOnnFSFixPj0i31p7F_uLCSyacuf9eITh0jB4awcayBIs85mxhZ0s1xOo_Vu8fTkQF6IhgH2-pUHTPl-GJvdM0YMFS1Pt8N3BcocHfNAJZJY1tQGCM1zgeGUDigmz8uImPk4zc6gniQLza_q7nmAP-SRfs0GvcJTLmdkb_bq5cefAV4hsuq6ywdvZzkNs6G0lleirYENyYs6Oh7Fx50Pa-W-7oXHWLcnprgtb5xCP9WrC5QGNCwNhw'
        },
        dataType: 'json',

        success: function( result ) {
            console.log( 'Success!' );
            console.log( result );

            // Append calendar data to calendar div
            $('div#calendar').append(
                "Click here to view your calendar" //'" + result.Name + "'"
            );
        },

        error: function( error ) {
            console.log( 'Error...' );
            console.log( error );
        }
    });

});
