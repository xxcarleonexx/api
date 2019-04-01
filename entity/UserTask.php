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
        $query = 'INSERT INTO  `' . $this->table . '` SET `title`=:title, `priority`=:priority';

        $stmt = $this->db->prepare($query);

        $this->name = htmlspecialchars(strip_tags($this->name));

        $stmt->bindParam(':title', $this->name);
        $stmt->bindParam(':priority', $this->priority);

        $connection = $stmt->execute();

        if (false === $connection) {
            var_dump($this->db->errorInfo());
            throw new RuntimeException($this->db->errorCode());
        }
        return $connection;
    }

    /**
     * @return bool
     */
    public function delete()
    {
        $query = 'DELETE FROM `' . $this->table . '` WHERE id = :id';

        $stmt = $this->db->prepare($query);

        $this->id = htmlspecialchars(strip_tags($this->id));

        $stmt->bindParam(':id', $this->id);

        return $stmt->execute();
    }

    /**
     * @param $from
     * @param $perPage
     * @return false|PDOStatement
     */
    public function readPaging($from, $perPage)
    {
        $query = 'SELECT * FROM ' . $this->table . ' LIMIT :page, :max';

        $stmt = $this->db->prepare($query);

        $stmt->bindParam(':page', $from, PDO::PARAM_INT);
        $stmt->bindParam(':max', $perPage, PDO::PARAM_INT);

        $connection = $stmt->execute();

        if (false === $connection) {
            var_dump($this->db->errorInfo());
            throw new RuntimeException($this->db->errorCode());
        }

        return $stmt;
    }

    public function count()
    {
        $query = 'SELECT COUNT(*) as total FROM `' . $this->table . '`';
        $stmt = $this->db->query($query);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'];
    }

}
