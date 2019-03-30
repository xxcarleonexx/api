<?php


namespace base;


use PDO;
use PDOException;

class Db
{

    /** @var PDO */
    protected $conn;

    private $host = 'db';
    private $db = 'api';
    private $user = 'root';
    private $pass = 'root';
    private $port = '3306';

    public function __construct()
    {
        try {
            $this->conn = new PDO('mysql:host=' . $this->host . ';port=' . $this->port . ';dbname=' . $this->db, $this->user, $this->pass);
            $this->conn->exec('set names utf8');
        } catch (PDOException $exception) {
            echo 'Connection error: ' . $exception->getMessage();
        }
    }

    /**
     * @return PDO
     */
    public function getConnection()
    {
        return $this->conn;
    }

}
