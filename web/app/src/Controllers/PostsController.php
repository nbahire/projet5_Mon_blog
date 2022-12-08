<?php

namespace App\Acme\Controllers;

use App\Acme\Controllers\Traits\SessionTrait;
use App\Acme\Models\PostsModel;
use App\Acme\Models\CommentsModel;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class PostsController extends Controller
{
    use SessionTrait;

    /**
     * cette methode affichera une page listant toutes les posts de la base de données
     * @return void
     */
    public function index(): void
    {
        $sessionItems = $this->getSession();

        //On instancie le modele correspondant à la table 'posts'
        $postsModel = new PostsModel();

        //On va chercher tous les posts
        $posts = $postsModel->findAll();
        $message = $this->getSuccess();
        //On genere la vue
        try {
            $this->twig->display('posts/index.html.twig', compact('posts', 'sessionItems', 'message'));
        } catch (LoaderError|RuntimeError|SyntaxError $e) {
            echo 'erreur sur '.$e;
        }
    }
    /**
     * affiche 1 billet de blog
     * @param int $id du billet de blog
     * @return void
     */

    public function read(int $id): void
    {
        $sessionItems= $this->getSession();

        //On instancie le modéle
        $postsModel = new PostsModel();
        $commentsModel = new CommentsModel();
        //On va chercher 1 billet de blog

        $post = $postsModel->find($id);
        if (!$post) {
            http_response_code(404);
            header('Location: erreur');
        }

        $addComment = null;
        if (!empty($_SESSION['user']) && !empty($_POST['comment'])) {
            $user = strip_tags($_SESSION['user']['name']);
            $comment = strip_tags($_POST['comment']);
            $addComment = $commentsModel->setPostId($id)
                ->setAuthor($user)
                ->setComment(htmlentities($comment));
            $commentsModel->create();
            $_SESSION['success'] = 'Votre message a été envoyé pour moderation';
            header('Location:'. $_SERVER['HTTP_REFERER']);
        }

        //On associe le commentaire a son billet de blog correspodant
        $comments = $commentsModel->findBy(['post_id' => $id]);
        $displayComment = [];
        foreach ($comments as $moderatedComment) {
            if ($moderatedComment->moderates === 1) {
                $displayComment[] = $moderatedComment;
            }
        }
        $moderatedComments = $displayComment;
        $message = $this->getSuccess();
        try {
            $this->twig->display(
                'posts/read.html.twig',
                compact('post', 'addComment', 'moderatedComments', 'sessionItems', 'message')
            );
        } catch (LoaderError|RuntimeError|SyntaxError $e) {
            echo 'erreur sur '.$e;
        }
    }
}
