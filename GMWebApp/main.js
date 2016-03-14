$(document).ready( function($) {

    //// Global Vars ////
    var username = $('h2#name')[0].innerHTML;
    var availability;
    var availabilityToggle = $('#availabilityToggle');
    var inputs = $('ul.chosen-results li');
    var skillSelect = $('select#skillSelect').chosen();


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


    //// Update skill checkboxes' 'checked' state ////
    //for (var x=0; x < inputs.length; x++){
    //    if (inputs[x].hasClass("result-selected")) {
    //        //inputs[x].addClass("result-selected");
    //    }
    //}

    var inputsLength=0;

    $('div.chosen-container').click( function() {
        inputsLength = $('ul.chosen-results li').length;
        console.log(inputsLength);
    });


    //// Push skill updates to database ////
    var form = $('#skillForm');

    form.submit( function (event) {
        event.preventDefault();
        var columns = [];
        inputsLength = $('#skillSelect option').length;
        console.log('length: ',inputsLength);
        alert("Your skills have been updated.")

        if (inputsLength == 0){
            for (var x=0; x<5; x++){
                columns.push("0");
            }
        }

        console.log(inputsLength);
        for (var i = 1; i <= inputsLength-1; i++) {
            if ($('#skillSelect option:nth-child('+i+')').is(':selected')){
                columns.push("1");
            }
            else {
                columns.push("0");
                console.log( i + " - False");
            }
        }

        //// Notification of changes and new state ////
        if($(".toggle-on").hasClass("active")) {
            availability = 1;
        }
        else {
            availability = 0;
        }

        console.log( "Available = " + availability );

        console.log(columns);
        var experts = '';

        for(var y =0; y<columns.length;y++){
            experts = experts.concat(columns[y]);
        }
        console.log(experts);

        // Post skills to the user profile
        $.ajax({
            type: "POST",
            url: "server.php",
            data: { username: username, availability: availability, ExpertSkills: experts }
        });


        //alert("Your skills have been updated!");
    });


    //// Notification of changes and new state ////
    availabilityToggle.on( 'toggle', function(e, active) {
        var columns = [];
        inputsLength = $('#skillSelect option').length;
        console.log('length: ',inputsLength);

        if (inputsLength == 0){
            for (var x=0; x<5; x++){
                columns.push("0");
            }
        }

        console.log(inputsLength);
        for (var i = 1; i <= inputsLength-1; i++) {
            if ($('#skillSelect option:nth-child('+i+')').is(':selected')){
                columns.push("1");
            }
            else {
                columns.push("0");
                console.log( i + " - False");
            }
        }


        console.log( "Available = " + availability );

        console.log(columns);
        var experts = '';

        for(var y =0; y<columns.length;y++){
            experts = experts.concat(columns[y]);
        }

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
                data: { username: username, availability: "1", ExpertSkills: experts }
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
                data: { username: username, availability: "0", ExpertSkills: experts }
            });
            //**********************END******************************

            //alert( 'You are now offline and not available to help others.' );
        }
    });


    //// Leaderboard button re-directs to Leaderboard page ////
    $('button#leaderboards').click( function() {
        window.location.href = window.location.origin + "/GMWebApp/leaderboard.php";
    });


    //function checkRefresh()
    //{
    //    if( $('#refreshForm').visited = "")
    //    {
    //        // This is a fresh page load
    //        $('#refreshForm').visited = "1"
    //
    //        // You may want to add code here special for
    //        // fresh page loads
    //        if(availabilityToggle.on)
    //        {
    //            availabilityToggle.toggle({easing: linear, animate: 0});
    //            availabilityToggle.toggle({text: {on:'Online', off:'Offline'}});
    //        }
    //        else
    //        {
    //            availabilityToggle.toggle({easing: linear, animate: 0});
    //            availabilityToggle.toggle({text: {on:'Online', off:'Offline'}});
    //        }
    //
    //    }
    //    else
    //    {
    //        // This is a page refresh
    //
    //        // Insert code here representing what to do on
    //        // a refresh
    //
    //        if(availabilityToggle.on)
    //        {
    //            availabilityToggle.toggle({easing: linear, animate: 0});
    //            availabilityToggle.toggle({text: {on:'Online', off:'Offline'}});
    //        }
    //        else
    //        {
    //            availabilityToggle.toggle({easing: linear, animate: 0});
    //            availabilityToggle.toggle({text: {on:'Online', off:'Offline'}});
    //        }
    //    }
    //}
    //
    //$(window).preload = function(event) {
    //    checkRefresh();
    //};

});
