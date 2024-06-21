<?php
try {
    include('../Connection.php');
    $EncodedData = file_get_contents("php://input");
    $DecodedData = json_decode($EncodedData, true);

    $clubID = $DecodedData['clubID'];
    $userID = $DecodedData['userID'];

    $stmt = $conn->prepare("INSERT INTO joinclub (clubID,userEmail) VALUES(?,?)");
    $stmt->bind_param("is", $clubID,$userID); 
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
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