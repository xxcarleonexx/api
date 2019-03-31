<?php

use base\Db;
use entity\UserTask;

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=UTF-8');

// include database and object files
include_once '../../base/core.php';
include_once '../../base/Db.php';
include_once '../utilities.php';
include_once '../config/database.php';
include_once '../objects/product.php';

// utilities
$utilities = new Utilities();

// instantiate database and product object
$db = new Db();
$connection = $db->getConnection();

// initialize object
$userTasks = new UserTask($connection);

// query products
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
    $products_arr["paging"] = $paging;

    http_response_code(200);
    echo json_encode($userTasks);
} else {
    http_response_code(404);
    echo json_encode(["message" => "No products found."]);
}
