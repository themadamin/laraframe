<?php

use Core\App;
use Core\Container;
use Core\CustomMailer;
use Core\Database;
use Core\Request;
use Symfony\Component\Mailer\MailerInterface;

$container = new Container;

$container->bind(Database::class, function () {
    $config = require base_path('config.php');

    return new Database($config['database']);
});

$container->bind(Request::class, function (){
    return new Request();
});

$container->bind(MailerInterface::class, fn() => new CustomMailer());

App::setContainer($container);
