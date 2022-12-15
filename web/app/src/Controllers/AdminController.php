<?php

namespace App\Acme\Controllers;

use App\Acme\Controllers\Traits\SessionTrait;
use App\Acme\Models\CommentsModel;
use App\Acme\Models\PostsModel;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class AdminController extends Controller
{
    use SessionTrait;
    private $message;
    /**
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws LoaderError
     */
    public function index(): void
    {
        //On verifie si on est admin
        if ($this->isAdmin()) {
            $sessionItems= $this->getSession();

            //On instancie le modele correspondant à la table 'posts'
            $postsModel = new PostsModel();
            $message = $this->getSuccess();
            //On va chercher tous les posts
            $posts = $postsModel->findAll();
            //On genere la vue
            $this->twig->display('admin/index.html.twig', compact("posts", 'sessionItems', 'message'));
        }
    }

    /**
     * On verfie si on est admin
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
            return false;
        }
    }

    public function addChapter(): void
    {
        if ($this->isAdmin()) {
            $sessionItems = $this->getSession();

            $postModel = new PostsModel();
            if (!empty($_POST['titre']) && !empty($_POST['description'])) {
                $addChapter = $postModel->setTitre(htmlentities($_POST['titre']))
                    ->setDescription(htmlentities($_POST['description']))
                    ->setContenu($_POST['contenu']);
                $postModel->create($addChapter);
                //On envoie a la vue
                $_SESSION['success'] = 'Article ajouté avec success';
                header('location: /admin');
                $this->twig->display('admin/addChapter.html.twig', compact('addChapter', 'sessionItems'));
            }
            $this->twig->display('admin/addChapter.html.twig', compact('sessionItems'));
        }
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function modifyChapter(int $id): void
    {
        if ($this->isAdmin()) {
            $sessionItems = $this->getSession();
            //On instancie le modéle
            $postsModel = new PostsModel();
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
                $_SESSION['success'] = 'L\'article a été modifié';
                header('Location: /posts/read/' . $post->id);
                $this->twig->display('admin/modifyChapter.html.twig', compact('post', 'postModif', 'sessionItems'));
            }
            $this->twig->display('admin/modifyChapter.html.twig', compact('post', 'sessionItems'));
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
            $sessionItems= $this->getSession();

            $commentsModel = new CommentsModel();

            //On va chercher tous les posts
            $moderates = $commentsModel->findBy(['moderates' => 0]);
            try {
                $this->twig->display('admin/moderateComment.html.twig', compact('moderates', 'sessionItems'));
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
            $comment = new CommentsModel();
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
            $deleteP = new PostsModel();
            $deleteP->delete($id);

            header('location: /posts');
        }
    }
    /**
     * active le signalement du commentaire en mettant moderate a 0
     *
     * @param int $id
     * @return void
     */
    public function getComment(int $id): void
    {
        $commentsModel = new CommentsModel();
        $commentaArray = $commentsModel->find($id);
        if ($commentaArray) {
            $comment = $commentsModel->hydrate($commentaArray);
            $comment->setModerates($comment->getModerates() ?: 0);
            $comment->update();
        }
    }

    /**
     * restaure le commentaire en mettant moderate à 1
     * @param int $id
     * @return void
     */
    public function restaure(int $id): void
    {
        $commentsModel = new CommentsModel();
        $commentaArray = $commentsModel->find($id);
        if ($commentaArray) {
            $comment = $commentsModel->hydrate($commentaArray);
            $comment->setModerates(1);
            header('location: /admin/moderateComment');
            $comment->update();
        }
    }
}
