<?php
session_start();
include '../config_db.php';

if (isset($_SESSION['photographer_login'])) {
    $email = $_SESSION['photographer_login'];
    $sql = "SELECT photographer_id FROM photographer WHERE photographer_email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $rowPhoto = $result->fetch_assoc();
    $id_photographer = $rowPhoto['photographer_id'];

    $stmt = $conn->prepare("SELECT * FROM booking WHERE photographer_id = ? AND booking_confirm_status = '1'");
    $stmt->bind_param("i", $id_photographer);
    $stmt->execute();
    $result = $stmt->get_result();

    $bookings = [];
    while ($row = $result->fetch_assoc()) {
        $bookings[] = $row;
    }

    echo json_encode($bookings);
} else {
    echo json_encode([]);
}
?>
