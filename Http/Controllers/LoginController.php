<?php

namespace Http\Controllers;

use Core\Authenticator;
use Core\Request;
use Core\Session;
use Http\Forms\LoginForm;

class LoginController
{
    public function create()
    {
        view('auth/login', [
            'errors' => Session::get('errors')
        ]);
    }

    public function store(Request $request)
    {
        $form = LoginForm::validate($attributes = [
            'email' => $request->email,
            'password' => $request->password
        ]);

        $signedIn = (new Authenticator)->attempt(
            $attributes['email'], $attributes['password']
        );

        if (!$signedIn) {
            $form->error(
                'email', 'No matching account found for given email address'
            )->throw();
        }

        redirect('/');
    }
}