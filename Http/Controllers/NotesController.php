<?php

namespace Http\Controllers;

use Core\App;
use Core\Database;
use Core\Validator;

class NotesController extends Controller
{
    private $db;
    public function __construct()
    {
        $this->db = App::resolve(Database::class);
    }

    public function index()
    {
        $notes = $this->db->query('select * from notes where user_id = 1')->get();
        view('notes/index', ['heading' => 'Note', 'notes' => $notes]);
    }

    public function show($id)
    {
        $currentUserId = 1;

        $note = $this->db->query('select * from notes where id = :id', [
            'id' => $id
        ])->findOrFail();

        authorize($note['user_id'] === $currentUserId);

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

    public function store()
    {
        $errors = [];

        if (! Validator::string($_POST['title'], 1, 100)) {
            $errors['title'] = 'A body of no more than 1,0 characters is required.';
        }

        if (! Validator::string($_POST['body'], 1, 1000)) {
            $errors['body'] = 'A body of no more than 1,0 characters is required.';
        }

        if (! empty($errors)) {
            view('notes/create', [
                'heading' => 'Create Note',
                'errors' => $errors
            ]);
        }

        $this->db->query("INSERT INTO notes(title, body, user_id) VALUES(:title, :body, :user_id)", [
            'title' => $_POST['title'],
            'body' => $_POST['body'],
            'user_id' => 1
        ]);

        header('location: /notes');
        die();
    }

    public function edit($id)
    {
        $currentUserId = 1;

        $note = $this->db->query('select * from notes where id = :id', [
            'id' => $id
        ])->findOrFail();

        authorize($note['user_id'] === $currentUserId);

        view('notes/edit', [
            'heading' => 'Edit Note',
            'note' => $note
        ]);
    }

    public function update($id)
    {
        $currentUserId = 1;

        $note = $this->db->query('select * from notes where id = :id', [
            'id' => $id
        ])->findOrFail();

        authorize($note['user_id'] === $currentUserId);

        $errors = [];

        if (! Validator::string($_POST['body'], 1, 1000)) {
            $errors['body'] = 'A body of no more than 1,000 characters is required.';
        }

        if (count($errors)) {
            view('notes/edit', [
                'heading' => 'Edit Note',
                'errors' => $errors,
                'note' => $note
            ]);
        }

        $this->db->query("update notes set title = :title, body = :body where id = :id", [
            'id' => $id,
            'title' => $_POST['title'],
            'body' => $_POST['body']
        ]);

        header('location: /notes');
        die();
    }

    public function delete($id)
    {
        $currentUserId = 1;

        $note = $this->db->query('select * from notes where id = :id', [
            'id' => $id
        ])->findOrFail();

        authorize($note['user_id'] === $currentUserId);

        $this->db->query('delete from notes where id = :id', [
            'id' => $id
        ]);

        header('location: /notes');
        exit();
    }

}