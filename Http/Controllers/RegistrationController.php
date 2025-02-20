<?php

namespace Http\Controllers;

use Core\App;
use Core\Database;
use Core\Validator;

class RegistrationController
{
    /**
     * @return void
     */
    public function create(): void
    {
        if ($_SESSION['user'] ?? false){
            header("location: /");
            exit();
        }

        view('auth/registration');
    }

    public function store()
    {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = $_POST['password'];

        $errors = [];

        if (! Validator::string($name, 4, 100)){
            $errors['name'] = 'Name is invalid';
        }


        if (! Validator::email($email)){
            $errors['email'] = 'Email is invalid';
        }

        if (! Validator::string($password, 7, 255)){
            $errors['password'] = 'Password is invalid';
        }

        if (! empty($errors)){
            view('auth/registration.view.php', ['errors' => $errors]);
        }

        $db = App::resolve(Database::class);

        $user = $db->query("SELECT * FROM users WHERE email = :email", ['email' => $email])->find();

        if ($user){
            header("location: /");
            exit();
        }else{
            $db->query("INSERT INTO users (name, email, password) VALUES (:name, :email, :password)", [
                'name' => $name,
                'email' => $email,
                'password' => password_hash($password, PASSWORD_BCRYPT)
            ]);
        }

        $_SESSION['user'] = ['email' => $email];

        header("location: /");

        exit();
    }
}