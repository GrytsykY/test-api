<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../config/Database.php';
include_once '../classes/Tag.php';

$database = new Database();
$db = $database->getConnection();

$tag = new Tag($db);

$stmt = $tag->read();
$num = $stmt->rowCount();

if ($num>0) {

    $tag_arr=array();
    $tag_arr["records"]=array();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);

        $tag_item=array(
            "id" => $id,
            "name" => $name
        );

        array_push($tag_arr["records"], $tag_item);
    }

    http_response_code(200);

    echo json_encode($tag_arr);
} else {

    http_response_code(404);

    echo json_encode(array("message" => "Теги не найдены."), JSON_UNESCAPED_UNICODE);
}