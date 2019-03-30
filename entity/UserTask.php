<?php


namespace entity;

use PDO;
use PDOStatement;
use RuntimeException;

class UserTask
{

    public const STATUS_NEW = 1;

    public const STATUS_DONE = 2;

    public const STATUS_ERR = 3;

    private $db;
    private $table = 'user_tasks';

    /** @var int */
    public $id;

    /** @var int */
    public $user_id;

    /** @var string */
    public $name;

    /** @var int */
    public $status;

    /** @var string */
    public $created;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    /**
     * @return false|PDOStatement
     * throws RuntimeException
     */
    public function read()
    {
        $query = 'SELECT * FROM `' . $this->table . '`';
        $connection = $this->db->query($query);
        if (false === $connection) {
            var_dump($this->db->errorInfo());
            throw new RuntimeException($this->db->errorCode());
        }
        return $connection;
    }

    /**
     * @return bool
     */
    function create()
    {
        // query to insert record
        $query = "INSERT INTO  `" . $this->table . "` SET name=:name, status=:status, user_id=:user_id";

        // prepare query
        $stmt = $this->db->prepare($query);

        // sanitize
        $this->name = htmlspecialchars(strip_tags($this->name));

        // bind values
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":status", $this->status);
        $stmt->bindParam(":user_id", $this->user_id);

        // execute query
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

}
