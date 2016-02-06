<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>GM | IT Expert Live Help - Login</title>
    <link rel="stylesheet" type="text/css" href="login.css" />
    <link rel="shortcut icon" href="?\favicon.ico">

</head>
<body>
<div id="wrapper">
    <div class="container" id="header">
    <header>
        <img src="images/Logo_of_General_Motors.png" width="100" height="100" alt="General Motors Logo" /><h1>IT Expert Live Help</h1>
    </header>
        </div>
    <div id="content">
<form role="form" method="post" action="post/login-post.php" autocomplete="off" id="slick-login">
    <div class="username">
        <input type="text" name="username" id="username" class="form-control input-lg" placeholder="User Name" value="<?php if(isset($error)){ echo $_POST['username']; } ?>">
    </div>

    <div class="password">
        <input type="password" name="password" id="password" class="form-control input-lg" placeholder="Password" value="<?php if(isset($error)){ echo $_POST['password']; } ?>" >
    </div>

    <input type="submit" value="Log In">

</form>
</div>
        </div>
</body>
</html>

<form action="index.php">
    <center><input type="submit" value="Developer Mode"></center>
</form>


<footer>
    <p>&copy; Team GM - Spring 2016</p>
</footer>

</body>


</html>