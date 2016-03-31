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
    $.ajax({
        type: "GET",
        url: "https://login.microsoftonline.com/common/oauth2/v2.0/authorize?" +
             "client_id=df5c3f43-84b2-4444-a0e8-3022d364f53b" +
             "&response_type=code" +
             "&redirect_uri=https%3A%2F%2F35.9.22.109%2FGMWebApp%2Findex.php" +
             "&scope=openid&20https%3A%2F%2Fgraph.microsoft.com%2Fcalendar.read%20",
        headers: { 'Access-Control-Allow-Origin' : "https://35.9.22.109", 'Access-Control-Allow-Methods' : 'GET',
                   'Access-Control-Allow-Headers' : 'Content-Type' },
        data: {},

        success: function( result ) {
            console.log( result );
        },

        error: function( error ) {
            console.log( error );
        }
    });


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
    //$.ajax({
    //    type: "GET",
    //    url: "https://outlook.office.com/api/v2.0/me/calendarview",
    //    header: { Prefer: "outlook.timezone='Eastern Standard Time'", 'Access-Control-Allow-Origin': "https://35.9.22.109" },
    //    data: { 'client_id' : 'df5c3f43-84b2-4444-a0e8-3022d364f53b', 'return_client_request_id' : true,
    //            'Authorization' : 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImtpZCI6Ik1uQ19WWmNBVGZNNXBPWWlKSE1iYTlnb0VLWSJ9.eyJhdWQiOiJkZjVjM2Y0My04NGIyLTQ0NDQtYTBlOC0zMDIyZDM2NGY1M2IiLCJpc3MiOiJodHRwczovL2xvZ2luLm1pY3Jvc29mdG9ubGluZS5jb20vMjIxNzcxMzAtNjQyZi00MWQ5LTkyMTEtNzQyMzdhZDU2ODdkL3YyLjAiLCJpYXQiOjE0NTkzNjc2MTAsIm5iZiI6MTQ1OTM2NzYxMCwiZXhwIjoxNDU5MzcxNTEwLCJpcGFkZHIiOiIzNS45LjIyLjE1OSIsInN1YiI6ImJxeWx6VTltTXpQZzVJZzY2UWlRc0hRNExqTUhJSGpLdTJmbkFXazVuUGMiLCJ0aWQiOiIyMjE3NzEzMC02NDJmLTQxZDktOTIxMS03NDIzN2FkNTY4N2QiLCJ2ZXIiOiIyLjAifQ.RlES4gmM3XvO3bE3HEGlQDS9XYfqj0dwW59c_pgiOljpAJZKwGsLDys6xkiI9Bwso6_xECEUMkL_EIeFWCs41cXMB7-cCAJZ8yOM64bOF2lsuOcfJM2aYCnDubArqvJDLuu5ifdMoMYOmwxi0aU0DDrVtSx5EKEv2lIWig9m8ZO_K5VpHrUEwCcf5n7KwXX29o0IxIzCx1j5z0o3II8ncZB62D6-nVh8GK-sAftmTOSdS68K76DVFUe2CeSdjv8kVx6VoI-Z5u1GdVxxDKbolhujf6hkHgdUa6yjXIVwyCjCgpaQIq7m0uwbYoDHCfT_4Gv7WWq8tPI5nkgLGtq-Rw' },
    //
    //    success: function( result ) {
    //        console.log( result );
    //    },
    //
    //    error: function( error ) {
    //        console.log( error );
    //    }
    //});
});

//?startDateTime=2016-03-01T01:00:00&endDateTime=2016-04-31T23:00:00