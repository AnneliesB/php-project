<?php
//allow sessions to be used everywhere bootstrap is required
session_start();
//autoload classes when requiring this bootstrap file
spl_autoload_register(function($class){
    require_once(__DIR__ . "/.." . DIRECTORY_SEPARATOR . "classes" . DIRECTORY_SEPARATOR . $class . ".php");
});