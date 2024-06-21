<?php
try {
    include('../Connection.php');
    $EncodedData = file_get_contents("php://input");
    $DecodedData = json_decode($EncodedData, true);

    $ID = $DecodedData['ID'];
    $publisherEmail = $DecodedData['publisherEmail'];
    $name = $DecodedData['name'];
    $description = $DecodedData['description'];
    $condition = $DecodedData['condition'];
    $category = $DecodedData['category'];
    $campus = $DecodedData['campus'];
    $price = $DecodedData['price'];

    $stmt = $conn->prepare("UPDATE items SET publisherEmail = ?, name = ?, description = ?, `condition` = ?, category = ?, campus = ?, price = ? WHERE ID = ?");
    $stmt->bind_param("sssssssi", $publisherEmail, $name, $description, $condition, $category, $campus, $price,$ID);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        $response = array("success" => true, "message" => "item Updated");
    } else {
        $response = array("success" => false, "message" => "Something went wrong, please try again later");
    }
} catch (Exception $e) {
    $response = array("success" => false, "message" => $e->getCode());
}

header('Content-Type: application/json');
echo json_encode($response);
?>