<?php
/**
 * Created by PhpStorm.
 * User: cse498
 * Date: 2/8/16
 * Time: 6:11 PM
 */
unset($_SESSION['user']);
session_destroy();

header("Location: ../login.php");
exit();
