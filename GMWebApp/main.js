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
});
