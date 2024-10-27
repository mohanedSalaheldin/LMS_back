<?php
include_once '../db.php';

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");

$database = new Database();
$db = $database->getConnection();

// Get input data
$data = json_decode(file_get_contents("php://input"));



// Check if required data is present
if (!empty($data->Author_Name) && !empty($data->Author_Bio)) {
    // Sanitize inputs
    $author_name = htmlspecialchars(strip_tags($data->Author_Name));
    $author_bio = htmlspecialchars(strip_tags($data->Author_Bio));

    // Prepare the SQL query
    $query = "INSERT INTO Authors (Author_Name, Author_Bio) VALUES (:Author_Name, :Author_Bio)";
    $stmt = $db->prepare($query);

    // Bind parameters
    $stmt->bindParam(":Author_Name", $author_name);
    $stmt->bindParam(":Author_Bio", $author_bio);

    // Execute the query and catch errors
    if ($stmt->execute()) {
        http_response_code(201);
        echo json_encode(array("message" => "Author added successfully."));
    } else {
        // Log the error if the execution fails
        $error_info = $stmt->errorInfo();
        http_response_code(503);
        echo json_encode(array("message" => "Unable to add author.", "error" => $error_info));
    }
} else {
    // Handle incomplete data
    http_response_code(400);
    echo json_encode(array("message" => "Incomplete data. Please provide both Author_Name and Author_Bio."));
}
?>
