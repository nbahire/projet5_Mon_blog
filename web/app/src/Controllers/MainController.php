<?php

namespace App\Acme\Controllers;

use App\Acme\Controllers\Traits\SessionTrait;
use App\Acme\Models\PostsModel;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
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
     * @throws Exception
     */
    public function index(): void
    {
        //On instancie le modele correspondant à la table 'posts'
        $postsModel = new PostsModel();
        $sessionItems = $this->getSession();
        $message = $this->getSuccess();

        if (
            isset($_POST['email'])
            && isset($_POST['message'])
            && isset($_POST['lastname'])
            && isset($_POST['firstname'])
            && isset($_POST['subject'])
        ) {
            $emailAddress = strip_tags($_POST['email']);
            $message = strip_tags($_POST['message']);
            $name = strip_tags($_POST['firstname']).' '.strip_tags($_POST['lastname']);
            $object = strip_tags($_POST['subject']);

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
            $phpmailer->setFrom($emailAddress, $name);
            //On construit le lien
            $subject = $object;
            $output = $message;
            $phpmailer->addAddress('email@email.com');
            $phpmailer->Subject = $subject;
            $phpmailer->Body = $output;
            $submit = $phpmailer->Send();
            if (!$submit) {
                $_SESSION['error'] = "Mailer Error: " . $phpmailer->ErrorInfo;
            } else {
                $_SESSION['success']  = 'Votre message a été envoyé !!';
                header('Location: /main');
            }
        }
        //On va chercher tous les posts
        $posts = $postsModel->findAll();
        $post = end($posts);
        $this->twig->display('main/index.html.twig', compact('post', 'sessionItems', 'message'));
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
