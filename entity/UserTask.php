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

    /** @var PDO */
    private $db;

    /** @var string */
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
        $query = "INSERT INTO  `" . $this->table . "` SET name=:name, status=:status, user_id=:user_id";

        $stmt = $this->db->prepare($query);

        $this->name = htmlspecialchars(strip_tags($this->name));

        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":priority", $this->priority);
        $stmt->bindParam(":user_id", $this->user_id);

        return $stmt->execute();
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

    /**
     * @param $from
     * @param $perPage
     * @return false|PDOStatement
     */
    public function readPaging($from, $perPage)
    {
        $query = 'SELECT * FROM `' . $this->table . '` LIMIT ?, ?';

        $stmt = $this->db->prepare($query);

        $stmt->bindParam(1, $from, PDO::PARAM_INT);
        $stmt->bindParam(2, $perPage, PDO::PARAM_INT);

        $connection = $this->db->query($query);

        if (false === $connection) {
            var_dump($this->db->errorInfo());
            throw new RuntimeException($this->db->errorCode());
        }

        return $connection;
    }

    public function count()
    {
        $query = 'SELECT COUNT(*) as total FROM `' . $this->table . '`';
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'];
    }

}
