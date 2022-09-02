<?php

namespace App\Acme\Controllers\Traits;

trait SessionTrait
{
    /**
     */
    public function getSession(): array
    {
        $session = false;
        if(isset($_SESSION['user'] )&& in_array('ROLE_ADMIN',$_SESSION['user']['roles'] )){
            $session = true;
        }
        $error = null;
        if (!empty($_SESSION['erreur'])){
            $error = $_SESSION['erreur'];
        }
        $message = null;
        if (!empty($_SESSION['message'])){
            $message = $_SESSION['message'];
        }
        return $sessionItems =[
            'message' => $message,
            'error' => $error,
            'session' => $session
        ];
    }

}