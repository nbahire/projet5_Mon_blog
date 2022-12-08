<?php

namespace App\Acme\Core;

//On "importe" PDO
use PDO;
use PDOException;

class Db extends PDO
{
    //Instance unique de la classe
    private static $instance;

    // Informations de connexion
    private const DB_HOST = 'mysql';
    private const DB_USER = 'root';
    private const DB_PASS = 'root';
    private const DB_NAME = 'my_blog';

    public function __construct()
    {
        //DSN de connexion
        $_dsn = 'mysql:dbname=' . self::DB_NAME . ';host=' . self::DB_HOST;

        //On appelle le constructeur de la class PDO
        try {
            parent::__construct($_dsn, self::DB_USER, self::DB_PASS);
            $this->setAttribute(PDO::MYSQL_ATTR_INIT_COMMAND, 'SET NAME utf8');
            $this->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
            $this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
}
