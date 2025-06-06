<?php

namespace Http\Controllers;

use Core\Database;
use Core\Request;
use Core\Validator;

class NotesController extends Controller
{
    public function __construct(protected Database $database)
    {
    }

    public function index()
    {
        $notes = $this->database->query('select * from notes')->get();
        view('notes/index', ['heading' => 'Note', 'notes' => $notes]);
    }

    public function show($id)
    {
        $user = $this->database->query('select * from users where email = :email', [
            'email' => $_SESSION['user']['email']
        ])->find();

        $note = $this->database->query('select * from notes where id = :id', [
            'id' => $id
        ])->findOrFail();

        authorize($note['user_id'] === $user['id']);

        view('notes/show', [
            'heading' => 'Note Info',
            'note' => $note
        ]);
    }

    public function create()
    {
        view('notes/create', [
            'heading' => 'Create Note',
            'errors' => []
        ]);
    }

    public function store(Request $request)
    {
        $errors = [];

        if (! Validator::string($request->title, 1, 100)) {
            $errors['title'] = 'A body of no more than 1,0 characters is required.';
        }

        if (! Validator::string($request->body, 1, 1000)) {
            $errors['body'] = 'A body of no more than 1,0 characters is required.';
        }

        if (! empty($errors)) {
            view('notes/create', [
                'heading' => 'Create Note',
                'errors' => $errors
            ]);
        }

        $this->database->query("INSERT INTO notes(title, body, user_id) VALUES(:title, :body, :user_id)", [
            'title' => $request->title,
            'body' => $request->body,
            'user_id' => 1
        ]);

        header('location: /notes');
        die();
    }

    public function edit($id)
    {
        $user = $this->database->query('select * from users where email = :email', [
            'email' => $_SESSION['user']['email']
        ])->find();

        $note = $this->database->query('select * from notes where id = :id', [
            'id' => $id
        ])->findOrFail();

        authorize($note['user_id'] === $user['id']);

        view('notes/edit', [
            'heading' => 'Edit Note',
            'note' => $note
        ]);
    }

    public function update($id, Request $request)
    {
        $user = $this->database->query('select * from users where email = :email', [
            'email' => $_SESSION['user']['email']
        ])->find();

        $note = $this->database->query('select * from notes where id = :id', [
            'id' => $id
        ])->findOrFail();

        authorize($note['user_id'] === $user['id']);

        $errors = [];

        if (! Validator::string($request->body, 1, 1000)) {
            $errors['body'] = 'A body of no more than 1,000 characters is required.';
        }

        if (count($errors)) {
            view('notes/edit', [
                'heading' => 'Edit Note',
                'errors' => $errors,
                'note' => $note
            ]);
        }

        $this->database->query("update notes set title = :title, body = :body where id = :id", [
            'id' => $id,
            'title' => $request->title,
            'body' => $request->body
        ]);

        header('location: /notes');
        die();
    }

    public function delete($id)
    {
        $currentUserId = 1;

        $note = $this->database->query('select * from notes where id = :id', [
            'id' => $id
        ])->findOrFail();

        authorize($note['user_id'] === $currentUserId);

        $this->database->query('delete from notes where id = :id', [
            'id' => $id
        ]);

        header('location: /notes');
        exit();
    }

}