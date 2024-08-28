
<?php
session_start();
include("conn.php");

if (isset($_GET['id']) && isset($_SESSION['user_id'])) {
    $comment_id = intval($_GET['id']); 
    $user_id = $_SESSION['user_id'];

    mysqli_begin_transaction($con);

    try {
        // Fetch media associated with the comment
        $sql_media = "SELECT media_URL FROM media WHERE comment_id = $comment_id";
        $media_result = mysqli_query($con, $sql_media);
        
        if ($media_result && mysqli_num_rows($media_result) > 0) {
            while ($media_row = mysqli_fetch_array($media_result)) {
                $media_URL = $media_row['media_URL'];
                // Delete media file from the server
                if (file_exists(__DIR__ . '/' . $media_URL)) {
                    unlink(__DIR__ . '/' . $media_URL);
                }
            }
        }

        // Delete media records from the database
        $sql_delete_media = "DELETE FROM media WHERE comment_id = $comment_id";
        if (!mysqli_query($con, $sql_delete_media)) {
            throw new Exception("Error deleting media: " . mysqli_error($con));
        }

        // Delete the comment
        $sql_delete_comment = "DELETE FROM comment WHERE comment_id = $comment_id AND user_id = $user_id";
        if (!mysqli_query($con, $sql_delete_comment)) {
            throw new Exception("Error deleting comment: " . mysqli_error($con));
        }

        // Commit the transaction
        mysqli_commit($con);
        echo '<script>alert("Record deleted!"); window.location.href="contentmanager.php";</script>';
    } catch (Exception $e) {
        // Rollback the transaction in case of error
        mysqli_rollback($con);
        echo '<script>alert("Error deleting record: ' . $e->getMessage() . '"); window.location.href="contentmanager.php";</script>';
    }

    mysqli_close($con);
} else {
    echo '<script>alert("Invalid request or session expired!"); window.location.href="contentmanager.php";</script>';
}
?>



