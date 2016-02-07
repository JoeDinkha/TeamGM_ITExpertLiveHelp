<!DOCTYPE html>
<html lang="en">


<head>
    <meta charset="utf-8">
    <title>IT Expert Live Help - Login | GM</title>
    <link rel="stylesheet" type="text/css" href="style.css" />
    <link rel="shortcut icon" href="?\favicon.ico" />
</head>


<body>
    <div class="container">
        <header>
            <img src="images/Logo_of_General_Motors.png" width="100" height="100" alt="General Motors Logo"/>
            <h1>IT Expert Live Help - Login</h1>
        </header>


        <div id="content" class="login">
            <form role="form" method="post" action="post/login-post.php" autocomplete="off" id="slick-login">
                <input class="form-control input-lg" type="text" name="username" id="username" placeholder="User Name"
                       value="<?php if (isset($error)) {
                           echo $_POST['username'];
                       } ?>" autofocus />
                <br/>

                <input class="form-control input-lg" type="password" name="password" id="password" placeholder="Password"
                       value="<?php if (isset($error)) {
                           echo $_POST['password'];
                       } ?>"/>
                <br/>

                <input type="submit" value="Log In" />
            </form>

            <form action="index.php" method="post">
                <input id="developerMode" type="submit" value="Developer Mode" />
            </form>
        </div> <!-- end div.content -->


        <footer>
            <p>&copy; Team GM - Spring 2016</p>
        </footer>
    </div> <!-- end div.container -->
</body>
</html>
