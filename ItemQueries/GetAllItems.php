<?php
try {
    include('../Connection.php');

    $stmt =$conn->prepare("SELECT * FROM items");
    
    $stmt->execute();
    $result = $stmt->get_result();

    $items = array();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $items[] = $row;
        }
        $response = array("success" => true, "data" => $items);
    } else {
        $response = array("success" => false, "message" => "No items Found");
    }
} catch (Exception $e) {
    $response = array("success" => false, "message" => "Error: " . $e->getCode());
}

header('Content-Type: application/json');
echo json_encode($response);

$conn->close();
?>