This is a backstage function. No style required.

<?php
session_start();
include("conn.php");

// Check if user is logged in and has admin privileges
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'helpdesk_support') {
    echo "<script>alert('You must be logged in as an Heldepsk Support to access this page.'); window.location.href = 'login.html';</script>";
    exit();
}

if (isset($_GET['faq_id'])) {
    $faq_id = $_GET['faq_id'];
    
    // Start transaction
    $con->begin_transaction();

    try {
        // Delete associated media first
        $delete_media_sql = "DELETE FROM media WHERE faq_id = ?";
        $delete_media_stmt = $con->prepare($delete_media_sql);
        $delete_media_stmt->bind_param("i", $faq_id);
        $delete_media_stmt->execute();
        $delete_media_stmt->close();

        // Delete the FAQ
        $delete_faq_sql = "DELETE FROM faq WHERE faq_id = ?";
        $delete_faq_stmt = $con->prepare($delete_faq_sql);
        $delete_faq_stmt->bind_param("i", $faq_id);
        $delete_faq_stmt->execute();
        $delete_faq_stmt->close();

        // Commit transaction
        $con->commit();

        echo "<script>alert('FAQ and associated media deleted successfully.'); window.location.href = 'editfaq.php';</script>";
    } catch (Exception $e) {
        // Rollback transaction on error
        $con->rollback();
        echo "<script>alert('Error: " . $e->getMessage() . "'); window.location.href = 'editfaq.php';</script>";
    }
} else {
    echo "<script>alert('No FAQ ID provided.'); window.location.href = 'editfaq.php';</script>";
}

$con->close();
?>