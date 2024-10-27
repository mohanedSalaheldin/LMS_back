<?php
include_once '../db.php';

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: PUT");

$database = new Database();
$db = $database->getConnection();

$data = json_decode(file_get_contents("php://input"));

if (!empty($data->Book_ID) && !empty($data->Title) && !empty($data->Author_ID) && !empty($data->Programming_Language) && !empty($data->Category_ID) && !empty($data->Price) && !empty($data->Published_Year)) {
    
    $query = "UPDATE Books 
              SET Title = :Title, Author_ID = :Author_ID, Programming_Language = :Programming_Language, Category_ID = :Category_ID, Price = :Price, Published_Year = :Published_Year 
              WHERE Book_ID = :Book_ID";

    $stmt = $db->prepare($query);

    // Assign values to variables first
    $Book_ID = $data->Book_ID;
    $Title = htmlspecialchars(strip_tags($data->Title));
    $Author_ID = $data->Author_ID;
    $Programming_Language = htmlspecialchars(strip_tags($data->Programming_Language));
    $Category_ID = $data->Category_ID;
    $Price = $data->Price;
    $Published_Year = $data->Published_Year;

    // Bind the variables to the statement
    $stmt->bindParam(":Book_ID", $Book_ID);
    $stmt->bindParam(":Title", $Title);
    $stmt->bindParam(":Author_ID", $Author_ID);
    $stmt->bindParam(":Programming_Language", $Programming_Language);
    $stmt->bindParam(":Category_ID", $Category_ID);
    $stmt->bindParam(":Price", $Price);
    $stmt->bindParam(":Published_Year", $Published_Year);

    if ($stmt->execute()) {
        http_response_code(200);
        echo json_encode(array("message" => "Book updated successfully."));
    } else {
        http_response_code(503);
        echo json_encode(array("message" => "Unable to update book."));
    }
} else {
    http_response_code(400);
    echo json_encode(array("message" => "Incomplete data."));
}
?>
