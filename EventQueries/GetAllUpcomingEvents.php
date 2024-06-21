<?php
try {
    include('../Connection.php');
    $EncodedData = file_get_contents("php://input");
    $DecodedData = json_decode($EncodedData, true);
    $ID = $DecodedData['ID'];
    $date = $DecodedData['date'];
    $time = $DecodedData['time'];
    $datetime = $date . ' ' . $time;

$stmt = $conn->prepare("SELECT e.* FROM events e 
                        JOIN joinclub jc ON e.clubID = jc.clubID
                        WHERE jc.userEmail = ? 
                        AND STR_TO_DATE(CONCAT(e.date, ' ', e.time), '%e %M %Y %H:%i') >= STR_TO_DATE(?, '%e %M %Y %H:%i')
                        ORDER BY STR_TO_DATE(CONCAT(e.date, ' ', e.time), '%e %M %Y %H:%i') ASC");
                            
    $stmt->bind_param("ss", $ID, $datetime); 
    $stmt->execute();
    $result = $stmt->get_result();
    $events = array();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $events[] = $row;
        }
        $response = array("success" => true, "data" => $events);
    } else {
        $response = array("success" => false, "message" => "No Upcoming Events");
    }
} catch (Exception $e) {
    $response = array("success" => false, "message" => "Error: " . $e->getMessage());
}
header('Content-Type: application/json');
echo json_encode($response);
$conn->close();
?>