<?php

use App\Acme\Core\Main;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

$_SESSION['erreur']= null;
define('ROOT', dirname(__DIR__));

include '../vendor/autoload.php';
require_once ROOT . '/vendor/autoload.php';

$app = new Main();
$app->start();
