<?php
try {
    include('../Connection.php');

    $EncodedData = file_get_contents("php://input");
    $DecodedData = json_decode($EncodedData, true);
    $ID = $DecodedData['ID']; 


    $stmt =$conn->prepare("SELECT name FROM clubs WHERE ID = ?");
    $stmt->bind_param("i", $ID); 
    $stmt->execute();
    $result = $stmt->get_result();
    $events = array();
    
    $stmt->execute();
    $result = $stmt->get_result();

    $eventClub = array();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $eventClub[] = $row;
        }
        $response = array("success" => true, "data" => $eventClub );
    } else {
        $response = array("success" => false, "message" => "No Clubs Found");
    }
} catch (Exception $e) {
    $response = array("success" => false, "message" => "Error: " . $e->getCode());
}

header('Content-Type: application/json');
echo json_encode($response);

$conn->close();
?>