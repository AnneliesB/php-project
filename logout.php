<?php
require_once("bootstrap/bootstrap.php");
unset ($_SESSION['email']);
session_destroy();
header('location: login.php');
