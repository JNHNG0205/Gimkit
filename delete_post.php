
<?php
session_start();
include("conn.php");

if (isset($_GET['id']) && isset($_SESSION['user_id'])) {
    $discussion_id = intval($_GET['id']);
    $user_id = $_SESSION['user_id'];

    mysqli_begin_transaction($con);

    try {
        // Delete comments associated with the discussion
        $sql_comments = "DELETE FROM comment WHERE discussion_id = $discussion_id";
        if (!mysqli_query($con, $sql_comments)) {
            throw new Exception("Error deleting comments: " . mysqli_error($con));
        }

        // Delete the image associated with the discussion
        $sql_image = "SELECT media_URL FROM media WHERE discussion_id = $discussion_id AND media_type = 'image'";
        $image_result = mysqli_query($con, $sql_image);

        if ($image_result && mysqli_num_rows($image_result) > 0) {
            $image_row = mysqli_fetch_assoc($image_result);
            $image_url = $image_row['media_URL'];

            // Delete the image file from the server
            if (file_exists($image_url)) {
                unlink($image_url);
            }

            // Delete the image entry from the database
            $sql_delete_image = "DELETE FROM media WHERE discussion_id = $discussion_id AND media_type = 'image'";
            if (!mysqli_query($con, $sql_delete_image)) {
                throw new Exception("Error deleting image record: " . mysqli_error($con));
            }
        }

        // Delete the discussion
        $sql_discussion = "DELETE FROM discussion WHERE discussion_id = $discussion_id AND user_id = $user_id";
        if (!mysqli_query($con, $sql_discussion)) {
            throw new Exception("Error deleting discussion: " . mysqli_error($con));
        }

        // Commit the transaction
        mysqli_commit($con);
        echo '<script>alert("Record deleted!"); window.location.href="content.php";</script>';
    } catch (Exception $e) {
        // Rollback the transaction in case of error
        mysqli_rollback($con);
        error_log($e->getMessage());
        echo '<script>alert("Error deleting record: ' . $e->getMessage() . '"); window.location.href="content.php";</script>';
    }

    mysqli_close($con);
} else {
    echo '<script>alert("Invalid request or session expired!"); window.location.href="content.php";</script>';
}
?>
