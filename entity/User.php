<?php


namespace entity;


use PDO;

class User
{

    /** @var PDO  */
    private $db;
    /** @var string */
    private $table = 'users';

    /** @var int */
    public $id;
    /** @var string */
    public $user_name;
    /** @var string */
    public $mail;


    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

}
