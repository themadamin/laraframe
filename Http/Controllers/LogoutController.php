<?php

namespace Http\Controllers;

use Core\Session;

class LogoutController
{
    /**
     * @return void
     */
    public function __invoke(): void
    {
        Session::destroy();

        header('Location: /');

        exit();
    }
}