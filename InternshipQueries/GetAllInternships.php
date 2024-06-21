<?php
try {
    include('../Connection.php');

    $stmt =$conn->prepare("SELECT * FROM internships");
    
    $stmt->execute();
    $result = $stmt->get_result();

    $internships = array();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $internships[] = $row;
        }
        $response = array("success" => true, "data" => $internships);
    } else {
        $response = array("success" => false, "message" => "No internships Found");
    }
} catch (Exception $e) {
    $response = array("success" => false, "message" => "Error: " . $e->getCode());
}

header('Content-Type: application/json');
echo json_encode($response);

$conn->close();
?>







