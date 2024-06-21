<?php
try {
    include('../Connection.php');
    $EncodedData = file_get_contents("php://input");
    $DecodedData = json_decode($EncodedData, true);

    $clubID = $DecodedData['clubID'];
    $name = $DecodedData['name'];
    $info = $DecodedData['info'];
    $type = $DecodedData['type'];
    $location = $DecodedData['location'];
    $date = $DecodedData['date'];
    $time = $DecodedData['time'];
    $campus = $DecodedData['campus'];

    $stmt = $conn->prepare("INSERT INTO events (clubID, name, info, type, location, date, time, campus) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssssss", $clubID, $name, $info, $type, $location, $date, $time, $campus);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        $response = array("success" => true, "message" => "Event Added");
    } else {
        $response = array("success" => false, "message" => "Something went wrong, please try again later");
    }
} catch (Exception $e) {
    $response = array("success" => false, "message" => $e->getCode());
}

header('Content-Type: application/json');
echo json_encode($response);
?>