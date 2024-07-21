<?php

use Core\App;
use Core\Database;
use Core\Validator;

$email = $_POST['email'];
$password = $_POST['password'];

$errors = [];

if (! Validator::email($email)){
    $errors['email'] = 'Email is invalid';
}

if (! Validator::string($password, 7, 255)){
    $errors['password'] = 'Password is invalid';
}

if (! empty($errors)){
    view('registration/create.view.php', ['errors' => $errors]);
}
$db = App::resolve(Database::class);

$user = $db->query("SELECT * FROM users WHERE email = :email", ['email' => $email])->find();


if ($user){
    header("location: /");
    exit();
}else{
    $db->query("INSERT INTO users (name, email, password) VALUES (:name, :email, :password)", [
        'name' => substr($email, 0, strpos($email, '@')),
        'email' => $email,
        'password' => password_hash($password, PASSWORD_BCRYPT)
    ]);
}

$_SESSION['user'] = ['email' => $email];

header("location: /");

exit();