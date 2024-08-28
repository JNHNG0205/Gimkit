This is a backstage function. No style required.


<?php
session_start();
include("conn.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['discussion_id']) && isset($_POST['comment_text']) && isset($_SESSION['user_id'])) {
    $discussion_id = intval($_POST['discussion_id']);
    $user_id = $_SESSION['user_id'];
    $comment_text = mysqli_real_escape_string($con, $_POST['comment_text']);
    $created_at = date('Y-m-d H:i:s');

    mysqli_begin_transaction($con);

    try {
        // Insert comment
        $sql_comment = "INSERT INTO comment (discussion_id, user_id, comment_text, created_at) VALUES (?, ?, ?, ?)";
        $stmt_comment = $con->prepare($sql_comment);
        $stmt_comment->bind_param("iiss", $discussion_id, $user_id, $comment_text, $created_at);
        if (!$stmt_comment->execute()) {
            throw new Exception("Error adding comment: " . $con->error);
        }
        $comment_id = $con->insert_id;

        // Handle file upload
        if (isset($_FILES["fileToUpload"]) && $_FILES["fileToUpload"]["error"] == 0) {
            $target_dir = __DIR__ . "/uploads/";
            if (!file_exists($target_dir)) {
                mkdir($target_dir, 0755, true);
            }

            $original_filename = basename($_FILES["fileToUpload"]["name"]);
            $fileType = strtolower(pathinfo($original_filename, PATHINFO_EXTENSION));
            $base_filename = pathinfo($original_filename, PATHINFO_FILENAME);

            // Generate a unique filename
            $counter = 1;
            $target_file = $target_dir . $base_filename . '.' . $fileType;
            while (file_exists($target_file)) {
                $target_file = $target_dir . $base_filename . '_' . $counter . '.' . $fileType;
                $counter++;
            }

            // Validate and move uploaded file
            $allowed_extensions = array("jpg", "jpeg", "png", "gif");
            if ($_FILES["fileToUpload"]["size"] > 5000000 || !in_array($fileType, $allowed_extensions)) {
                throw new Exception("Invalid file.");
            }
            if (!move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                throw new Exception("Error uploading file.");
            }

            // Insert media record
            $media_URL = "uploads/" . basename($target_file);
            $sql_media = "INSERT INTO media (media_type, media_URL, comment_id) VALUES ('image', ?, ?)";
            $stmt_media = $con->prepare($sql_media);
            $stmt_media->bind_param("si", $media_URL, $comment_id);
            if (!$stmt_media->execute()) {
                throw new Exception("Error saving media information: " . $con->error);
            }
        }

        // Commit transaction
        mysqli_commit($con);
        header("Location: forumhome.php");
        exit();
    } catch (Exception $e) {
        mysqli_rollback($con);
        echo "Error: " . $e->getMessage();
    }

    mysqli_close($con);
} else {
    echo "Invalid request.";
}
?>
