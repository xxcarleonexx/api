<?php


namespace base;


use PDO;
use PDOException;

class Db
{

    /** @var PDO */
    protected $conn;

    public function __construct(string $host, string $db, string $user, string $pass)
    {
        try {
            $this->conn = new PDO("mysql:host=" . $host . ";dbname=" . $db, $user, $pass);
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
