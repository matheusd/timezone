<?php

include_once(__DIR__."/../bootstrap.php");

$container = new Pimple\Container();
$provider = new WebAppDIProvider();
$provider->register($container);

$response = $container['response'];
$emitter = $container['responseEmitter'];
$emitter->emit($response);
