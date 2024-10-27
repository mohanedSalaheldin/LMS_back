<?php
include_once '../db.php';

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: DELETE");

$database = new Database();
$db = $database->getConnection();

$data = json_decode(file_get_contents("php://input"));

if (!empty($data->Book_ID)) {
    $query = "DELETE FROM Books WHERE Book_ID = :Book_ID";
    $stmt = $db->prepare($query);
    $stmt->bindParam(":Book_ID", $data->Book_ID);

    if ($stmt->execute()) {
        http_response_code(200);
        echo json_encode(array("message" => "Book deleted successfully."));
    } else {
        http_response_code(503);
        echo json_encode(array("message" => "Unable to delete book."));
    }
} else {
    http_response_code(400);
    echo json_encode(array("message" => "Incomplete data."));
}
?>
