<?php

$heading = "Notes";

$config = require('config.php');
$db = new Database($config['database']);

$id = $_GET['id'];
$currentUser = 8;
$note = $db->query("select * from notes where id = ?", [$id])->findOrFail();


authorize($note['user_id'] === $currentUser);

require('views/note.view.php');