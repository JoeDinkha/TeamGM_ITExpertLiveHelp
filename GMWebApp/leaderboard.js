$(document).ready( function($) {

    //// Global Vars ////
    var scrollBarWidth = 21;


    //// Set login container's height ////
    // Mobile - Set to document height
    if( $(window).width() <= 1313 - scrollBarWidth ) {
        $('div#leaderboard.container').height( $(document).height() );
    }

    // Desktop - Set to window height
    else {
        $('div#leaderboard.container').height( $(window).height() );
    }

    // Adjust on window resize
    $(window).resize( function() {
        if( $(window).width() <= 1313 - scrollBarWidth ) {
            $('div#leaderboard.container').height( $(document).height() );
        }

        else {
            $('div#leaderboard.container').height( $(window).height() );
        }
    });

});
