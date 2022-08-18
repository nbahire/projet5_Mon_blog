<?php

use App\Acme\Core\Main;
$_SESSION['erreur']= null;
define('ROOT', dirname(__DIR__));

include 'vendor/autoload.php';
require_once ROOT . '../autoload.php';

$app = new Main();
try {
    $app->start();
} catch (\Twig\Error\LoaderError $e) {
} catch (\Twig\Error\RuntimeError $e) {
} catch (\Twig\Error\SyntaxError $e) {
}
