<?php

use Core\App;
use Core\Container;
use Core\Database;
use Core\Request;

$container = new Container;

$container->bind(Database::class, function () {
    $config = require base_path('config.php');

    return new Database($config['database']);
});

$container->bind(Request::class, function (){
    return new Request();
});

App::setContainer($container);
