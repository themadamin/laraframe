<?php

namespace Http\Controllers;

class HomeController extends Controller
{
    public function index(): null
    {
        return view('index', ['heading' => 'Home']);
    }
}