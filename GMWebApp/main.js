$(document).ready( function() {

    $('#availabilityToggle').toggles({
        drag: true,         // allow dragging the toggle between positions
        click: true,        // allow clicking on the toggle

        text: {
            on: 'Online',   // text for the ON position
            off: 'Offline'  // and off
        },

        on: false,          // is the toggle ON on init
        animate: 250,       // animation time (ms)
        easing: 'swing',    // animation transition easing function

        checkbox: null,     // the checkbox to toggle (for use in forms)
        clicker: null,      // element that can be clicked on to toggle. removes binding from the toggle itself (use nesting)

        width: 150,         // width used if not set in css
        height: 40,         // height if not set in css
        type: 'compact'     // if this is set to 'select' then the select style toggle will be used
    });


    // Getting notified of changes, and the new state:
    $('#availabilityToggle').on('toggle', function(e, active) {
        if (active) {
            alert('You are now online and available to help others.');
        }

        else {
            alert('You are now offline and not available to help others.');
        }
    });

});