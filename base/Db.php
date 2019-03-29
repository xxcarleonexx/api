<?php


namespace base;


use PDO;
use PDOException;

class Db
{

    /** @var PDO */
    protected $conn;

    private $host = 'localhost';
    private $db = 'api';
    private $user = 'root';
    private $pass = '123456';

    public function __construct()
    {
        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db, $this->user, $this->pass);
            $this->conn->exec("set names utf8");
        } catch (PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
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
