<?php

namespace App\Acme\Controllers;

use App\Acme\Controllers\Traits\SessionTrait;
use App\Acme\Models\PostsModel;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class MainController extends Controller
{
    use SessionTrait;

    /**
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws LoaderError
     */
    public function index(): void
    {
        //On instancie le modele correspondant à la table 'posts'
        $postsModel = new PostsModel;
        $sessionItems = $this->getSession();
        $message = $this->getSuccess();
        //On va chercher tous les posts
        $posts = $postsModel->findBy(['']);
        $post = end($posts);
        $this->twig->display('main/index.html.twig',compact('post','sessionItems', 'message'));
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function error(): void
    {
        $sessionItems= $this->getSession();
        $this->twig->display('main/error.html.twig', compact('sessionItems'));

    }
}
