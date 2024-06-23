
<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate incoming data (you can use additional validation as per your requirements)
    if (!isset($_POST['userid']) || !isset($_POST['msg']) || !isset($_POST['created_on'])) {
        http_response_code(400);
        echo json_encode(array("status" => 0, "message" => "Incomplete data"));
        exit;
    }

    // Sanitize and store incoming data
    $userid = $_POST['userid'];
    $msg = $_POST['msg'];
    $created_on = $_POST['created_on'];

    // Insert data into the database
    require_once('database/ChatRooms.php'); // Adjust the path as per your file structure

    $chat_object = new ChatRooms();
    $chat_object->setUserId($userid);
    $chat_object->setMessage($msg);
    $chat_object->setCreatedOn($created_on);

    try {
        $chat_object->save_chat();
        echo json_encode(array("status" => 1, "message" => "Chat saved successfully"));
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(array("status" => 0, "message" => "Database error: " . $e->getMessage()));
    }
} else {
    http_response_code(405); // Method Not Allowed
    echo json_encode(array("status" => 0, "message" => "Method not allowed"));
}
?>
