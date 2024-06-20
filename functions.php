<?php

function isUri(string $uri): bool
{
    return $_SERVER['REQUEST_URI'] === $uri;
}


function dd($value): void
{
    echo "<pre>";
    var_dump($value);
    echo "</pre>";

    die();
}

function abort($code = 404):void
{
    http_response_code($code);
    require ("views/{$code}.view.php");

    die();
}

function authorize($condition, $status = Response::FORBIDDEN)
{
    if (!$condition){
        abort($status);
    }
}