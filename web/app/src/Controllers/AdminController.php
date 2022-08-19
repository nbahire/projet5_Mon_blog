<?php

namespace App\Acme\Controllers;

use App\Acme\Models\CommentsModel;
use App\Acme\Models\PostsModel;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class AdminController extends Controller
{
    /**
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws LoaderError
     */
    public function index()
    {
        //On verifie si on est admin
        if ($this->isAdmin()) {
            //On instancie le modele correspondant à la table 'posts'
            $postsModel = new PostsModel;

            //On va chercher tous les posts 
            $posts = $postsModel->findBy(['']);
            //On genere la vue 
            $this->twig->display('admin/index.html.twig', compact("posts"));
        }
    }
    /**
     * On verfie si on est admin
     *
     * @return true
     */
    private function isAdmin(): bool
    {
        //On verifie si on est connecté et si "ROLE_ADMIN" est dans nos roles
        if (isset($_SESSION['user']) && in_array('ROLE_ADMIN', $_SESSION['user']['roles'])) {

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
    public function addChapiter()
    {
        if ($this->isAdmin()) {
            $postModel = new PostsModel;
            if (!empty($_POST['titre']) && !empty($_POST['description'])) {

                $addChapiter = $postModel->setTitre(htmlentities($_POST['titre']))
                    ->setDescription(htmlentities($_POST['description']))
                    ->setContenu($_POST['contenu']);
                $postModel->create($addChapiter);
                //On envoie a la vue 
                header('location: /admin');
                $this->twig->display('admin/addChapiter.html.twig', compact('addChapiter'));
            }
            $this->twig->display('admin/addChapiter.html.twig', []);
        }
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function modifyChapiter($id)
    {
        if ($this->isAdmin()) {

            //On instancie le modéle
            $postsModel = new PostsModel;
            //On va chercher 1 billet de blog
            $post = $postsModel->find($id);
            if (!empty($_POST['titre']) && !empty($_POST['contenu'])) {
                // On hydrate
                $postModif = $postsModel->setId($post->id)
                    ->setTitre(htmlentities($_POST['titre']))
                    ->setDescription($_POST['description'])
                    ->setContenu($_POST['contenu']);
                // On enregistre
                $postModif->update();
                header('Location: /posts/lire/' . $post->id);
                $this->twig->display('admin/modifyChapiter.html.twig', compact('post', 'postModif'), 'admin');
            }
            $this->twig->display('admin/modifyChapiter.html.twig', compact('post'), 'admin');
        }
    }
    /**
     * Affiche les commentaires signalés
     *
     * @param [type] $id
     * @return void
     */
    public function moderateComment(): void
    {
        if ($this->isAdmin()) {
            $commentsModel = new CommentsModel;

            //On va chercher tous les posts 
            $moderates = $commentsModel->findBy(['moderates' => 1]);
            try {
                $this->twig->display('admin/moderateComment.html.twig', compact('moderates'));
            } catch (LoaderError|RuntimeError|SyntaxError $e) {
                echo 'erreur '.$e;
            }
        }
    }
    /**
     * supprimer un commentaire si on est admin
     *
     * @param int $id
     * @return void
     */
    public function deleteComment(int $id): void
    {
        if ($this->IsAdmin()) {
            $comment = new CommentsModel;
            $comment->delete($id);
            header('location: ' . $_SERVER['HTTP_REFERER']);
        }
    }
    /**
     * supprimer un post si on est admin
     *
     * @param int $id
     * @return void
     */
    public function deletePost(int $id): void
    {
        if ($this->IsAdmin()) {
            $deleteP = new PostsModel;
            $deleteP->delete($id);
            header('location: /posts');
        }
    }
    /**
     * active le signalement du commentaire en mettant moderate a un
     *
     * @param int $id
     * @return void
     */
    public function getComment(int $id): void
    {
        $commentsModel = new CommentsModel;
        $commentaArray = $commentsModel->find($id);
        if ($commentaArray) {
            $comment = $commentsModel->hydrate($commentaArray);
            $comment->setModerates($comment->getModerates() ?: 1);
            $comment->update();
        }
    }
    public function restaure($id)
    {
        $commentsModel = new CommentsModel;
        $commentaArray = $commentsModel->find($id);
        if ($commentaArray) {
            $comment = $commentsModel->hydrate($commentaArray);

            if ($comment->getModerates()) {
                $comment->setModerates(0);
                header('location: /admin/moderateComment');
            }

            $comment->update();
        }
    }
}