<?php
try {
    include('../Connection.php');

    $EncodedData = file_get_contents("php://input");
    $DecodedData = json_decode($EncodedData, true);
    $email = $DecodedData['email'];

    $stmt =$conn->prepare("SELECT firstName,lastName,email,campus,phoneNumber,role FROM users where email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    $students = array();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }
        $response = array("success" => true, "data" => $users);
    } else {
        $response = array("success" => false, "message" => "No users Found");
    }
} catch (Exception $e) {
    $response = array("success" => false, "message" => "Error: " . $e->getCode());
}

header('Content-Type: application/json');
echo json_encode($response);

$conn->close();
?>