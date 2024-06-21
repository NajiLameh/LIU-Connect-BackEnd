<?php
try {
    include('../Connection.php');

    $stmt =$conn->prepare("SELECT * FROM events");
    
    $stmt->execute();
    $result = $stmt->get_result();

    $events = array();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $events[] = $row;
        }
        $response = array("success" => true, "data" => $events);
    } else {
        $response = array("success" => false, "message" => "No Events Found");
    }
} catch (Exception $e) {
    $response = array("success" => false, "message" => "Error: " . $e->getCode());
}

header('Content-Type: application/json');
echo json_encode($response);

$conn->close();
?>