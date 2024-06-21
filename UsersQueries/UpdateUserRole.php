<?php
try {
    include('../Connection.php');
    $EncodedData = file_get_contents("php://input");
    $DecodedData = json_decode($EncodedData, true);

    $email = $DecodedData['email'];
    $role = $DecodedData['role'];

    $stmt = $conn->prepare("UPDATE users SET role = ? WHERE email = ?");
    $stmt->bind_param("ss", $role, $email);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        $response = array("success" => true, "message" => "role Changed Successfully");
    } else {
        $response = array("success" => false, "message" => "Something went wrong, please try again later");
    }
} catch (Exception $e) {
    $response = array("success" => false, "message" => $e->getCode());
}

header('Content-Type: application/json');
echo json_encode($response);
?>