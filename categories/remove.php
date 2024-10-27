<?php
include_once '../db.php';

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: DELETE");

$database = new Database();
$db = $database->getConnection();

$data = json_decode(file_get_contents("php://input"));

if (!empty($data->Category_ID)) {
    $query = "DELETE FROM Categories WHERE Category_ID = :Category_ID";
    $stmt = $db->prepare($query);
    $stmt->bindParam(":Category_ID", $data->Category_ID);

    if ($stmt->execute()) {
        http_response_code(200);
        echo json_encode(array("message" => "Category deleted successfully."));
    } else {
        http_response_code(503);
        echo json_encode(array("message" => "Unable to delete category."));
    }
} else {
    http_response_code(400);
    echo json_encode(array("message" => "Incomplete data."));
}
?>
