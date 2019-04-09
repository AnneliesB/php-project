<?php
session_start();
session_destroy();
setcookie('imdstagram', null, time()-3600);
header('location: login.php');
