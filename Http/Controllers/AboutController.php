<?php

namespace Http\Controllers;

class AboutController extends Controller
{
    public function index()
    {
        return view('about', ['heading' => 'About']);
    }
}