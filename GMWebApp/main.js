$(document).ready( function($) {

    //// Global Vars ////
    var username = $('h2#name')[0].innerHTML;
    var availability;
    var availabilityToggle = $('#availabilityToggle');
    var skillSelect = $('select#skillSelect').chosen();
    var inputs = $('ul.chosen-results li');


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


    var inputsLength = 0;

    $('div.chosen-container').click( function() {
        inputsLength = $('ul.chosen-results li').length;
    });


    //// Push skill updates to database ////
    var form = $('#skillForm');

    form.submit( function (event) {
        event.preventDefault();
        var columns = [];
        inputsLength = $('#skillSelect option').length;

        //// Notification of changes and new state ////
        if($(".toggle-on").hasClass("active")) {
            availability = 1;
        }
        else {
            availability = 0;
        }

        var expertSkills = getExpertSkills();

        // Post skills to the user profile
        $.ajax({
            type: "POST",
            url: "server.php",
            data: { username: username, availability: availability, ExpertSkills: expertSkills }
        });


        alert("Your skills have been updated.");
    });


    //// Notification of changes and new state ////
    availabilityToggle.on( 'toggle', function(e, active) {

        var expertSkills = getExpertSkils();

        if( active ) {
            $.ajax({
                type: "POST",
                url: "server.php",
                data: { username: username, availability: "1", ExpertSkills: expertSkills }
            });
            //alert( 'You are now online and available to help others.' );
        }

        else {
            $.ajax({
                type: "POST",
                url: "server.php",
                data: { username: username, availability: "0", ExpertSkills: expertSkills }
            });
            //alert( 'You are now offline and not available to help others.' );
        }
    });


    //// Leaderboard button re-directs to Leaderboard page ////
    $('button#leaderboards').click( function() {
        window.location.href = window.location.origin + "/GMWebApp/leaderboard.php";
    });



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

        var expertSkills = '';

        for(var y =0; y<columns.length;y++){
            expertSkills = expertSkills.concat(columns[y]);
        }

        return expertSkills;
    }

});
    //// Update skill checkboxes' 'checked' state ////
    //for (var x=0; x < inputs.length; x++){
    //    if (inputs[x].hasClass("result-selected")) {
    //        //inputs[x].addClass("result-selected");
    //    }
    //}

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


