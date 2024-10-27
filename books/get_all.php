<?php
include_once '../db.php';

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

$database = new Database();
$db = $database->getConnection();

$query = "SELECT * FROM Books";
$stmt = $db->prepare($query);
$stmt->execute();

$num = $stmt->rowCount();

if ($num > 0) {
    $books_arr = array();
    $books_arr["books"] = array();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $book_item = array(
            "Book_ID" => $Book_ID,
            "Title" => $Title,
            "Author_ID" => $Author_ID,
            "Programming_Language" => $Programming_Language,
            "Category_ID" => $Category_ID,
            "Price" => $Price,
            "Published_Year" => $Published_Year
        );

        array_push($books_arr["books"], $book_item);
    }

    http_response_code(200);
    echo json_encode($books_arr);
} else {
    http_response_code(404);
    echo json_encode(array("message" => "No books found."));
}
?>
