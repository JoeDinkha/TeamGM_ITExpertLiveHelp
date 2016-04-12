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

        // Open modal
        $('[data-remodal-id=skillsModal]').remodal().open();

        // Post skills to the user profile
        $.ajax({
            type: "POST",
            url: "server.php",
            data: { username: username, availability: availability, ExpertSkills: experts }
        });
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


    //// 'Leaderboard' button functionality ////
    $('button#leaderboard').click(function() {
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
    
    
    //// "Add to Calendar" widget functionality ////
    (function () {
        if (window.addtocalendar)if(typeof window.addtocalendar.start == "function")return;
        if (window.ifaddtocalendar == undefined) { window.ifaddtocalendar = 1;
            var d = document, s = d.createElement('script'), g = 'getElementsByTagName';
            s.type = 'text/javascript';s.charset = 'UTF-8';s.async = true;
            s.src = ('https:' == window.location.protocol ? 'https' : 'http')+'://addtocalendar.com/atc/1.5/atc.min.js';
            var h = d[g]('body')[0];h.appendChild(s); }
    })();


    // //// Get Outlook API authorization code - PHP ////
    // $.ajax({
    //     type: "GET",
    //     url: "outlook_api_calls.php/getLoginUrl(https://35.9.22.109/GMWebApp/index.php)",
    //
    //     success: function( result ) {
    //         console.log( 'Get Code - Success!' );
    //         console.log( result );
    //     },
    //
    //     error: function() {
    //         console.log( 'Get Code - Error...' );
    //     }
    // });
    //
    //
    // //// Exchange Outlook API authorization code for access token - PHP ////
    // $.ajax({
    //    type: "POST",
    //    url: "outlook_api_calls.php/getTokenFromAuthCode(OAAABAAAAiL9Kn2Z27UubvWFPbm0gLRNRYbPIhAS3RNC8GZ5KS69XJ6OQLluBYP0b7yCxu7NJXe5fFgUrWyr0CD85nGgLhJRwzdcTV8XQb2DF7KvlMzuYn8PrHQY_bVeDc4C0zc2wogylDYBOrTkumPZcwskxdwa87gFq27mrB1oZh7CaauN_GIxNIfO1nmib0nLhrFiVsyoeFxRMEqy1B5HHq1ZBOmzVyKx_zkQTPag0CoWxmOshtsT6w-05mmR9lYxvzieOw4neAtOxdFGJqnQVf15aQ6m-h7DXHuqPhc3PAAG_bHrTXNCzlwpCkchepwTyblWBYcj01FfI7jhzlFSjCgHwAlTx8vnI1mZnr8XUMp-OrzCADi3hNsJJqhK2wMiFITrJsEeQKHnjMdnOCsDm3v30OHolKKSLmBgz5U6_3jpB-yszz7nJWJlYr_T0zHqfAf-y6jmXX3naSnPUTyIrCwPn2NhB7iZLeQaGHQ965ef37U9THjwMHosNxvut4MJl9PucHx28HS-zOMiYXCendmMj-XVoVoMXp4ZIcZbgtf32ej4ph8sVFPpkiULzWOk7PGsp_Eu9ohg_Jk_IN8XtpkR05AzQWuOu0S8_s4cn90JKs3UgAA," +
    //          "https://35.9.22.109/GMWebApp/index.php)",
    //
    //    success: function( result ) {
    //        console.log( 'Get Token - Success!' );
    //    },
    //
    //    error: function( error ) {
    //        console.log( 'Get Token - Error...' );
    //    }
    // });
    //
    //
    // //// Get Outlook calendar events - PHP ////
    // $.ajax({
    //     type: "GET",
    //     url: "getEventsForDate(Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsIng1dCI6Ik1uQ19WWmNBVGZNNXBPWWlKSE1iYTlnb0VLWSIsImtpZCI6Ik1uQ19WWmNBVGZNNXBPWWlKSE1iYTlnb0VLWSJ9.eyJhdWQiOiJodHRwczovL291dGxvb2sub2ZmaWNlLmNvbSIsImlzcyI6Imh0dHBzOi8vc3RzLndpbmRvd3MubmV0LzIyMTc3MTMwLTY0MmYtNDFkOS05MjExLTc0MjM3YWQ1Njg3ZC8iLCJpYXQiOjE0NjA0MjQ5NDYsIm5iZiI6MTQ2MDQyNDk0NiwiZXhwIjoxNDYwNDI4ODQ2LCJhY3IiOiIxIiwiYW1yIjpbInB3ZCJdLCJhcHBpZCI6ImRmNWMzZjQzLTg0YjItNDQ0NC1hMGU4LTMwMjJkMzY0ZjUzYiIsImFwcGlkYWNyIjoiMSIsImZhbWlseV9uYW1lIjoiRGlua2hhIiwiZ2l2ZW5fbmFtZSI6IkpvZSBKb2huIiwiaXBhZGRyIjoiMzUuOS4yMi4xNTkiLCJuYW1lIjoiRGlua2hhLCBKb2UgSm9obiIsIm9pZCI6IjY1YzkwNGEyLTJkMzYtNGFhNi04ZWRlLTQ5MmJiMGNkYWJmNSIsIm9ucHJlbV9zaWQiOiJTLTEtNS0yMS0xMzU0NDk4MzMtMjM2NTI5NzIyLTEzMDAzMDU1NjUtMTEzMjM1IiwicHVpZCI6IjEwMDNCRkZEOTI4NjQ1RTEiLCJzY3AiOiJDYWxlbmRhcnMuUmVhZCIsInN1YiI6IjdvSDRuQVFweDRMWHlpTkpvQXNHdkN1WXlRTW1QWFRlRFlxWmNHTEpVOFkiLCJ0aWQiOiIyMjE3NzEzMC02NDJmLTQxZDktOTIxMS03NDIzN2FkNTY4N2QiLCJ1bmlxdWVfbmFtZSI6ImRpbmtoYWpvQG1zdS5lZHUiLCJ1cG4iOiJkaW5raGFqb0Btc3UuZWR1IiwidmVyIjoiMS4wIn0.sokcftIPHP_m319aQ0RLp2j6xeeQ0P89CS_MZFAU60UCmBw4Me4DM75C9w16Z2UNus8YxCI2N_oG68BNmgKREilhs2fW9nwc_sz1AuhXNiQy7x9rnkfCgr-6HezZn-tMBcwlq905sjaDoH9abCWsiNQH9EtHOkOZLQJWbrtEpWfxS6fxFSt6wwDclSwpuhqY7PikcNiLkn9RWGbjGuZt5ca274UkqDAlyVSfrwHG6AcFFW9cBuBP_GGFkHVnqc4wojq8q7JtTcz_dgh5-sLojtfZP8mCVq796vUvcOXGl1plDlAwJc0UyEKKM8gLI0wCkqss9LKb8n1AB3hv1yE5nA, " +
    //          "2016-04-01T01:00:00)",
    //          //"startDateTime=2016-04-01T01:00:00&endDateTime=2016-05-01T23:00:00"
    //
    //     success: function( result ) {
    //         console.log( 'Get Calendar - Success!' );
    //         console.log( result );
    //
    //         if( result.value[0].Subject == "IT Expert Live Help: Office Hours" ) {
    //             console.log("\nSubject = " + result.value[0].Subject);
    //             console.log("Start DateTime = " + result.value[0].Start.DateTime);
    //             console.log("End DateTime = " + result.value[0].End.DateTime);
    //         }
    //     },
    //
    //     error: function( error ) {
    //         console.log( 'Get Calendar - Error...' );
    //         console.log( error );
    //     }
    // });
    //
    //
    //// Refresh access token - PHP ////
    // $.ajax({
    //     type: "POST",
    //     url: "outlook_api_calls/getTokenFromRefreshToken(MCYWfBo3Q7kHPdGaOTJMfHwLbiD47gvqevwB4i4rqSl8ZyTqw6S2*kpdVtv6R7O1qV95yB0kBtmmxxxrXVnyAeKn2bNJ7DGFkcXlAqfbYfuenm08m7UGUgpNtIo5KhTA7LHGxU6dqBpuVtY7vDQkrlBrLHFCTUHTAP6Mtz*hSo7IddyaWcFvgGh44XqFNKGivqtt6kMtgCnB*1RRpKaV5Abe23tFCiyXGd66dn0DVHeYIBkysby6pyqimeV7aAIX4mhqAy3kJOx2hG80i!NbMxl6iHXVsHF9CxgVoIJR7SlD7fN3gPouCV!S4eLKxYdhL8T8mTspthqXXviSYVlUnCmI6hjS6UFXtuZUIJLPx8IXJFvT3V1WVrZUZJR3C7OYctMxxlSjKblilaOAMUl1g8hx2fdHaN4eD5Xlp3tWqp46f)",
    //
    //     success: function( result ) {
    //         console.log( 'Refresh Token - Success!' );
    //         console.log( result );
    //     },
    //
    //     error: function( error ) {
    //         console.log( 'Refresh Token - Error...' );
    //     }
    // });


    //// Get Outlook API authorization code - JS ////
    // $.ajax({
    //     type: "GET",
    //     url: "https://login.microsoftonline.com/common/oauth2/v2.0/authorize?" +
    //          "client_id=df5c3f43-84b2-4444-a0e8-3022d364f53b" +
    //          "&response_type=code" +
    //          "&redirect_uri=https%3A%2F%2F35.9.22.109%2FGMWebApp%2Findex.php" +
    //          "&scope=https%3A%2F%2Fgraph.microsoft.com%2Fcalendars.read%20openid%20offline_access",
    //     headers: { 'Access-Control-Allow-Origin' : "https://35.9.22.109", 'Access-Control-Allow-Methods' : 'GET',
    //         'Access-Control-Allow-Headers' : 'Content-Type' },
    //
    //     success: function( result ) {
    //         console.log( result );
    //     },
    //
    //     error: function( error ) {
    //         console.log( error );
    //     }
    // });


    //// Get Outlook calendar events - JS ////
    var accessToken = 'EwBwAul3BAAUo4xeBIbHjhBxWOFekj4Xy2fhaGQAAX/SyNL9STSoqltIvbBmuXkOENfy3+o7mEh505d7izOkz9OW3BjjQOHpzzH2Op1n4nZNhwATV2Kb/SakR3wzwU0mQLETPk2NsNr7cxft+zpfVt74J/IPdNPHqUuRZUQaXCUrlzfmH0UKLhO+zewDm5N/t0wUhufg3BUQIzXQdzUFikE9NgckkJEkx4gjerx9B5yHmxkO5DeGJQGC9aZX8tla9v23U0PoXXZPMp6fGZC9Pr7QAr1JT8HU5MMH6Lg4k0g2bUTjSn0HmFMg3sdWd9ScySPIMeP1/6UcniTVri5M2pL/acz2KMmMhhO7Fdv8cH2PL81h+rOh0/nsKpfKVUUDZgAACARZLZ1imET/QAFZYfU7NGQcpt+24gdoK5P4M5e3Pj9Y/Yj8tzultRIXmwn3ORocrhmqjaqDv94x389kOyMcO9PMb9ERmei67+67WriHOnBC+wneXZh5+YfCvVEvkVUF69XfswttNB9Bim5QBgFSd6LYgESHMOpUFj8+UtcJ5BCZbSOFkv9PJSvlcolMNt0kQabVZ6Gys4BAvU+3rfsh9jiQpgHGzIzglG7IDhdV/bHkvjA2ZPft+SEo0CdBXBbGMQt6trY1TtKjFA+LiNUGAKLnqoU99gWOKugSwi8d4zoq9alAMimEwkw4zg7QpIW6VCKiAP7/kdGlXV/d1t80t1zkMJeQD4vYeDcLAgVC0qbyJlMe/4MM0Z6mTc2xrRN/L8JHK5I3AErgNlrdelGRQ8z8lEVl0Ny/19g1YjeBn1GhRCXWLpX2O+zuOGkB';

    $.ajax({
        type: "GET",
        url: "https://outlook.office.com/api/v2.0/me/events?$select=Subject,Organizer,Start,End",
        headers: {
            Authorization: 'Bearer ' + accessToken
        },
        dataType: 'json',

        success: function( result ) {
            console.log( 'Get Calendar - Success!' );
            console.log( result );

            for(var i = 0; i < result.value.length; i++) {
                if (result.value[i].Subject == "Office Hours") {
                    console.log("\nSubject = " + result.value[i].Subject);
                    console.log("Start DateTime = " + result.value[i].Start.DateTime);
                    console.log("End DateTime = " + result.value[i].End.DateTime);

                }
            }
        },

        error: function( error ) {
            console.log( 'Get Calendar - Error...' );
            console.log( error );
        }
    });


    //// Refresh access token - JS ////
    var refreshToken = 'MCYWfBo3Q7kHPdGaOTJMfHwLbiD47gvqevwB4i4rqSl8ZyTqw6S2*kpdVtv6R7O1qV95yB0kBtmmxxxrXVnyAeKn2bNJ7DGFkcXlAqfbYfuenm08m7UGUgpNtIo5KhTA7LHGxU6dqBpuVtY7vDQkrlBrLHFCTUHTAP6Mtz*hSo7IddyaWcFvgGh44XqFNKGivqtt6kMtgCnB*1RRpKaV5Abe23tFCiyXGd66dn0DVHeYIBkysby6pyqimeV7aAIX4mhqAy3kJOx2hG80i!NbMxl6iHXVsHF9CxgVoIJR7SlD7fN3gPouCV!S4eLKxYdhL8T8mTspthqXXviSYVlUnCmI6hjS6UFXtuZUIJLPx8IXJFvT3V1WVrZUZJR3C7OYctMxxlSjKblilaOAMUl1g8hx2fdHaN4eD5Xlp3tWqp46f';

    $.ajax({
        type: "POST",
        url: "https://login.microsoftonline.com/common/oauth2/v2.0/token",
        data: {
            'client_id': 'df5c3f43-84b2-4444-a0e8-3022d364f53b',
            'scope': 'openid https://outlook.office.com/calendars.read offline_access',
            'refresh_token': refreshToken,
            'redirect_uri': 'https://35.9.22.109/GMWebApp/index.php',
            'grant_type': 'refresh_token', 'client_secret': 'APfpVLBgOLKhNrD7W1SpuXR}'
        },

        success: function( result ) {
            console.log( 'Refresh Token - Success!' );
            console.log( result );
        },

        error: function( error ) {
            console.log( 'Refresh Token - Error...' );
            console.log( error );
        }
    });

});
