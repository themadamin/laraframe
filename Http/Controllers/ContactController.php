<?php

namespace Http\Controllers;

use Http\Controllers\Controller;

class ContactController extends Controller
{
    public function index()
    {
        return view('contact', ['heading' => 'Contact']);
    }
}