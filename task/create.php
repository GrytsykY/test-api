<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/Database.php';
include_once '../classes/Task.php';

$database = new Database();
$db = $database->getConnection();

$task = new Task($db);

$data = json_decode(file_get_contents("php://input"));

if (
    !empty($data->name) &&
    !empty($data->description) &&
    !empty($data->tag_id)
) {

    $task->name = $data->name;
    $task->description = $data->description;
    $task->tag_id = $data->tag_id;
    $task->created = date('Y-m-d H:i:s');

    if($task->create()){
        http_response_code(201);
        echo json_encode(array("message" => "Задача была создана."), JSON_UNESCAPED_UNICODE);
    } else {
        http_response_code(503);
        echo json_encode(array("message" => "Невозможно создать задачу."), JSON_UNESCAPED_UNICODE);
    }
} else {

    http_response_code(400);

    echo json_encode(array("message" => "Невозможно создать задачу. Данные неполные."), JSON_UNESCAPED_UNICODE);
}
