<?php

$heading = "Create note";
$config = require('config.php');
$db = new Database($config['database']);

if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    $errors = [];

    if (strlen($_POST['body']) === 0){
        $errors['body'] = "A body is required";
    }

    if (strlen($_POST['body']) > 10){
        $errors['body'] = "A body need to be less than ten characters";
    }

    if (empty($errors)){
        $db->query('insert into notes(body, user_id) values(:body, :user_id)', [
            'body' => $_POST['body'],
            'user_id' => 8
        ]);
    }

}
require 'views/note-create.view.php';
