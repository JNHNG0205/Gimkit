<?php
session_start();
include("conn.php");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['ticket_id']) && isset($_POST['reply'])) {
    $ticket_id = $_POST['ticket_id'];
    $response_text = $_POST['reply'];
    $user_id = $_SESSION['user_id']; // Assuming you have user sessions

    // Insert the reply into ticket_responses table
    $sql = "INSERT INTO ticket_responses (response_text, created_at, ticket_id, user_id) 
            VALUES (?, NOW(), ?, ?)";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("sii", $response_text, $ticket_id, $user_id);
    
    if ($stmt->execute()) {
        // Update the ticket's updated_at timestamp
        $update_sql = "UPDATE tickets SET updated_at = NOW() WHERE ticket_id = ?";
        $update_stmt = $con->prepare($update_sql);
        $update_stmt->bind_param("i", $ticket_id);
        $update_stmt->execute();
        $update_stmt->close();

        echo "<script>alert('Response submitted successfully.'); window.location.href='view_ticket.php';</script>";
    } else {
        echo "<script>alert('Error submitting response: " . $stmt->error . "'); window.location.href='view_ticket.php';</script>";
    }
    
    $stmt->close();
} else {
    echo "<script>alert('Invalid request'); window.location.href='view_ticket.php';</script>";
}

$con->close();
?>