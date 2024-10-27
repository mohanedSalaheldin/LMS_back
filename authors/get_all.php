<?php
// Include the database connection
include_once '../db.php';

// Set headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Initialize database connection
$database = new Database();
$db = $database->getConnection();

// SQL query to fetch all authors
$query = "SELECT * FROM Authors";
$stmt = $db->prepare($query);
$stmt->execute();

// Get the number of records
$num = $stmt->rowCount();

if ($num > 0) {
    // Create an array to hold the authors
    $authors_arr = array();
    $authors_arr["authors"] = array();

    // Fetch data from the authors table
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        extract($row);

        $author_item = array(
            "Author_ID" => $Author_ID,
            "Author_Name" => $Author_Name,
            "Author_Bio" => $Author_Bio
        );

        array_push($authors_arr["authors"], $author_item);
    }

    // Set response code to 200 OK and output data in JSON format
    http_response_code(200);
    echo json_encode($authors_arr);
} else {
    // Set response code to 404 Not Found if no authors are found
    http_response_code(404);
    echo json_encode(array("message" => "No authors found."));
}
?>
