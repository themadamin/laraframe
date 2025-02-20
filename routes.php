<?php

use Http\Controllers\AboutController;
use Http\Controllers\ContactController;
use Http\controllers\HomeController;
use Http\Controllers\LoginController;
use Http\Controllers\LogoutController;
use Http\Controllers\NotesController;
use Http\Controllers\RegistrationController;
use Http\Controllers\TestController;

$router->get('/', [HomeController::class, 'index']);
$router->get('/about', [AboutController::class, 'index']);
$router->get('/contact', [ContactController::class, 'index']);

//Auth routes
$router->get('/login', [LoginController::class, 'create']);
$router->post('/login', [LoginController::class, 'store']);
$router->get('/logout', LogoutController::class);
$router->get('/register', [RegistrationController::class, 'create']);
$router->post('/register', [RegistrationController::class, 'store']);

//Note routes
$router->get('/notes', [NotesController::class, 'index']);
$router->get('/notes/{id}', [NotesController::class, 'show']);
$router->get('/notes/create', [NotesController::class, 'create']);
$router->post('/notes', [NotesController::class, 'store']);
$router->get('/notes/{id}/edit', [NotesController::class, 'edit']);
$router->put('/notes/{id}', [NotesController::class, 'update']);
$router->delete('/notes/{id}', [NotesController::class, 'delete']);