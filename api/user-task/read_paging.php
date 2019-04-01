<?php

use base\Db;
use entity\UserTask;
use utils\Utils;

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=UTF-8');

include_once '../../base/core.php';
include_once '../../base/Db.php';
include_once '../../utils/Utils.php';
include_once '../../entity/UserTask.php';

$utilities = new Utils();

$db = new Db();
$connection = $db->getConnection();

$userTasks = new UserTask($connection);

$stmt = $userTasks->readPaging($from, $perPage);
$num = $stmt->rowCount();

if ($num > 0) {

    $userTasks = [];
    $userTasks['items'] = [];
    $userTasks['paging'] = [];

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $userTasks['items'][] = [
            'id' => $row['id'],
            'name' => $row['name'],
            'user_id' => $row['user_id'],
            'priority' => $row['priority'],
        ];
    }

    $totalRows = $userTasks->count();
    $page_url = "{$homeUrl}userTask/read_paging.php?";
    $paging = $utilities->getPaging($page, $totalRows, $perPage, $page_url);
    $userTasks['paging'] = $paging;

    http_response_code(200);
    echo json_encode($userTasks);
} else {
    http_response_code(404);
    echo json_encode(['message' => 'No tasks found.']);
}
