<?php
try {
    include('../Connection.php');
    $EncodedData = file_get_contents("php://input");
    $DecodedData = json_decode($EncodedData, true);


    $stmt =$conn->prepare("SELECT email FROM users where role != ?");

    $rolePattern = 'Global Admin';
    $stmt->bind_param("s", $rolePattern);

    $stmt->execute();
    $result = $stmt->get_result();

    $emails = array();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $emails[] = $row;
        }
        $response = array("success" => true, "data" => $emails);
    } else {
        $response = array("success" => false, "message" => "GetAllUsersEmails Request Failed");
    }
} catch (Exception $e) {
    $response = array("success" => false, "message" => "Error: " . $e->getCode());
}

header('Content-Type: application/json');
echo json_encode($response);

$conn->close();
?>







