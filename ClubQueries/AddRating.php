<?php
try {
    include('../Connection.php');
    $EncodedData = file_get_contents("php://input");
    $DecodedData = json_decode($EncodedData, true);

    $clubID = $DecodedData['clubID'];
    $userID = $DecodedData['userID'];
    $rating = $DecodedData['rating'];

    // Insert the new rating
    $stmt = $conn->prepare("INSERT INTO clubrating (clubID, userEmail, rating) VALUES (?, ?, ?)");
    $stmt->bind_param("isi", $clubID, $userID, $rating); 
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        $stmt->close();  // Close the statement to free up resources

        // Retrieve current average rating and number of ratings
        $stmt = $conn->prepare("SELECT rating, numOfRatings FROM clubs WHERE ID = ?");
        $stmt->bind_param("i", $clubID);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($currentAverage, $numRatings);
        $stmt->fetch();
        
        if ($stmt->num_rows > 0) {
            // Calculate new average rating
            $newRating = round((($currentAverage * $numRatings) + $rating) / ($numRatings + 1), 1);
            $newNumRatings = $numRatings + 1;

            // Update the club's rating
            $stmt->close();  // Close the previous statement
            $stmt = $conn->prepare("UPDATE clubs SET rating = ?, numOfRatings = ? WHERE ID = ?");
            $stmt->bind_param("dii", $newRating, $newNumRatings, $clubID);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                $response = array("success" => true);
            } else {
                $response = array("success" => false, "message" => "Failed to update the club rating");
            }
            $stmt->close();
        } else {
            $response = array("success" => false, "message" => "Club not found");
        }
    } else {
        $response = array("success" => false, "message" => "Failed to insert rating");
    }
} catch (Exception $e) {
    $response = array("success" => false, "message" => "Error: " . $e->getMessage());
}

header('Content-Type: application/json');
echo json_encode($response);
$conn->close();
?>
