<?php
try {
    include('../Connection.php');

    $EncodedData = file_get_contents("php://input");
    $DecodedData = json_decode($EncodedData, true);
    $email = $DecodedData['email'];

    $stmt =$conn->prepare("SELECT role FROM users WHERE email = ?");
    
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $response = array("success" => true, "message" => $row['role']);
    } else {
        $response = array("success" => false, "message" => "Abort");
    }
} catch (Exception $e) {
    $response = array("success" => false, "message" => "Error: " . $e->getCode());
}
header('Content-Type: application/json');
echo json_encode($response);

$conn->close();
?>







