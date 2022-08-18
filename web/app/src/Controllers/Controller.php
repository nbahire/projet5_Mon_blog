<?php

namespace App\Acme\Controllers;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

abstract class Controller
{
    private FilesystemLoader $loader;
    protected Environment $twig;

    public function __construct()
    {
        //Paramétrage du dossier contenant les vues
        $this->loader = new FilesystemLoader(ROOT.'/app/src/Views');

        //Paramétrage de l'environnement Twig
        $this->twig = new Environment($this->loader);

    }
}
