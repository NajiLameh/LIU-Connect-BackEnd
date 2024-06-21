<?php
ob_start();

try {
    include('../Connection.php');

    $EncodedData = file_get_contents("php://input");
    $DecodedData = json_decode($EncodedData, true);
    $ID = $DecodedData['ID'];
    $password = $DecodedData['password'];

    if (str_contains($ID,'liu.edu.lb')){
        $stmt = $conn->prepare("SELECT email,firstName, password FROM users WHERE email = ?");
    } else {
        $stmt = $conn->prepare("SELECT email,firstName, password FROM users WHERE userName = ?");
    }

    $stmt->bind_param("s", $ID);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $hashedPassword = $row['password'];
        if (password_verify($password, $hashedPassword)) {
            $response = array(
                "success" => true,
                "message" => "Welcome Back, " . $row['firstName'], 
                "ID" => $row['email']
            );
        } else {
            $response = array(
                "success" => false,
                "message" => "Wrong username or password", 
            );
        }
    } else {
        $response = array(
            "success" => false,
            "message" => "Wrong username or password", 
        );
    }
} catch (Exception $e) {
    $response = array(
        "success" => false,
        "message" => "Error: " . $e->getMessage(),
    );
}
header('Content-Type: application/json');
echo json_encode($response);

ob_end_flush();

$conn->close();
?>