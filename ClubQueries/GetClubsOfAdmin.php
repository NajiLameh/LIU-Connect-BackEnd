<?php
try {
    include('../Connection.php');
    $EncodedData = file_get_contents("php://input");
    $DecodedData = json_decode($EncodedData, true);

    $adminEmail = $DecodedData['adminEmail']; 

    $stmt = $conn->prepare("SELECT *
                            FROM clubs c
                            JOIN clubadmins ca ON c.ID = ca.clubID
                            WHERE ca.adminEmail = ?");
    $stmt->bind_param("s", $adminEmail); 
    $stmt->execute();
    $result = $stmt->get_result();
    $events = array();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $events[] = $row;
        }
        $response = array("success" => true, "data" => $events);
    } else {
        $response = array("success" => false, "message" => "No events Found");
    }
} catch (Exception $e) {
    $response = array("success" => false, "message" => "Error: " . $e->getMessage());
}
header('Content-Type: application/json');
echo json_encode($response);
$conn->close();
?>