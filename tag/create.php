<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/Database.php';
include_once '../classes/Tag.php';

$database = new Database();
$db = $database->getConnection();

$tag = new Tag($db);

$data = json_decode(file_get_contents("php://input"));

if (
    !empty($data->name)
) {

    $tag->name = $data->name;
    $tag->created = date('Y-m-d H:i:s');

    if($tag->create()){
        http_response_code(201);
        echo json_encode(array("message" => "Тег был создан."), JSON_UNESCAPED_UNICODE);
    } else {
        http_response_code(503);
        echo json_encode(array("message" => "Невозможно создать тег."), JSON_UNESCAPED_UNICODE);
    }
} else {

    http_response_code(400);

    echo json_encode(array("message" => "Невозможно создать тег. Данные неполные."), JSON_UNESCAPED_UNICODE);
}