<?php
include_once '../db.php';

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");

$database = new Database();
$db = $database->getConnection();

$data = json_decode(file_get_contents("php://input"));

if (!empty($data->Title) && !empty($data->Author_ID) && !empty($data->Programming_Language) && !empty($data->Category_ID) && !empty($data->Price) && !empty($data->Published_Year)) {
    $query = "INSERT INTO Books (Title, Author_ID, Programming_Language, Category_ID, Price, Published_Year) 
              VALUES (:Title, :Author_ID, :Programming_Language, :Category_ID, :Price, :Published_Year)";

    $stmt = $db->prepare($query);

    // Assign values to variables first
    $Title = htmlspecialchars(strip_tags($data->Title));
    $Author_ID = $data->Author_ID;
    $Programming_Language = htmlspecialchars(strip_tags($data->Programming_Language));
    $Category_ID = $data->Category_ID;
    $Price = $data->Price;
    $Published_Year = $data->Published_Year;

    // Then bind the variables
    $stmt->bindParam(":Title", $Title);
    $stmt->bindParam(":Author_ID", $Author_ID);
    $stmt->bindParam(":Programming_Language", $Programming_Language);
    $stmt->bindParam(":Category_ID", $Category_ID);
    $stmt->bindParam(":Price", $Price);
    $stmt->bindParam(":Published_Year", $Published_Year);

    if ($stmt->execute()) {
        http_response_code(201);
        echo json_encode(array("message" => "Book added successfully."));
    } else {
        http_response_code(503);
        echo json_encode(array("message" => "Unable to add book."));
    }
} else {
    http_response_code(400);
    echo json_encode(array("message" => "Incomplete data."));
}
?>
