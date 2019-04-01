<?php

include_once '../../base/Db.php';
include_once '../../entity/UserTask.php';

use base\Db;
use entity\UserTask;


header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Max-Age: 3600');
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');


$db = new Db();
$connect = $db->getConnection();

$userTask = new UserTask($connect);

$postData = file_get_contents('php://input');
$data = json_decode($postData);
$userTask->id = $data->id;

if ($userTask->delete()) {
    http_response_code(200);
    echo json_encode(['message' => 'Product was deleted.']);
} else {
    http_response_code(503);
    echo json_encode(['message' => 'Unable to delete product.']);
}
