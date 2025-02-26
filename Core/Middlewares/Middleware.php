<?php

namespace Core\Middlewares;

use Core\Middlewares\Guest;
use Exception;

class Middleware {

    public const MAP = [
        'guest' => Guest::class,
        'auth' => Auth::class,
    ];

    /**
     * @throws Exception
     */
    public static function resolve(string $key): void
    {
        $middleware = static::MAP[$key] ?? false;

        if (!$middleware){
            throw new Exception("No matching middleware found for key $key.");
        }

        (new $middleware())->handle();
    }
}