<?php

use Doctrine\ORM\Tools\Console\ConsoleRunner;

// replace with file to your own project bootstrap
require_once 'src\bootstrap.php';


$container = new Pimple\Container();
$provider = new WebAppDIProvider();
$provider->register($container);

$entityManager = $container['entityManager'];

return ConsoleRunner::createHelperSet($entityManager);