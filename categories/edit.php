<?php
include_once '../db.php';

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: PUT");

$database = new Database();
$db = $database->getConnection();

$data = json_decode(file_get_contents("php://input"));

if (!empty($data->Category_ID) && !empty($data->Category_Name)) {
    $query = "UPDATE Categories SET Category_Name = :Category_Name WHERE Category_ID = :Category_ID";
    $stmt = $db->prepare($query);

    // Sanitize and assign to a variable
    $category_name = htmlspecialchars(strip_tags($data->Category_Name));

    // Bind the variable to the prepared statement
    $stmt->bindParam(":Category_Name", $category_name);
    $stmt->bindParam(":Category_ID", $data->Category_ID);

    if ($stmt->execute()) {
        http_response_code(200);
        echo json_encode(array("message" => "Category updated successfully."));
    } else {
        http_response_code(503);
        echo json_encode(array("message" => "Unable to update category."));
    }
} else {
    http_response_code(400);
    echo json_encode(array("message" => "Incomplete data."));
}
?>
