<?php
try {
    include('Connection.php');

    $stmtU =$conn->prepare("SELECT Count(*) AS rowCount FROM users");
    $stmtU->execute();
    $resultU = $stmtU->get_result();

    $stmtC =$conn->prepare("SELECT Count(*) AS rowCount FROM clubs");
    $stmtC->execute();
    $resultC = $stmtC->get_result();

    $stmtE =$conn->prepare("SELECT Count(*) AS rowCount FROM events");
    $stmtE->execute();
    $resultE = $stmtE->get_result();

    $stmtI =$conn->prepare("SELECT Count(*) AS rowCount FROM items");
    $stmtI->execute();
    $resultI = $stmtI->get_result();

    $stmtIN =$conn->prepare("SELECT Count(*) AS rowCount FROM internships");
    $stmtIN->execute();
    $resultIN = $stmtIN->get_result();

    if ($resultU && 
        $resultC && 
        $resultE && 
        $resultI && 
        $resultIN) {

        $rowU = $resultU->fetch_assoc();
        $rowC = $resultC->fetch_assoc();
        $rowE = $resultE->fetch_assoc();
        $rowI = $resultI->fetch_assoc();
        $rowIN = $resultIN->fetch_assoc();
        $response = array("success" => true, 
                        "usersCount" => $rowU['rowCount'],
                        "clubsCount" => $rowC['rowCount'] - 1,
                        "eventsCount" => $rowE['rowCount'],
                        "itemsCount" => $rowI['rowCount'],
                        "internshipsCount" => $rowIN['rowCount'],
                        );
    } else {
        $response = array("success" => false, "message" => "Stats Failed");
    }
} catch (Exception $e) {
    $response = array("success" => false, "message" => "Error: " . $e->getCode());
}

header('Content-Type: application/json');
echo json_encode($response);

$conn->close();
?>