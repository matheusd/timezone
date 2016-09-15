<?php

// use Composer autoloader
if (!@include(__DIR__.'/../vendor/autoload.php')) { 
    die('Could not find Composer autoloader');
}

//load the project's autoloader
if (!@include(__DIR__.'/modules/autoload.php')) { 
    die('Could not find the project autoloader');
}

//Mask all PHP errors/notices/warnings as exceptions
function exception_error_handler($no, $str, $file, $line ) {
    throw new ErrorException($str, 0, $no, $file, $line);
}
set_error_handler("exception_error_handler");

require("config/di.php");
