<?php
include_once '../db.php';

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: DELETE");

$database = new Database();
$db = $database->getConnection();

$data = json_decode(file_get_contents("php://input"));

if (!empty($data->Book_ID) && !empty($data->Borrower_ID)) {
    // Check if the book is borrowed by the specific borrower
    $query = "SELECT Borrowed_ID FROM Borrowed_Books WHERE Book_ID = :Book_ID AND Borrower_ID = :Borrower_ID";
    $stmt = $db->prepare($query);
    $stmt->bindParam(":Book_ID", htmlspecialchars(strip_tags($data->Book_ID)));
    $stmt->bindParam(":Borrower_ID", $data->Borrower_ID);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        // Book is borrowed by this borrower, proceed to return
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $borrowed_id = $row['Borrowed_ID'];

        // Delete the entry from Borrowed_Books
        $deleteQuery = "DELETE FROM Borrowed_Books WHERE Borrowed_ID = :Borrowed_ID";
        $deleteStmt = $db->prepare($deleteQuery);
        $deleteStmt->bindParam(":Borrowed_ID", $borrowed_id);

        if ($deleteStmt->execute()) {
            http_response_code(200);
            echo json_encode(array("message" => "Book returned successfully."));
        } else {
            http_response_code(503);
            echo json_encode(array("message" => "Unable to return the book."));
        }
    } else {
        http_response_code(404);
        echo json_encode(array("message" => "No record found for this book and borrower."));
    }
} else {
    http_response_code(400);
    echo json_encode(array("message" => "Incomplete data."));
}
?>
