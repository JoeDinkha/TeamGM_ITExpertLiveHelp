$(document).ready(function($) {

    //// Globals ////
    var username = $('h2#name')[0].innerHTML;
    var availability;
    var availabilityToggle = $('#availabilityToggle');
    var skillSelect = $('select#skillSelect').chosen();
    var form = $('#skillForm');
    var inputsLength = 0;


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

        // Post skills to the user profile
        $.ajax({
            type: "POST",
            url: "server.php",
            data: { username: username, availability: availability, ExpertSkills: experts }
        });

        alert("Your skills have been updated.");
    });


    //// Availability status changing ////
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


    //// Leaderboard button redirects to its page ////
    $('button#leaderboards').click(function() {
        window.location.href = window.location.origin + "/GMWebApp/leaderboard.php";
    });


    //// 'Show More Best Feedback' button functionality ////
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


    //// 'Show More Worst Feedback' button functionality ////
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


    //// 'Log Out' button functionality ////
    $('#logOut').click( function() {
        window.location.href = 'post/logout-post.php';
    });


    //// Get Outlook API authorization code ////
    $.ajax({
        type: "GET",
        url: "https://login.microsoftonline.com/common/oauth2/v2.0/authorize?" +
             "client_id=df5c3f43-84b2-4444-a0e8-3022d364f53b" +
             "&response_type=code" +
             "&redirect_uri=https%3A%2F%2F35.9.22.109%2FGMWebApp%2Findex.php" +
             "&scope=openid&20https%3A%2F%2Fgraph.microsoft.com%2Fcalendar.read%20",
        headers: { 'Access-Control-Allow-Origin' : "https://35.9.22.109", 'Access-Control-Allow-Methods' : 'GET',
                   'Access-Control-Allow-Headers' : 'Content-Type' },

        success: function( result ) {
            console.log( result );
        },

        error: function( error ) {
            console.log( error );
        }
    });


    //// Post Outlook API authorization code ////
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
            Authorization : 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsIng1dCI6Ik1uQ19WWmNBVGZNNXBPWWlKSE1iYTlnb0VLWSIsImtpZCI6Ik1uQ19WWmNBVGZNNXBPWWlKSE1iYTlnb0VLWSJ9.eyJhdWQiOiJodHRwczovL291dGxvb2sub2ZmaWNlLmNvbSIsImlzcyI6Imh0dHBzOi8vc3RzLndpbmRvd3MubmV0LzIyMTc3MTMwLTY0MmYtNDFkOS05MjExLTc0MjM3YWQ1Njg3ZC8iLCJpYXQiOjE0NTk3OTIzNTQsIm5iZiI6MTQ1OTc5MjM1NCwiZXhwIjoxNDU5Nzk2MjU0LCJhY3IiOiIxIiwiYW1yIjpbInB3ZCJdLCJhcHBpZCI6ImRmNWMzZjQzLTg0YjItNDQ0NC1hMGU4LTMwMjJkMzY0ZjUzYiIsImFwcGlkYWNyIjoiMSIsImZhbWlseV9uYW1lIjoiU2Fub2NraSIsImdpdmVuX25hbWUiOiJKZW5uYSBOaWNvbGUiLCJpcGFkZHIiOiIzNS45LjIyLjE1OSIsIm5hbWUiOiJTYW5vY2tpLCBKZW5uYSBOaWNvbGUiLCJvaWQiOiI4YjExMjJiYi1iOWZlLTQwZTItYmQ4ZC00OWMwMWVjODc3NTYiLCJvbnByZW1fc2lkIjoiUy0xLTUtMjEtMTM1NDQ5ODMzLTIzNjUyOTcyMi0xMzAwMzA1NTY1LTEzNTU4MCIsInB1aWQiOiIxMDAzM0ZGRjkyOEY0MDFGIiwic2NwIjoiQ2FsZW5kYXJzLlJlYWQiLCJzdWIiOiIwazRmS09nRlFQTF8yQUlxYkZ4dk40cTctV3VtV3FwTmhtWmY4aTR6SGJJIiwidGlkIjoiMjIxNzcxMzAtNjQyZi00MWQ5LTkyMTEtNzQyMzdhZDU2ODdkIiwidW5pcXVlX25hbWUiOiJzYW5vY2tpMUBtc3UuZWR1IiwidXBuIjoic2Fub2NraTFAbXN1LmVkdSIsInZlciI6IjEuMCJ9.sqrMytDGM32McxeQKacq6fApwwalTbzfJ17URp96kqkdId3mp3kQFw--zkPSELW8VtA5awxU-YD3TUWr-Gwu5GqeimrPIO2a-J2uDQgrg2qStZcL1rw0cKxKaLScO4mLD6LESFBqrCUCNrjZRc7NA6VL4ju_SN2JPbCn72-4IrOHBMOdVElbnofnxC2rdGgT7B6MrFXaDj12_GXQryJwhBbRU2E3zYj5TrwtwY5LkP6a65sOc9JCdu3v4_itFrVg0tf2xWxWcM8CgXY-F1U6_rVN0cXAwXYr-8o42Jexm8xbYBAFSBg6K3FEjiBMFevGTAooJARAipy_k3WYWRH8eA'
        },
        dataType: 'json',

        success: function( result ) {
            console.log( 'Success!' );
            console.log( result );

            // Append calendar data to calendar div
            $('div#calendar').append(
                "Click here to view your calendar"
            );
        },

        error: function( error ) {
            console.log( 'Error...' );
            console.log( error );
        }
    });

});
