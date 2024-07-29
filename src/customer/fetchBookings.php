<?php
session_start();
include '../config_db.php';

// Set the content type to JSON
header('Content-Type: application/json');

// Initialize an empty array for bookings
$booking = array();

try {
    // Fetch general information
    $sql = "SELECT * FROM `information`";
    $resultInfo = $conn->query($sql);
    if (!$resultInfo) {
        throw new Exception("Error fetching information: " . $conn->error);
    }
    $rowInfo = $resultInfo->fetch_assoc();

    // Fetch customer details if logged in
    if (isset($_SESSION['customer_login'])) {
        $email = $_SESSION['customer_login'];
        $sql = "SELECT * FROM customer WHERE cus_email = ?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Error preparing statement: " . $conn->error);
        }
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $resultCus = $stmt->get_result();
        if (!$resultCus) {
            throw new Exception("Error executing statement: " . $stmt->error);
        }
        $rowCus = $resultCus->fetch_assoc();
        $id_cus = $rowCus['cus_id'];
        $stmt->close(); // Close the statement
    }

    // Fetch bookings if photographer ID is available
    if (isset($id_photographer)) {
        // Prepare SQL query
        $sql = "SELECT * FROM `booking` WHERE photographer_id = ? AND booking_confirm_status = '1'";
        $stmt = $conn->prepare($sql);
    
        if (!$stmt) {
            throw new Exception("Error preparing statement: " . $conn->error);
        }
    
        // Bind parameters and execute statement
        $stmt->bind_param("i", $id_photographer);
        $stmt->execute();
        
        // Get result and handle errors
        $result = $stmt->get_result();
        if (!$result) {
            throw new Exception("Error executing statement: " . $stmt->error);
        }
    
        // Fetch results and populate $booking array
        while ($row = $result->fetch_assoc()) {
            $booking[] = $row;
        }

        $stmt->close(); // Close the statement
    }

    // Convert bookings to JSON
    echo json_encode($booking);

} catch (Exception $e) {
    // Handle error
    echo json_encode(array('error' => $e->getMessage()));
}
?>
