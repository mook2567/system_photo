<?php
session_start();
include '../config_db.php';

// Set the content type to JSON
header('Content-Type: application/json');

// Initialize an empty array for bookings
$bookings = array();

try {
    // Check if customer is logged in
    if (isset($_SESSION['customer_login'])) {
        $email = $_SESSION['customer_login'];
        $stmtCus = $conn->prepare("SELECT * FROM customer WHERE cus_email = ?");
        if (!$stmtCus) {
            throw new Exception("Error preparing statement: " . $conn->error);
        }
        $stmtCus->bind_param("s", $email);
        $stmtCus->execute();
        $resultCus = $stmtCus->get_result();
        if (!$resultCus) {
            throw new Exception("Error executing statement: " . $stmtCus->error);
        }
        $rowCus = $resultCus->fetch_assoc();
        $id_cus = $rowCus['cus_id'];
        $stmtCus->close(); // Close the statement

        // Define photographer ID (replace this with the actual logic)
        $id_photographer = 1; // Example value, replace with actual logic

        // Fetch bookings for the photographer
        $stmt = $conn->prepare("SELECT * FROM booking WHERE photographer_id = ? AND booking_confirm_status = '1'");
        if (!$stmt) {
            throw new Exception("Error preparing statement: " . $conn->error);
        }
        $stmt->bind_param("i", $id_photographer);
        $stmt->execute();
        $result = $stmt->get_result();
        if (!$result) {
            throw new Exception("Error executing statement: " . $stmt->error);
        }

        // Fetch bookings and populate the array
        while ($row = $result->fetch_assoc()) {
            $bookings[] = $row;
        }
        $stmt->close(); // Close the statement

        // Output bookings as JSON
        echo json_encode($bookings);
    } else {
        echo json_encode([]);
    }
} catch (Exception $e) {
    // Handle error
    echo json_encode(array('error' => $e->getMessage()));
}
?>
