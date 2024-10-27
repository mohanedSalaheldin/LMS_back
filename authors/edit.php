<?php
include_once '../db.php';

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: PUT");

$database = new Database();
$db = $database->getConnection();

// Get input data
$data = json_decode(file_get_contents("php://input"));


// Check if required data is present
if (!empty($data->Author_ID) && !empty($data->Author_Name) && !empty($data->Author_Bio)) {
    // Sanitize inputs
    $author_id = $data->Author_ID; // Assuming Author_ID is an integer and no sanitization is needed
    $author_name = htmlspecialchars(strip_tags($data->Author_Name));
    $author_bio = htmlspecialchars(strip_tags($data->Author_Bio));

    // Prepare the SQL query
    $query = "UPDATE Authors SET Author_Name = :Author_Name, Author_Bio = :Author_Bio WHERE Author_ID = :Author_ID";
    $stmt = $db->prepare($query);

    // Bind parameters
    $stmt->bindParam(":Author_Name", $author_name);
    $stmt->bindParam(":Author_Bio", $author_bio);
    $stmt->bindParam(":Author_ID", $author_id);

    // Execute the query and log errors if any
    if ($stmt->execute()) {
        http_response_code(200);
        echo json_encode(array("message" => "Author updated successfully."));
    } else {
        // Log the error if the execution fails
        $error_info = $stmt->errorInfo();
        http_response_code(503);
        echo json_encode(array("message" => "Unable to update author.", "error" => $error_info));
    }
} else {
    // Handle incomplete data
    http_response_code(400);
    echo json_encode(array("message" => "Incomplete data. Please provide Author_ID, Author_Name, and Author_Bio."));
}
?>
