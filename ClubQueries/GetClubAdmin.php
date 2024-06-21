<?php
try {
    include('../Connection.php');
    $EncodedData = file_get_contents("php://input");
    $DecodedData = json_decode($EncodedData, true);

    $ID = $DecodedData['ID'];

    $stmt = $conn->prepare('SELECT adminEmail FROM clubadmins WHERE clubID = ?');
    $stmt->bind_param('i',$ID);
    $stmt->execute();
    $result = $stmt->get_result();
    if($result && $result->num_rows > 0){
        $row = $result->fetch_assoc();
        $response = array("success" => true, "clubAdminEmail" => $row['adminEmail']);
    } else {
        $response = array("success" => false, "message" => "error in fetching club admin");
    }
} catch (Exception $e) {
    $response = array("success" => false, "message" => $e->getCode());
}

header('Content-Type: application/json');
echo json_encode($response);
?>