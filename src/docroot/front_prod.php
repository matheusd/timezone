<?php

try {
    include_once(__DIR__."/../bootstrap.php");

    $container = new Pimple\Container();
    $provider = new WebAppDIProvider();
    $provider->register($container);

    $response = $container['response'];
    $emitter = $container['responseEmitter'];
    $emitter->emit($response);    
} catch (Throwable $t) {
    //will only reach here in **very** weird circumstances (such as missing a config file
    //or a broken install). The DI usually handles all exceptions.
    
    error_log($t);
    header("HTTP/1.0 500 Internal server error");
    die('{"status": "error", "errorMsg": "Internal Server Error"}');
}
