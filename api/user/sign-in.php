<?php

use base\Db;
use entity\User;

require_once '../../base/Db.php';
require_once '../../entity/User.php';

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Max-Age: 3600');
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');

$database = new Db();
$db = $database->getConnection();

$user = new User($db);

$input = file_get_contents('php://input');
$data = json_decode($input);

if (
    !empty($data->login) &&
    !empty($data->password)
) {
    try {
        $user->login = $data->login;
        $user->email = empty($data->email) ? '' : $data->email;
        $user->password = $data->password;
        http_response_code(201);
        echo json_encode(['message' => $user->signIn()]);

    } catch (Throwable $exception) {
        http_response_code(503);
        echo json_encode(['message' => $exception->getMessage()]);
    }
} else {
    http_response_code(400);
    echo json_encode(['message' => 'Unable to sign-in user. Fill user login and password']);
}
