<?php

namespace Http\Controllers;

class TestController
{
    public function __invoke()
    {
        var_dump('working');
    }
}