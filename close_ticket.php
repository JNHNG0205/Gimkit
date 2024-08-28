<?php
session_start();
include("conn.php");

function closeTicket($con, $ticket_id, $user_id) {
    $updated_at = date('Y-m-d H:i:s');

    // Start transaction
    mysqli_begin_transaction($con);

    try {
        // Update ticket status to 'closed'
        $sql_update = "UPDATE tickets SET status = 'closed', updated_at = ? WHERE ticket_id = ? AND user_id = ?";
        $stmt_update = mysqli_prepare($con, $sql_update);
        
        if ($stmt_update) {
            mysqli_stmt_bind_param($stmt_update, "sii", $updated_at, $ticket_id, $user_id);
            
            if (!mysqli_stmt_execute($stmt_update)) {
                throw new Exception("Error updating ticket status: " . mysqli_stmt_error($stmt_update));
            }
            
            mysqli_stmt_close($stmt_update);
        } else {
            throw new Exception("Error in preparing update statement: " . mysqli_error($con));
        }

        // If we've made it this far without exceptions, commit the transaction
        mysqli_commit($con);

        return "Ticket closed successfully.";
    } catch (Exception $e) {
        // An error occurred; rollback the transaction
        mysqli_rollback($con);
        return "Error: " . $e->getMessage();
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['ticket_id'])) {
    $ticket_id = $_POST['ticket_id'];
    
    $sql = "UPDATE tickets SET status = 'closed', updated_at = NOW() WHERE ticket_id = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("i", $ticket_id);
    
    if ($stmt->execute()) {
        echo "Ticket closed successfully";
    } else {
        echo "Error closing ticket: " . $con->error;
    }
    
    $stmt->close();
} else {
    echo "Invalid request";
}

$con->close();
?>
