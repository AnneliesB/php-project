<?php

# require bootstrap
require_once("bootstrap/bootstrap.php");

# check if a session is running with the correct email
if (isset($_SESSION['email'])) {
    //User is logged in, no redirect needed!
} else {
    //User is not logged in, redirect to login.php!
    header("location: login.php");
}

# connect to the database
$conn = Db::getConnection();

# get clicked post info from database -> special tags from class

# get user info from database (get user id based on the session cookie email)

# check if a record exists in the likes table where the current post's id and current user's id are available

    # if true => update record liked status using boolean

    # if false => insert new record into the likes table with the current post id, user id en a true liked status




