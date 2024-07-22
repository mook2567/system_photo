<?php
session_start();
include '../config_db.php';

// Set the content type to JSON
header('Content-Type: application/json');

$sql = "SELECT * FROM `information`";
$resultInfo = $conn->query($sql);
$rowInfo = $resultInfo->fetch_assoc();

if (isset($_SESSION['photographer_login'])) {
    $email = $_SESSION['photographer_login'];
    $sql = "SELECT * FROM photographer WHERE photographer_email LIKE ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $resultPhoto = $stmt->get_result();
    $rowPhoto = $resultPhoto->fetch_assoc();
    $id_photographer = $rowPhoto['photographer_id'];
}

$booking = array();
if (isset($id_photographer)) {
    $stmt = $conn->prepare("SELECT * FROM booking WHERE photographer_id = ?");
    $stmt->bind_param("i", $id_photographer);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $booking[] = $row;
    }
}

// Return bookings data as JSON
echo json_encode($booking);
?>
