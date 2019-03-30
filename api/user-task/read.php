<?php

use base\Db;

require_once '../../base/Db.php';
require_once '../../entity/UserTask.php';

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=UTF-8');

$dbConnection = (new Db())->getConnection();
$userTask = new \entity\UserTask($dbConnection);

$stmt = $userTask->read();

$colCount = $stmt->rowCount();
if ($colCount > 0) {
    $userTasks = [];
    $userTasks['items'] = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $userTasks['items'][] = [
            'id' => $row['id'],
            'name' => $row['name'],
            'user_id' => $row['user_id'],
            'status' => $row['status'],
        ];
    }
    http_response_code(200);
    echo json_encode($userTasks);
} else {
    http_response_code(404);
    echo json_encode(['message' => 'No found tasks']);
}
