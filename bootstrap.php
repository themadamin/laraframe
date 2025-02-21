<?php

use Core\App;
use Core\Container;
use Core\Database;
use Http\Controllers\PaddleService;
use Http\Controllers\TestInterface;

$container = new Container;

$container->bind(Database::class, function () {
    $config = require base_path('config.php');

    return new Database($config['database']);
});

App::setContainer($container);
