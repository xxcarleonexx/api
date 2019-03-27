<?php


namespace base;


use mysqli;

class Db
{

    protected $mysqli;

    public function __construct($host, $db, $name, $pass)
    {
        $this->mysqli = new mysqli($host, $name, $pass, $db);
        $this->mysqli->connect();
    }

}
