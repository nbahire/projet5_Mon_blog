<?php

namespace App\Acme\Controllers;

use App\Acme\Models\PostsModel;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class MainController extends Controller
{
    /**
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws LoaderError
     */
    public function index(): void
    {
        //On instancie le modele correspondant à la table 'posts'
        $postsModel = new PostsModel;

        //On va chercher tous les posts
        $posts = $postsModel->findBy(['']);
        $post = $posts[0];

        $session = false;
        if(isset($_SESSION['user'] )&& $_SESSION['user']['roles'] === 'ROLE_ADMIN'){
             $session = true;
        }
        var_dump($session);
        $this->twig->display('main/index.html.twig',compact('post'));
        $this->twig->display('base.html.twig', compact('session'));
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function error(): void
    {
        $this->twig->display('main/error.html.twig', []);

    }
}
