<?php
try {
    include('../Connection.php');
    $EncodedData = file_get_contents("php://input");
    $DecodedData = json_decode($EncodedData, true);

    $ID = $DecodedData['ID'];    
    $publisherEmail = $DecodedData['publisherEmail'];
    $name = $DecodedData['name'];
    $datePosted = $DecodedData['datePosted'];
    $company = $DecodedData['company'];
    $type = $DecodedData['type'];
    $location = $DecodedData['location'];
    $paid = $DecodedData['paid'];
    $remote = $DecodedData['remote'];
    $duration = $DecodedData['duration'];
    $internshipLink = $DecodedData['internshipLink'];


    $stmt = $conn->prepare("UPDATE internships SET publisherEmail = ?, name = ?, datePosted = ?, company = ?, type = ?, location = ?, paid = ?, remote = ?, duration = ?, internshipLink = ? WHERE ID = ?");
    $stmt->bind_param("ssssssssssi", $publisherEmail, $name, $datePosted, $company, $type, $location, $paid, $remote, $duration, $internshipLink,$ID);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        $response = array("success" => true, "message" => "Internship Updated");
    } else {
        $response = array("success" => false, "message" => "Something went wrong, please try again later");
    }
} catch (Exception $e) {
    $response = array("success" => false, "message" => $e->getCode());
}

header('Content-Type: application/json');
echo json_encode($response);
?>