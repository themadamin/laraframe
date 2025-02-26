<?php

namespace Core\Middlewares;

class Auth
{
    public function handle(): void
    {
        if (! $_SESSION['user'] ?? false){
            header("location: /login");
            exit();
        }
    }
}