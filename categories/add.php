<?php
include_once '../db.php';

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");

$database = new Database();
$db = $database->getConnection();

$data = json_decode(file_get_contents("php://input"));

if (!empty($data->Category_Name)) {
    $query = "INSERT INTO Categories SET Category_Name=:Category_Name";
    $stmt = $db->prepare($query);

    // Sanitize and assign to a variable
    $category_name = htmlspecialchars(strip_tags($data->Category_Name));

    // Bind the variable to the prepared statement
    $stmt->bindParam(":Category_Name", $category_name);

    if ($stmt->execute()) {
        http_response_code(201);
        echo json_encode(array("message" => "Category added successfully."));
    } else {
        http_response_code(503);
        echo json_encode(array("message" => "Unable to add category."));
    }
} else {
    http_response_code(400);
    echo json_encode(array("message" => "Incomplete data."));
}
?>
