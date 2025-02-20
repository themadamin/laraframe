<?php

use Core\Response;
use Core\Session;

function dd(mixed ...$value)
{
    echo "<pre>";
    var_dump($value);
    echo "</pre>";

    die();
}

function dump(mixed ...$value)
{
    echo "<pre>";
    var_dump($value);
    echo "</pre>";
}

/**
 * @param $value
 * @return bool
 */
function urlIs($value)
{
    return $_SERVER['REQUEST_URI'] === $value;
}

/**
 * @param string $message
 * @param $code
 * @return void
 */
function abort(string $message = 'Not found', $code = 404): void
{
    http_response_code($code);

    require base_path("views/{$code}.php");

    die();
}

function authorize($condition, $status = Response::FORBIDDEN)
{
    if (! $condition) {
        abort($status);
    }

    return true;
}

/**
 * @param $path
 * @return string
 */
function base_path($path): string
{
    return BASE_PATH . $path;
}

/**
 * @param $path
 * @param $attributes
 * @return void
 */
function view($path, $attributes = []): void
{
    extract($attributes);

    require base_path('views/' . $path . '.view.php');
}

/**
 * @param $path
 * @return void
 */
function redirect($path): void
{
    header("location: $path");
    exit();
}

function old($key, $default = '')
{
    return Session::get('old')[$key] ?? $default;
}
