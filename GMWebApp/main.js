$(document).ready( function($) {

    var availabilityToggle = $('#availabilityToggle');
    var username = $('h2')[0].innerHTML;

    var global_inputs = document.getElementsByTagName('input');

    for (var x=0; x < global_inputs.length-1; x += 1){
        console.log(global_inputs[x].checked);
        if (global_inputs[x].name == "1"){
            global_inputs[x].checked = true;
        }
    }



    $('#saveButton').click (function (event) {
        var inputs = document.getElementsByTagName('input');
        var columns = [];
        for (var i = 0; i < inputs.length-1; i += 1) {
            if(inputs[i].checked) {
                columns.push("1");
            }
            else {
                columns.push("0");
            }
        }
        console.log(username);
        // Post skills to the user profile
        $.ajax({
            type: "POST",
            url: "server.php",
            data: { username: username, SkillWord: columns[0], SkillOutlook: columns[1], SkillPowerPoint: columns[2], SkillExplorer: columns[3], SkillSkype: columns[4] }
        });

        alert("Your skills have been updated!")
    });


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


    // Notification of changes and new state
    availabilityToggle.on( 'toggle', function(e, active) {
        if( active ) {


            //************ AUTHOR: Jacob Price  *********************
            //Ajax call to post to server.php
            //This function posts the data with the specified username as available
            //username is hardcoded for now
            //or not available
            //if available, set flag in database to 1 (true)
            $.ajax({
                type: "POST",
                url: "server.php",
                data: { username: username, availability: "1" }
            });
            //**********************END******************************


            //alert( 'You are now online and available to help others.' );

        }

        else {

            //************ AUTHOR: Jacob Price  *********************
            //Ajax call to post to server.php
            //This function posts the data with the specified username as available
            //username is hardcoded for now
            //or not available
            //if available, set flag in database to 0 (false)
            $.ajax({
                type: "POST",
                url: "server.php",
                data: { username: username, availability: "0" }
            });
            //**********************END******************************

            //alert( 'You are now offline and not available to help others.' );

        }
    });



});
