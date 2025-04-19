<?php

namespace Core\Middlewares;

class Guest
{
    public function handle(): void
    {
        if (!empty($_SESSION['user']) ?? false){
            header("location: /");
            exit();
        }
    }
}