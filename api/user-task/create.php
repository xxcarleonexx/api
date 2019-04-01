<?php

use base\Db;
use entity\UserTask;

require_once '../../base/Db.php';
require_once '../../entity/UserTask.php';

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Max-Age: 3600');
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');

$database = new Db();
$db = $database->getConnection();

$userTask = new UserTask($db);

$input = file_get_contents('php://input');
$data = json_decode($input);

if (
    !empty($data->priority) &&
    !empty($data->name) &&
    !empty($data->user_id)
) {

    $userTask->name = $data->name;
    $userTask->priority = $data->priority;
    $userTask->user_id = $data->user_id;
    $userTask->created = date('Y-m-d H:i:s');

    if ($userTask->create()) {
        http_response_code(201);
        echo json_encode(['message' => 'Task was created.']);
    } else {
        http_response_code(503);
        echo json_encode(['message' => 'Unable to create task.']);
    }
} else {
    http_response_code(400);
    echo json_encode(['message' => 'Unable to create task. Data is incomplete.']);
}
