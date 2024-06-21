<?php
try {
    include('../Connection.php');
    $EncodedData = file_get_contents("php://input");
    $DecodedData = json_decode($EncodedData, true);

    $ID = $DecodedData['ID'];
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

    $stmt = $conn->prepare('SELECT adminEmail FROM clubadmins WHERE clubID = ?');
    $stmt->bind_param('i',$ID);
    $stmt->execute();
    $result = $stmt->get_result();
    if($result && $result->num_rows > 0){
        $row = $result->fetch_assoc();
        $oldAdminEmail = $row['adminEmail'];
        $stmt2 = $conn->prepare("UPDATE clubs SET imageURL = ?, name = ?, description = ?, department = ?, status = ?, campus = ?, rating = ?, facebookLink = ?, instagramLink = ?, linkedinLink = ? WHERE ID = ?");
        $stmt2->bind_param("ssssssisssi", $imageURL, $name, $description, $department, $status, $campus, $rating, $facebookLink, $instagramLink, $linkedinLink, $ID);
        $stmt2->execute();
        if($stmt2->execute()){
            $stmt3 = $conn->prepare("UPDATE users SET role = ? WHERE email = ?");
            $rolePattern = "Club Admin";
            $stmt3->bind_param("ss",$rolePattern,$adminEmail);
            $stmt3->execute();
            if($stmt3->execute()){
                $stmt4 = $conn->prepare("UPDATE clubadmins SET adminEmail = ?  WHERE clubID = ?");
                $stmt4->bind_param("si", $adminEmail,$ID);
                $stmt4->execute();
                if($stmt4->execute()){
                    if($adminEmail != $oldAdminEmail){
                        $stmt5 = $conn->prepare("UPDATE users SET role = ? WHERE email = ?");
                        $rolePattern = "student";
                        $stmt5->bind_param("ss",$rolePattern,$oldAdminEmail);
                        $stmt5->execute();
                        if($stmt5->execute()){
                            $response = array("success" => true, "message" => "Club Edited Successfully");
                        } else {
                            $response = array("success" => false, "message" => "error in setting old admin to student");
                        }
                    } else {
                        $response = array("success" => true, "message" => "Club Edited Successfully");
                    }
                } else {
                    $response = array("success" => false, "message" => "error in updating clubadmin table");
                }
            } else {
                $response = array("success" => false, "message" => "error in setting new club admin role in users");
            }
        } else {
            $response = array("success" => false, "message" => "error in updating clubadmin table");
        }
    } else {
        $response = array("success" => false, "message" => "error in fetching club admin");
    }
} catch (Exception $e) {
    $response = array("success" => false, "message" => $e->getCode());
}

header('Content-Type: application/json');
echo json_encode($response);
?>