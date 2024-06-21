<?php
try {
    include('../Connection.php');
    $EncodedData = file_get_contents("php://input");
    $DecodedData = json_decode($EncodedData, true);

    $imageURL = $DecodedData['imageURL'];
    $userName = $DecodedData['userName'];
    $firstName = $DecodedData['firstName'];
    $lastName = $DecodedData['lastName'];
    $email = $DecodedData['email'];
    $password = $DecodedData['password'];
    $phoneNumber = $DecodedData['phoneNumber'];
    $campus = $DecodedData['campus'];
    $role = $DecodedData['role'];

    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    $stmt = $conn->prepare("INSERT INTO users (imageURL, userName, firstName, lastName, email, password, phoneNumber, campus, role) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssss", $imageURL, $userName, $firstName, $lastName, $email, $hashedPassword, $phoneNumber, $campus, $role);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        $response = array("success" => true, "message" => "Registration Successful");
    } else {
        $response = array("success" => false, "message" => "Registration Failed");
    }
} catch (Exception $e) {
    $response = array("success" => false, "message" => $e->getCode());
}

header('Content-Type: application/json');
echo json_encode($response);
?>