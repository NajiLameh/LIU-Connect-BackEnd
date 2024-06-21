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
        $adminEmail = $row['adminEmail'];
        $stmt2 = $conn->prepare("DELETE FROM clubs WHERE id = ?");
        $stmt2->bind_param("i", $ID);
        $stmt2->execute();
        if($stmt2->affected_rows > 0){
            $stmt3 = $conn->prepare("UPDATE users SET role = ? WHERE email = ?");
            $rolePattern = "student";
            $stmt3->bind_param("ss",$rolePattern, $adminEmail);
            if($stmt3->execute()){
                $response = array("success" => true, "message" => "Club Deleted");
            } else {
                $response = array("success" => false, "message" => "error in setting admin to student");
            }
        } else{
            $response = array("success" => false, "message" => "error in deleting club");
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