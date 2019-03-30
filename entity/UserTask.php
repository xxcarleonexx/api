<?php


namespace entity;

use PDO;

class UserTask
{

    public const STATUS_NEW = 1;

    public const STATUS_DONE = 2;

    public const STATUS_ERR = 3;

    private $db;
    private $table = 'user_task';

    /** @var int */
    public $id;

    /** @var int */
    public $user_id;

    /** @var string */
    public $task;

    /** @var int */
    public $status;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function read()
    {
        $query = 'SELECT * FROM "' . $this->table . '"';
        return $this->db->query($query);
    }

}
