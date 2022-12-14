<?php

namespace App\Acme\Core;

use App\Acme\Controllers\Controller;
use App\Acme\Controllers\MainController;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class Main extends Controller
{
    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function start(): void
    {
        session_start();
        // On récupère l'adresse
        $uri = $_SERVER['REQUEST_URI'];
        $uri = substr($uri, 1);
        // On vérifie si elle n'est pas vide et si elle se termine par un /
        if (!empty($uri) && $uri !== '/' && $uri[-1] === '/') {
            // Dans ce cas on enlève le /
            $uri = substr($uri, 0, -1);

            // On envoie une redirection permanente
            http_response_code(301);

            // On redirige vers l'URL dans /
            header('Location: ' . $uri.'?');
        }
        // On sépare les paramètres et on les met dans le tableau $params
        $params = explode('/', $uri);
        // Si au moins 1 paramètre existe
        if ($params[0] !== "") {
            // On sauvegarde le 1er paramètre dans $controller en mettant sa 1ère lettre en majuscule,
            // en ajoutant le namespace des controleurs et en ajoutant "Controller" à la fin
            $controller = '\\App\\Acme\\Controllers\\' . ucfirst(array_shift($params)) . 'Controller';
            // On instancie le contrôleur
            try {
                $controller = new $controller();
            } catch (\Error) {
            }


            // On sauvegarde le 2ème paramètre dans $action si il existe, sinon index
            $action = isset($params[0]) ? array_shift($params) : 'index';


            if (method_exists($controller, $action)) {
                // Si il reste des paramètres, on appelle la méthode en envoyant les paramètres sinon on l'appelle "à vide"
                try {
                    (isset($params[0])) ? call_user_func_array([$controller, $action], $params) : $controller->$action();
                } catch (\Error) {
                    http_response_code(404);
                    header('Location: /main/error');
                }
            } else {
                // On envoie le code réponse 404
                http_response_code(404);
                header('Location: /main/error');
            }
        } else {
            // Ici aucun paramètre n'est défini
            // On instancie le contrôleur par défaut (page d'accueil)
            $controller = new MainController();

            // On appelle la méthode index
            $controller->index();
        }
    }
}
