<?php
try {
    include('../Connection.php');
    $EncodedData = file_get_contents("php://input");
    $DecodedData = json_decode($EncodedData, true);

    $ID = $DecodedData['ID'];
    $clubID = $DecodedData['clubID'];
    $name = $DecodedData['name'];
    $info = $DecodedData['info'];
    $type = $DecodedData['type'];
    $location = $DecodedData['location'];
    $date = $DecodedData['date'];
    $time = $DecodedData['time'];
    $campus = $DecodedData['campus'];

    $stmt = $conn->prepare(" UPDATE events SET clubID = ?, name = ?, info = ?, type = ?, location = ?, date = ?, time = ?, campus = ? WHERE ID = ?");
    $stmt->bind_param("ssssssssi", $clubID, $name, $info, $type, $location, $date, $time, $campus,$ID);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        $response = array("success" => true, "message" => "Event Updated");
    } else {
        $response = array("success" => false, "message" => "Something went wrong, please try again later");
    }
} catch (Exception $e) {
    $response = array("success" => false, "message" => $e->getCode());
}

header('Content-Type: application/json');
echo json_encode($response);
?>