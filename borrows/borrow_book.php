<?php
include_once '../db.php';

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");

$database = new Database();
$db = $database->getConnection();

$data = json_decode(file_get_contents("php://input"));

if (!empty($data->Borrower_Name) && !empty($data->Contact_Info) && !empty($data->Book_ID)) {
    // Check if borrower exists
    $query = "SELECT Borrower_ID FROM Borrowers WHERE Borrower_Name = :Borrower_Name";
    $stmt = $db->prepare($query);
    $stmt->bindParam(":Borrower_Name", htmlspecialchars(strip_tags($data->Borrower_Name)));
    $stmt->execute();

    if ($stmt->rowCount() == 0) {
        // Borrower doesn't exist, insert new borrower with contact info
        $insertQuery = "INSERT INTO Borrowers SET Borrower_Name=:Borrower_Name, Contact_Info=:Contact_Info";
        $insertStmt = $db->prepare($insertQuery);
        $insertStmt->bindParam(":Borrower_Name", htmlspecialchars(strip_tags($data->Borrower_Name)));
        $insertStmt->bindParam(":Contact_Info", htmlspecialchars(strip_tags($data->Contact_Info)));
        $insertStmt->execute();

        $borrower_id = $db->lastInsertId();
    } else {
        // Borrower exists, get their ID
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $borrower_id = $row['Borrower_ID'];
    }

    // Insert into Borrowed_Books
    $borrowQuery = "INSERT INTO Borrowed_Books SET Book_ID=:Book_ID, Borrower_ID=:Borrower_ID, Borrow_Date=NOW()";
    $borrowStmt = $db->prepare($borrowQuery);
    $borrowStmt->bindParam(":Book_ID", htmlspecialchars(strip_tags($data->Book_ID)));
    $borrowStmt->bindParam(":Borrower_ID", $borrower_id);

    if ($borrowStmt->execute()) {
        http_response_code(201);
        echo json_encode(array("message" => "Book borrowed successfully."));
    } else {
        http_response_code(503);
        echo json_encode(array("message" => "Unable to borrow the book."));
    }
} else {
    http_response_code(400);
    echo json_encode(array("message" => "Incomplete data."));
}
?>
