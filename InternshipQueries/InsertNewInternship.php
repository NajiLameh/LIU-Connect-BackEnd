<?php
try {
    include('../Connection.php');
    $EncodedData = file_get_contents("php://input");
    $DecodedData = json_decode($EncodedData, true);

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


    $stmt = $conn->prepare("INSERT INTO internships (publisherEmail, name, datePosted, company, type, location, paid, remote, duration, internshipLink) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssssss", $publisherEmail, $name, $datePosted, $company, $type, $location, $paid, $remote, $duration, $internshipLink);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        $response = array("success" => true, "message" => "Internship Added");
    } else {
        $response = array("success" => false, "message" => "Something went wrong, please try again later");
    }
} catch (Exception $e) {
    $response = array("success" => false, "message" => $e->getCode());
}

header('Content-Type: application/json');
echo json_encode($response);
?>