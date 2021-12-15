<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header("Content-Type: application/json");

include_once '../config/Database.php';
include_once '../classes/Task.php';

$database = new Database();
$db = $database->getConnection();

$task = new Task($db);



$task->id = isset($_GET['id']) ? $_GET['id'] : die();

$stmt = $task->readOne();
$num = $stmt->rowCount();

if ($num>0) {

    $task_arr=array();
    $task_arr["records"]=array();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){

        extract($row);

        $task_item=array(
            "id" => $id,
            "name" => $name,
            "description" => html_entity_decode($description),
            "tag_id" => $tag_id,
            'created' => $created
        );

        array_push($task_arr["records"], $task_item);
    }


    http_response_code(200);

    echo json_encode($task_arr,JSON_UNESCAPED_UNICODE);
} else {

    http_response_code(404);

    echo json_encode(array("message" => "Товары не найдены."), JSON_UNESCAPED_UNICODE);
}