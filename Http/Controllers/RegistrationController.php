<?php

namespace Http\Controllers;

use Core\App;
use Core\Database;
use Core\Request;
use Core\Validator;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;

class RegistrationController
{
    public function __construct(protected MailerInterface $mailer)
    {

    }
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

    public function store(Request $request)
    {
        $name = $request->name;
        $email = $request->email;
        $password = $request->password;

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

        $db = App::container()->get(Database::class);

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

        $mail = (new Email())
            ->from('laraframe@support.com')
            ->to($email)
            ->subject('Welcome')
            ->html($this->getHtmlBodyContent($name));

        $this->mailer->send($mail);

        $_SESSION['user'] = ['email' => $email];

        redirect('/');
    }

    public function getHtmlBodyContent(string $name): string
    {
        return (<<<HTMLBody
    <h1 style="text-align: center; color: blue;"> Welcome </h1>
    Hello $name
    <br>
    Thank you for signing up!
    HTMLBody);
    }
}