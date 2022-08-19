<?php

namespace App\Acme\Controllers;

use App\Acme\Models\UsersModel;
use JetBrains\PhpStorm\NoReturn;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class UsersController extends Controller
{

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
            }else{
                $_SESSION['erreur'] = " Identifiants incorectes !! ";
                header('Location: login');
                exit;
            }
            $this->twig->display('users/login.html.twig', compact('user'));
        }
        $this->twig->display('users/register.html.twig');
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
