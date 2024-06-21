<?php
try {
    include('../Connection.php');

    $query = "SELECT firstName, lastName, email, role FROM users";
    $result = $conn->query($query);

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