<?php

namespace App\Acme\Controllers\Traits;

trait SessionTrait
{
    /**
     */
    public function getSession(): array
    {
        $session = '';
        if(isset($_SESSION['user'] )&& in_array('ROLE_ADMIN',$_SESSION['user']['roles'] )){
            $session = 'admin';
        }

        if(isset($_SESSION['user'] )&& in_array('ROLE_USER',$_SESSION['user']['roles'] )){
            $session = 'user';
        }
        return ['session' => $session];
    }
    public function getSuccess() : ?string
    {
        $message = null;
        if (isset($_SESSION['success'])) {
            $message = $_SESSION['success'];
            unset($_SESSION['success']);
        }
        return $message;
    }

    public function getError() : ?string
    {
        $error = null;
        if (isset($_SESSION['error'])) {
            $error = $_SESSION['error'];
            unset($_SESSION['error']);
        }
        return $error;
    }

}