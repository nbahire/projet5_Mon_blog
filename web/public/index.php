<?php

use App\Acme\Core\Main;
$_SESSION['erreur']= null;
define('ROOT', dirname(__DIR__));

include '../app/vendor/autoload.php';
require_once ROOT . '/app/vendor/autoload.php';

$app = new Main();
$app->start();
