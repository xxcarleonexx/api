<?php


namespace entity;

use PDO;
use PDOStatement;
use RuntimeException;

class UserTask
{

    public const PRIORITY_LOW = 1;

    public const PRIORITY_NORMAL = 2;

    public const PRIORITY_HIGH = 3;

    /** @var PDO  */
    private $db;

    /** @var string  */
    private $table = 'user_tasks';

    /** @var int */
    public $id;

    /** @var int */
    public $user_id;

    /** @var string */
    public $name;

    /** @var int */
    public $priority;

    /** @var string */
    public $created;

    /** @var string */
    public $due_date;

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
        $stmt->bindParam(":priority", $this->priority);
        $stmt->bindParam(":user_id", $this->user_id);

        // execute query
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    /**
     * @return bool
     */
    public function delete()
    {
        $query = "DELETE FROM `" . $this->table . "` WHERE id = ?";

        $stmt = $this->db->prepare($query);

        $this->id = htmlspecialchars(strip_tags($this->id));

        $stmt->bindParam(1, $this->id);

        return $stmt->execute();
    }

}
