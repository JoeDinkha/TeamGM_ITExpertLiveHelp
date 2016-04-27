<?php
/**
 * Author: Zack Keith
 */



//Email information
$admin_email = "noreplyGMITLiveHelp@gmail.com";
$email = "priceja7@msu.edu";
$subject = "IT Expert Live Help: Availability Notice";
$comment = "Dear User,\n
You were marked unavailable when a user tried to reach you for help.
Your office hours have been overridden and you are now set to offline.\n
Thanks, \n
GM IT Expert Live Help
";

//send email
mail($email, "$subject", $comment, "From:" . $admin_email);


?>
