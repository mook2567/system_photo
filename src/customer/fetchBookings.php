<?php
session_start();
include '../config_db.php';

// Set the content type to JSON
header('Content-Type: application/json');

// Initialize an empty array for bookings
$booking = array();

try {
    // Fetch information
    $sql = "SELECT * FROM `information`";
    $resultInfo = $conn->query($sql);
    if (!$resultInfo) {
        throw new Exception("Error fetching information: " . $conn->error);
    }
    $rowInfo = $resultInfo->fetch_assoc();

    // Fetch photographer details if logged in
    if (isset($_SESSION['photographer_login'])) {
        $email = $_SESSION['photographer_login'];
        $sql = "SELECT * FROM photographer WHERE photographer_email = ?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Error preparing statement: " . $conn->error);
        }
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $resultPhoto = $stmt->get_result();
        if (!$resultPhoto) {
            throw new Exception("Error executing statement: " . $stmt->error);
        }
        $rowPhoto = $resultPhoto->fetch_assoc();
        $id_photographer = $rowPhoto['photographer_id'];
    }

    // Fetch bookings if photographer ID is available
    if (isset($id_photographer)) {
        $sql = "SELECT * FROM `booking` WHERE photographer_id = ? AND booking_confirm_status = '2'";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Error preparing statement: " . $conn->error);
        }
        $stmt->bind_param("i", $id_photographer);
        $stmt->execute();
        $result = $stmt->get_result();
        if (!$result) {
            throw new Exception("Error executing statement: " . $stmt->error);
        }

        while ($row = $result->fetch_assoc()) {
            $booking[] = $row;
        }
    }

    // Return bookings data as JSON
    echo json_encode($booking);

} catch (Exception $e) {
    // Handle error
    echo json_encode(array('error' => $e->getMessage()));
}
?>
