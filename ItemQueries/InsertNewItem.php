<?php
try {
    include('../Connection.php');
    $EncodedData = file_get_contents("php://input");
    $DecodedData = json_decode($EncodedData, true);

    $publisherEmail = $DecodedData['publisherEmail'];
    $name = $DecodedData['name'];
    $description = $DecodedData['description'];
    $condition = $DecodedData['condition'];
    $category = $DecodedData['category'];
    $campus = $DecodedData['campus'];
    $price = $DecodedData['price'];

    $stmt = $conn->prepare("INSERT INTO items (publisherEmail, name, description, `condition`, category, campus, price) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssi", $publisherEmail, $name, $description, $condition, $category, $campus, $price);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        $response = array("success" => true, "message" => "item Added Successfully");
    } else {
        $response = array("success" => false, "message" => "Something went wrong, please try again later");
    }
} catch (Exception $e) {
    $response = array("success" => false, "message" => $e->getCode());
}

header('Content-Type: application/json');
echo json_encode($response);
?>