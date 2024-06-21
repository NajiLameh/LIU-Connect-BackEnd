<?php
try {
    include('../Connection.php');
    $EncodedData = file_get_contents("php://input");
    $DecodedData = json_decode($EncodedData, true);

    $ID = $DecodedData['ID'];


    $stmt = $conn->prepare("DELETE FROM internships WHERE id = ?");
    $stmt->bind_param("i", $ID);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        $response = array("success" => true, "message" => "Internship Deleted");
    } else {
        $response = array("success" => false, "message" => "Something went wrong, please try again later");
    }
} catch (Exception $e) {
    $response = array("success" => false, "message" => $e->getCode());
}

header('Content-Type: application/json');
echo json_encode($response);
?>