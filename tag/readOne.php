<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header("Content-Type: application/json");

include_once '../config/Database.php';
include_once '../classes/Tag.php';

$database = new Database();
$db = $database->getConnection();

$tag = new Tag($db);

$tag->id = isset($_GET['id']) ? $_GET['id'] : die();

$stmt = $tag->readOne();
$num = $stmt->rowCount();

if ($num>0) {

    $tag_arr=array();
    $tag_arr["records"]=array();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){

        extract($row);

        $tag_item=array(
            "id" => $id,
            "name" => $name,
            'created' => $created
        );

        array_push($tag_arr["records"], $tag_item);
    }


    http_response_code(200);

    echo json_encode($tag_arr,JSON_UNESCAPED_UNICODE);
} else {

    http_response_code(404);

    echo json_encode(array("message" => "Товары не найдены."), JSON_UNESCAPED_UNICODE);
}