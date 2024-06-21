<?php
try {
    include('../Connection.php');
    $EncodedData = file_get_contents("php://input");
    $DecodedData = json_decode($EncodedData, true);

    $imageURL = $DecodedData['imageURL'];
    $name = $DecodedData['name'];
    $description = $DecodedData['description'];
    $department = $DecodedData['department'];
    $status = $DecodedData['status'];
    $campus = $DecodedData['campus'];
    $rating = $DecodedData['rating'];
    $facebookLink = $DecodedData['facebookLink'];
    $instagramLink = $DecodedData['instagramLink'];
    $linkedinLink = $DecodedData['linkedinLink'];
    $adminEmail = $DecodedData['adminEmail'];


    $stmt = $conn->prepare("INSERT INTO clubs (imageURL, name, description, department, status, campus, rating, facebookLink, instagramLink, linkedinLink) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssisss", $imageURL, $name, $description, $department, $status, $campus, $rating, $facebookLink, $instagramLink, $linkedinLink);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        $lastInsertedId = $conn->insert_id;
        $stmt2 = $conn->prepare("INSERT INTO clubadmins (clubID, adminEmail) VALUES (?, ?)");
        $stmt2->bind_param("is", $lastInsertedId, $adminEmail);
        $stmt2->execute();
        if ($stmt2->affected_rows > 0){
            $stmt3 = $conn->prepare("UPDATE users SET role = ? WHERE email = ?");
            $rolePattern = "Club Admin";
            $stmt3->bind_param("ss",$rolePattern,$adminEmail);
            if ($stmt3->execute()){
                $response = array("success" => true, "message" => "Club Added Successfully");
            } else {
                $response = array("success" => false, "message" => "Error in updating user role");
            }
        } else {
            $response = array("success" => false, "message" => "Error in adding to clubadmin");
        }
    } else {
        $response = array("success" => false, "message" => "Error in inserting new club");
    }
} catch (Exception $e) {
    $response = array("success" => false, "message" => $e->getCode());
}

header('Content-Type: application/json');
echo json_encode($response);
?>