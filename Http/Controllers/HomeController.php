<?php

namespace Http\Controllers;

class HomeController extends Controller
{
    public function index()
    {
        view('index', ['heading' => 'Home']);
    }
}