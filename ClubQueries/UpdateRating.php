<?php
try {
    include('../Connection.php');
    $EncodedData = file_get_contents("php://input");
    $DecodedData = json_decode($EncodedData, true);

    $clubID = $DecodedData['clubID'];
    $userID = $DecodedData['userID'];
    $rating = $DecodedData['rating'];

    $stmt = $conn->prepare("SELECT rating FROM clubrating WHERE clubID = ? AND userEmail = ?");
    $stmt->bind_param("is", $clubID, $userID);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($existingRating);
    $ratingExists = $stmt->num_rows > 0;
    $stmt->fetch();
    $stmt->close();

    if ($ratingExists) {
        // Update the user's rating
        $stmt = $conn->prepare("UPDATE clubrating SET rating = ? WHERE clubID = ? AND userEmail = ?");
        $stmt->bind_param("iis", $rating, $clubID, $userID);
        $stmt->execute();
        $stmt->close();

        // Retrieve current average rating and number of ratings
        $stmt = $conn->prepare("SELECT rating, numOfRatings FROM clubs WHERE ID = ?");
        $stmt->bind_param("i", $clubID);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($currentAverage, $numRatings);
        $stmt->fetch();
        $stmt->close();

        // Adjust the average rating considering the change in user's rating
        $newRating = round((($currentAverage * $numRatings) - $existingRating + $rating) / $numRatings, 1);

        // Update the club's rating
        $stmt = $conn->prepare("UPDATE clubs SET rating = ? WHERE ID = ?");
        $stmt->bind_param("di", $newRating, $clubID);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            $response = array("success" => true);
        } else {
            $response = array("success" => false, "message" => "Failed to update the club rating");
        }
        $stmt->close();
    } else {
        $response = array("success" => false, "message" => "Rating not found for the given user and club");
    }

} catch (Exception $e) {
    $response = array("success" => false, "message" => "Error: " . $e->getMessage());
}

header('Content-Type: application/json');
echo json_encode($response);
$conn->close();
?>
