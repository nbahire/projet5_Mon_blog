<?php

namespace App\Acme\Controllers;

use App\Acme\Controllers\Traits\SessionTrait;
use App\Acme\Core\Form;
use App\Acme\Models\UsersModel;
use JetBrains\PhpStorm\NoReturn;
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
    public function index()
    {
        if($this->isUser()){
            //On verifie si on est admin
            $sessionItems= $this->getSession();
            $userInfo = [
                'name' => $_SESSION['user']['name'],
                'email' => $_SESSION['user']['email']
            ];
            //On genere la vue
            $this->twig->display('users/index.html.twig', compact('sessionItems','userInfo'));
        }

    }

    private function isUser(): bool
    {
        //On verifie si on est connecté et si "ROLE_ADMIN" est dans nos roles
        if (isset($_SESSION['user']) && in_array('ROLE_USER', $_SESSION['user']['roles'])) {

            return true;
        } else {

            // On est pas admin
            $_SESSION['erreur'] = "Accès interdit !!";
            header('location: posts');
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
        // On vérifie si notre post contient les champs email et password
        if (!empty($_POST['email']) && !empty($_POST['password'])) {
            // On nettoie l'e-mail et on chiffre le mot de passe
            $userModel = new UsersModel;
            $userArray = $userModel->findByEmail(strip_tags($_POST['email']));           
            if (!$userArray ) {
                
                $_SESSION['erreur'] = " Identifiants incorectes !! ";
                header('Location: login');
                exit;
            }
            $user = $userModel->hydrate($userArray);
            
            if (password_verify($_POST['password'], $user->getPassword())) {
                $user->setSession();
                if (isset($_SESSION['user']['roles']) && in_array('ROLE_ADMIN', $_SESSION['user']['roles'])) {
                    header('Location: /admin');
                }       
                if (isset($_SESSION['user']['roles']) && in_array('ROLE_USER', $_SESSION['user']['roles'])) {
                    header('Location: /users');
                }
            }else{
                $_SESSION['erreur'] = " Identifiants incorectes !! ";
                header('Location: login');
                exit;
            }
            $this->twig->display('users/login.html.twig', compact('user'));
        }
        $this->twig->display('users/login.html.twig');
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function register(): void
    {
        // On instancie le formulaire
        $form = new Form;

    // On ajoute chacune des parties qui nous intéressent
        $form->startForm()
            ->addLabelFor('name', 'Nom')
            ->addInput('name', 'name', ['id' => 'name', 'class' => 'form-control mb-2'])
            ->addLabelFor('email', 'Email')
            ->addInput('email', 'email', ['id' => 'email', 'class' => 'form-control mb-2'])
            ->addLabelFor('password', 'Mot de passe')
            ->addInput('password', 'password', ['id' => 'password', 'class' => 'form-control mb-2'])
            ->addButton('S\'enregister', ['class' => 'btn btn-secondary'])
            ->endForm()
        ;
        // On vérifie si notre post contient les champs email et password
        if(Form::validate($_POST, ['email', 'password','name'])){
            // On nettoie l'e-mail et on chiffre le mot de passe
            $name = strip_tags($_POST['name']);
            $email = strip_tags($_POST['email']);
            $pass = password_hash($_POST['password'], PASSWORD_ARGON2I);
            $role = 'ROLE_USER';
            $user = new UsersModel;
            $user->setEmail($email)
                ->setName($name)
                ->setRoles($role)
                ->setPassword($pass);
            $user->create();
            header('Location: /users');
        }
        $this->twig->display('users/register.html.twig', ['registerForm' => $form->create()]);
    }
    /**
     * Déconnexion de l'utilisateur
     */
    #[NoReturn] public function logout(): void
    {
        unset($_SESSION['user']);
        header('Location: /main');
        exit;
    }
}
