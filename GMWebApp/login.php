<!DOCTYPE html>
<html lang="en">


<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GM | IT Expert Live Help - Login</title>

    <!-- Stylesheets -->
    <link rel="stylesheet" href="style.css" type="text/css" />

    <!-- Script Imports -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
    <script src="login.js" type="text/javascript"></script>

    <!-- Favicons -->
    <link rel="shortcut icon" href="favicons/favicon.ico">
    <link rel="icon" sizes="16x16 32x32 64x64" href="favicons/favicon.ico">
    <link rel="icon" type="image/png" sizes="196x196" href="favicons/favicon-192.png">
    <link rel="icon" type="image/png" sizes="160x160" href="favicons/favicon-160.png">
    <link rel="icon" type="image/png" sizes="96x96" href="favicons/favicon-96.png">
    <link rel="icon" type="image/png" sizes="64x64" href="favicons/favicon-64.png">
    <link rel="icon" type="image/png" sizes="32x32" href="favicons/favicon-32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="favicons/favicon-16.png">
    <link rel="apple-touch-icon" href="favicons/favicon-57.png">
    <link rel="apple-touch-icon" sizes="114x114" href="favicons/favicon-114.png">
    <link rel="apple-touch-icon" sizes="72x72" href="favicons/favicon-72.png">
    <link rel="apple-touch-icon" sizes="144x144" href="favicons/favicon-144.png">
    <link rel="apple-touch-icon" sizes="60x60" href="favicons/favicon-60.png">
    <link rel="apple-touch-icon" sizes="120x120" href="favicons/favicon-120.png">
    <link rel="apple-touch-icon" sizes="76x76" href="favicons/favicon-76.png">
    <link rel="apple-touch-icon" sizes="152x152" href="favicons/favicon-152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="favicons/favicon-180.png">
    <meta name="msapplication-TileColor" content="#FFFFFF">
    <meta name="msapplication-TileImage" content="favicons/favicon-144.png">
    <meta name="msapplication-config" content="favicons/browserconfig.xml">
</head>


<body>
    <div class="container" id="login">
        <header>
            <img src="images/Logo_of_General_Motors.png" width="100" height="100" alt="General Motors Logo" />
            <h1>IT Expert Live Help - Login</h1>
        </header>


        <div id="login" class="content">
            <form id="slick-login" method="post" action="post/login-post.php" autocomplete="off">
                <input type="text" name="username" id="username" placeholder="User Name"
                       value="<?php if (isset($error)) { echo $_POST['username']; } ?>"
                       autofocus />
                <br/>

                <input type="password" name="password" id="password" placeholder="Password"
                       value="<?php if (isset($error)) { echo $_POST['password']; } ?>" />
                <br/>

                <input type="submit" id="logIn" value="Log In" />
            </form>

            <form action="post/login-post.php" method="post">
                <input type="submit" name="developerMode" id="developerMode" value="Developer Mode" />
            </form>
        </div><!-- end div.content -->


        <footer>
            <p>&copy; Team GM - Spring 2016</p>
        </footer>
    </div><!-- end div.container -->
</body>
</html>
