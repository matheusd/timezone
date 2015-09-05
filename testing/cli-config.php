<?php

use Doctrine\ORM\Tools\Console\ConsoleRunner;

require_once __DIR__.'/../src/bootstrap.php';

$container = new Pimple\Container();
$provider = new WebAppDIProvider();
$provider->register($container);
$container['dbConfigName'] = 'test';

$entityManager = $container['entityManager'];

return ConsoleRunner::createHelperSet($entityManager);