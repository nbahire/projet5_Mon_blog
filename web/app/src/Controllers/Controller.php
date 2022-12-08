<?php

namespace App\Acme\Controllers;

use Twig\Environment;
use Twig\Extension\DebugExtension;
use Twig\Loader\FilesystemLoader;
use Twig\TwigFunction;

abstract class Controller
{
    private FilesystemLoader $loader;
    protected Environment $twig;

    public function __construct()
    {
        //Paramétrage du dossier contenant les vues
        $this->loader = new FilesystemLoader(ROOT.'/app/src/Views');

        //Paramétrage de l'environnement Twig
        $this->twig = new Environment($this->loader, ['debug' => true]);
        $this->twig->addExtension(new DebugExtension());
        $this->twig->addFunction(new TwigFunction('asset', function ($asset) {
            // implement whatever logic you need to determine the asset path

            return sprintf('/%s', ltrim($asset, '/'));
        }));
    }
}
