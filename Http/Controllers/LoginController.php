<?php

namespace Http\Controllers;

use Core\Authenticator;
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

    public function store()
    {
        $form = LoginForm::validate($attributes = [
            'email' => $_POST['email'],
            'password' => $_POST['password']
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