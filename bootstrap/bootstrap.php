<?php

/**
 * volgens mij moet hier een sessie lopende zijn, maar ik ben niet zeker
 */

spl_autoload_register(function($class){
   require_once (__DIR__.DIRECTORY_SEPARATOR."classes".DIRECTORY_SEPARATOR.$class."php");
});