<?php

namespace App\Acme\Controllers;

use App\Acme\Controllers\Traits\SessionTrait;
use App\Acme\Core\Form;
use App\Acme\Models\UsersModel;
use JetBrains\PhpStorm\NoReturn;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class UsersController extends Controller
{
    use SessionTrait;
    /**
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws LoaderError
     */
    public function index(): void
    {
        if (isset($_SESSION['user']) && in_array('ROLE_USER', $_SESSION['user']['roles'])) {
            //On verifie si on est admin
            $sessionItems= $this->getSession();
            $userInfo = [
                'name' => $_SESSION['user']['name'],
                'email' => $_SESSION['user']['email']
            ];
            $message = $this->getSuccess();
            //On genere la vue
            $this->twig->display('users/index.html.twig', compact('sessionItems', 'userInfo', 'message'));
        }
    }

    private function isNotUser(): bool
    {
        //On verifie si on est connecté et si "ROLE_USER" est dans nos roles
        if (!isset($_SESSION['user'])) {
            return true;
        } else {
            // On est pas admin
            $_SESSION['erreur'] = "Accès interdit !!";
            header('location: /users');
            exit;
        }
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function login(): void
    {
        if ($this->isNotUser()) {
            // On vérifie si notre post contient les champs email et password
            if (!empty($_POST['email']) && !empty($_POST['password'])) {
                $userModel = new UsersModel();
                $userArray = $userModel->findByEmail(strip_tags($_POST['email']));

                if (!$userArray) {
                    $_SESSION['erreur'] = " Identifiants incorectes !! ";
                    header('Location: login');
                    exit;
                }
                $user = $userModel->hydrate($userArray);

                if (password_verify(strip_tags($_POST['password']), $user->getPassword())) {
                    $user->setSession();
                    $_SESSION['success'] = 'Bienvenue '.$_SESSION['user']['name'];
                    if (isset($_SESSION['user']['roles']) && in_array('ROLE_ADMIN', $_SESSION['user']['roles'])) {
                        header('Location: /admin');
                    }

                    if (isset($_SESSION['user']['roles']) && in_array('ROLE_USER', $_SESSION['user']['roles'])) {
                        header('Location: /users');
                    }
                } else {
                    $_SESSION['erreur'] = " Identifiants incorectes !! ";
                    header('Location: login');
                    exit;
                }
                $this->twig->display('users/login.html.twig', compact('user'));
            }
            $message = $this->getSuccess();
            $this->twig->display('users/login.html.twig', compact('message'));
        }
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function register(): void
    {
        if ($this->isNotUser()) {
            // On instancie le formulaire
            $form = new Form();

            // On ajoute chacune des parties qui nous intéressent
            $form->startForm()
                ->addLabelFor('name', 'Nom')
                ->addInput('email', 'name', ['id' => 'name', 'class' => 'form-control mb-2'])
                ->addLabelFor('email', 'Email')
                ->addInput('email', 'email', ['id' => 'email', 'class' => 'form-control mb-2'])
                ->addLabelFor('password', 'Mot de passe')
                ->addInput('password', 'password', ['id' => 'password', 'class' => 'form-control mb-2'])
                ->addButton('submit', 'S\'enregister', ['class' => 'btn btn-primary pull-right'])
                ->endForm()
            ;
            // On vérifie si notre post contient les champs email et password
            if (Form::validate($_POST, ['email', 'password','name'])) {
                // On nettoie l'e-mail et on chiffre le mot de passe
                $name = strip_tags($_POST['name']);
                $email = strip_tags($_POST['email']);
                $pass = password_hash($_POST['password'], PASSWORD_ARGON2I);
                $role = 'ROLE_USER';
                $user = new UsersModel();
                $user->setEmail($email)
                    ->setName($name)
                    ->setRoles($role)
                    ->setPassword($pass);
                $user->create();
                $_SESSION['success'] = 'Bienvenue dans le monde du dev '.$_SESSION['user']['name'];
                header('Location: /users');
            }
            $this->twig->display('users/register.html.twig', ['registerForm' => $form->create()]);
        }
    }

    /**
     * @throws Exception
     * @throws \Exception
     */
    public function forgotten(): void
    {
        if ($this->isNotUser()) {
            // On instancie le formulaire
            $form = new Form();

            // On ajoute chacune des parties qui nous intéressent
            $form->startForm()
                ->addLabelFor('email', 'Entrez votre adresse email', ['class' => 'mb-2'])
                ->addInput('email', 'email', ['id' => 'email', 'class' => 'form-control mb-2'])
                ->addButton('submit', 'Récupérer', ['class' => 'btn btn-secondary'])
                ->endForm();
            if (isset($_POST["email"]) && (!empty($_POST["email"]))) {
                $emailAddress = strip_tags($_POST['email']);
                $emailAddress = filter_var($emailAddress, FILTER_VALIDATE_EMAIL);

                $userModel = new UsersModel();
                $user = $userModel->findByEmail($emailAddress);
                if (!$user) {
                    $_SESSION['erreur'] = " Email inconnu !! ";
                    header('Location: forgotten');
                    exit;
                }
                // generate token by binaryhexa
                $token = bin2hex(random_bytes(50));
                $_SESSION['token'] = $token;
                $_SESSION['email'] = $emailAddress;

                $phpmailer = new PHPMailer();
                $phpmailer->IsSMTP();
                $phpmailer = new PHPMailer();
                $phpmailer->isSMTP();
                $phpmailer->Host = 'smtp.mailtrap.io';
                $phpmailer->SMTPAuth = true;
                $phpmailer->Port = 2525;
                $phpmailer->Username = 'b8b1fd330eb0c5';
                $phpmailer->Password = '0636c7af985b1f';
                $phpmailer->IsHTML(true);
                $phpmailer->From = "alainledev@mail.io";
                $phpmailer->FromName = "Alain le dev";

                //On construit le lien
                $subject = "Password Recovery";
                $output = '<p>Please click on the following link to reset your password.</p>';
                //replace the site url
                $output .= '<p><a href="https://localhost:3000/users/reset" target="_blank">https://localhost:3000/users/reset</a></p>';
                $body = $output;
                $phpmailer->Subject = $subject;
                $phpmailer->Body = $body;
                $phpmailer->AddAddress($emailAddress);
                if (!$phpmailer->Send()) {
                    echo "Mailer Error: " . $phpmailer->ErrorInfo;
                } else {
                    $_SESSION['success']  = 'Un email contenant le lien de réinitialisation vous a été envoyé !!';
                    header('Location: /main');
                }
                $this->twig->display('users/forgotten.html.twig', ['forgottenPassForm' => $form->create(), $user]);
            }
            $this->twig->display('users/forgotten.html.twig', ['forgottenPassForm' => $form->create()]);
        }
    }
    public function reset(): void
    {
        // On instancie le formulaire
        $form = new Form();
        $form->startForm()
            ->addLabelFor('password', 'Entreot de passe')
            ->addInput('password', 'password', ['id' => 'password', 'class' => 'form-control mb-2'])
            ->addLabelFor('password', 'Confirmer le mot de passe')
            ->addInput('password', 'password', ['id' => 'password', 'class' => 'form-control mb-2'])
            ->addButton('submit', 'Modifier', ['class' => 'btn btn-secondary'])
            ->endForm()
        ;
        $userModel = new UsersModel();
        $user = $userModel->findByEmail($_SESSION['email']);
        if (!isset($user) && !$_SESSION['token']) {
            header('Location: error');
            exit;
        }
        if (!empty($_POST['password'])) {
            $email = $user->email;
            $pass = password_hash($_POST['password'], PASSWORD_ARGON2I);
            $userModel->updatePassword($email, $pass);
            $_SESSION['success']  = 'Votre mot de passe a été réinitialisation !!';
            unset($_SESSION['token'], $_SESSION['email']);
            header('Location: login');
        }
        try {
            $message = $this->getSuccess();
            $this->twig->display('users/reset.html.twig', ['resetPassForm' => $form->create(), $user, $message]);
        } catch (LoaderError|RuntimeError|SyntaxError $e) {
            $_SESSION['erreur'] = 'Erreur '.$e.'!!';
        }
    }

    /**
     * Déconnexion de l'utilisateur
     */
    #[NoReturn] public function logout(): void
    {
        unset($_SESSION['user']);
        $_SESSION['success'] = 'vous etes maintenant deconnécté ';
        header('Location: /main');
        exit;
    }
}
