<?php

class BDD{
    private string $dsn = 'mysql:host=localhost;dbname=tournaments-manager;charsetutf8';
    private string $username = 'phpmyadmin';
    private string $password = 'admin';
    private PDO $bdd;

    public function __construct(){
        $this->bdd = new PDO($this->dsn, $this->username, $this->password);
    }

    public function get_bdd(){
        return $this->bdd;
    }
}