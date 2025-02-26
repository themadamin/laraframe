<?php

use Core\App;
use Core\Request;
use Core\Router;
use Core\Session;
use Core\ValidationException;

const BASE_PATH = __DIR__ . '/../';

require BASE_PATH . '/vendor/autoload.php';

session_start();

require BASE_PATH . 'Core/functions.php';

require base_path('bootstrap.php');

$router = new Router(App::container());

$routes = require base_path('routes.php');

$request = App::get(Request::class);

try {
    $router->route($request->getUri(), $request->getMethod());
} catch (ValidationException $exception) {
    Session::flash('errors', $exception->errors);
    Session::flash('old', $exception->old);

    return redirect($router->previousUrl());
}

Session::unflash();
