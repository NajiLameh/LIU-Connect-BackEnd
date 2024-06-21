<?php
try {
    include('../Connection.php');

    $stmt =$conn->prepare("SELECT ID,name FROM clubs");
    
    $stmt->execute();
    $result = $stmt->get_result();

    $clubs = array();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $clubs[] = $row;
        }
        $response = array("success" => true, "data" => $clubs);
    } else {
        $response = array("success" => false, "message" => "No Clubs Found");
    }
} catch (Exception $e) {
    $response = array("success" => false, "message" => "Error: " . $e->getCode());
}

header('Content-Type: application/json');
echo json_encode($response);

$conn->close();
?>