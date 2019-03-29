<?php

use base\Db;

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=UTF-8');

$dbConnection = (new Db())->getConnection();
$userTask = new \entity\UserTask($dbConnection);

$stmt = $userTask->read();
$colCount = $stmt->rowCount();
if ($colCount > 0) {
    $userTasks = [];
}
