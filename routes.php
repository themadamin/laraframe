<?php

use Core\Router;
use Http\Controllers\AboutController;
use Http\Controllers\ContactController;
use Http\controllers\HomeController;
use Http\Controllers\LoginController;
use Http\Controllers\LogoutController;
use Http\Controllers\NotesController;
use Http\Controllers\RegistrationController;

$router->get('/', [HomeController::class, 'index']);
$router->get('/about', [AboutController::class, 'index']);
$router->get('/contact', [ContactController::class, 'index']);

//Auth routes
$router->get('/login', [LoginController::class, 'create'])->middleware('guest');
$router->post('/login', [LoginController::class, 'store'])->middleware('guest');
$router->get('/logout', LogoutController::class)->middleware('auth');
$router->get('/register', [RegistrationController::class, 'create'])->middleware('guest');
$router->post('/register', [RegistrationController::class, 'store'])->middleware('guest');

//Note routes
$router->get('/notes', [NotesController::class, 'index']);
$router->get('/notes/{id}', [NotesController::class, 'show']);
$router->get('/notes/create', [NotesController::class, 'create'])->middleware('auth');
$router->post('/notes', [NotesController::class, 'store'])->middleware('auth');
$router->get('/notes/{id}/edit', [NotesController::class, 'edit'])->middleware('auth');
$router->put('/notes/{id}', [NotesController::class, 'update'])->middleware('auth');
$router->delete('/notes/{id}', [NotesController::class, 'delete'])->middleware('auth');
