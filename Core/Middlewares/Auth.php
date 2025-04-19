<?php

namespace Core\Middlewares;

class Auth
{
    public function handle(): void
    {
        if (empty($_SESSION['user'])){
            header("location: /login");
            exit();
        }
    }
}