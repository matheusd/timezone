<?php

if ($_SERVER['REQUEST_URI'] == "/") {
    //serve index.html when accessing root during development
    fpassthru(fopen(__DIR__ . "/index.html", "rb"));
    return;
}

if (preg_match('|^/assets/(.+)/(.+)$|', $_SERVER['REQUEST_URI'], $matches)) {
    //serve assets statically through the php embedded ws
    return false;
}

//just give some space on the WS console to ease the view
error_log('');
error_log('vvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvv');
error_log('');

//execute the request by including the production front controller
include(__DIR__."/front_prod.php");
