<?php

include_once '../../base/Db.php';
include_once '../../entity/UserTask.php';
require_once '../../entity/User.php';

use base\Db;
use entity\User;
use entity\UserTask;


header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Max-Age: 3600');
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');


$db = new Db();
$connect = $db->getConnection();

$userTask = new UserTask($connect);
$user = new User($connect);

$postData = file_get_contents('php://input');
$data = json_decode($postData);
$userTask->id = $data->id;

if (empty($data->token)) {
    http_response_code(403);
    echo json_encode(['message' => 'You not allowed to make this action']);
    return;
}

if (!empty($data->id)) {
    $user->token = $data->token;
    if (!$user->findByToken()) {
        http_response_code(400);
        echo json_encode(['message' => 'Invalid token']);
        return;
    }
    if ($userTask->delete()) {
        http_response_code(200);
        echo json_encode(['message' => 'Task was deleted.']);
    } else {
        http_response_code(503);
        echo json_encode(['message' => 'Unable to delete task.']);
    }
} else {
    http_response_code(400);
    echo json_encode(['message' => 'Unable to create task. Data is incomplete.']);
}

