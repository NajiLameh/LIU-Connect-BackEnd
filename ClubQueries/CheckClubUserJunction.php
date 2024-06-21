<?php
try {
    include('../Connection.php');
    $EncodedData = file_get_contents("php://input");
    $DecodedData = json_decode($EncodedData, true);

    $clubID = $DecodedData['clubID'];
    $userID = $DecodedData['userID'];

    $stmt = $conn->prepare("SELECT 1 FROM joinclub WHERE clubID = ? AND userEmail = ?");
    $stmt->bind_param("is", $clubID,$userID); 
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $response = array("success" => true);
    } else {
        $response = array("success" => false);
    }
} catch (Exception $e) {
    $response = array("success" => false, "message" => "Error: " . $e->getMessage());
}
header('Content-Type: application/json');
echo json_encode($response);
$conn->close();
?>