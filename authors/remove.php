<?php
include_once '../db.php';

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: DELETE");

$database = new Database();
$db = $database->getConnection();

$data = json_decode(file_get_contents("php://input"));

if (!empty($data->Author_ID)) {
    // Assign the Author_ID to a variable
    $author_id = $data->Author_ID;  // Assuming Author_ID is an integer and doesn't need sanitization

    // Prepare the SQL query
    $query = "DELETE FROM Authors WHERE Author_ID = :Author_ID";
    $stmt = $db->prepare($query);

    // Bind the Author_ID parameter
    $stmt->bindParam(":Author_ID", $author_id);

    // Execute the query
    if ($stmt->execute()) {
        http_response_code(200);
        echo json_encode(array("message" => "Author deleted successfully."));
    } else {
        http_response_code(503);
        echo json_encode(array("message" => "Unable to delete author."));
    }
} else {
    http_response_code(400);
    echo json_encode(array("message" => "Incomplete data."));
}
?>
