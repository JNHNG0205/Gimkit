<?php
session_start();
include("conn.php");

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submitBtn'])) {
    if (isset($_SESSION['user_id']) && isset($_POST['discussion_title']) && isset($_POST['discussion_text'])) {
        $user_id = $_SESSION['user_id'];
        $discussion_title = mysqli_real_escape_string($con, $_POST['discussion_title']);
        $discussion_text = mysqli_real_escape_string($con, $_POST['discussion_text']);

        $sql_check_user = "SELECT user_id FROM user WHERE user_id = ?";
        $stmt_check_user = $con->prepare($sql_check_user);
        $stmt_check_user->bind_param("i", $user_id);
        $stmt_check_user->execute();
        $stmt_check_user->store_result();

        if ($stmt_check_user->num_rows === 0) {
            $message = "Invalid user. Please log in and try again.";
            error_log("User ID not found in 'user' table: " . $user_id);
        } else {
            $sql_discussion = "INSERT INTO discussion (discussion_title, discussion_text, user_id) VALUES (?, ?, ?)";
            $stmt_discussion = $con->prepare($sql_discussion);
            $stmt_discussion->bind_param("ssi", $discussion_title, $discussion_text, $user_id);

            if ($stmt_discussion->execute()) {
                $discussion_id = $stmt_discussion->insert_id;

                if (isset($_FILES["fileToUpload"]) && $_FILES["fileToUpload"]["error"] == 0) {
                    $title = $_POST["discussion_title"];
                    $description = $_POST["discussion_text"];
                    $media_type = 'image'; // Default to 'image'

                    $target_dir = __DIR__ . "/uploads/";

                    if (!file_exists($target_dir)) {
                        mkdir($target_dir, 0755, true);
                    }

                    $original_filename = basename($_FILES["fileToUpload"]["name"]);
                    $fileType = strtolower(pathinfo($original_filename, PATHINFO_EXTENSION));
                    $base_filename = pathinfo($original_filename, PATHINFO_FILENAME);

                    // Determine the media type
                    if (in_array($fileType, ['jpg', 'jpeg', 'png', 'gif'])) {
                        $media_type = 'image';
                    } elseif (in_array($fileType, ['pdf', 'mp4', 'avi', 'mov'])) {
                        $media_type = 'document'; // or 'video' based on your needs
                    } else {
                        $media_type = 'other'; // Default type for unsupported formats
                    }

                    $counter = 1;
                    $target_file = $target_dir . $base_filename . '.' . $fileType;
                    while (file_exists($target_file)) {
                        $target_file = $target_dir . $base_filename . '_' . $counter . '.' . $fileType;
                        $counter++;
                    }

                    $uploadOk = 1;

                    if ($_FILES["fileToUpload"]["size"] > 5000000) {
                        $message = "Sorry, your file is too large.";
                        $uploadOk = 0;
                    }

                    $allowed_extensions = array("jpg", "jpeg", "png", "gif", "pdf", "mp4", "avi", "mov");
                    if (!in_array($fileType, $allowed_extensions)) {
                        $message = "Sorry, only JPG, JPEG, PNG, GIF, PDF, MP4, AVI, and MOV files are allowed.";
                        $uploadOk = 0;
                    }

                    if ($uploadOk == 1) {
                        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                            $media_URL = "uploads/" . basename($target_file);

                            $sql_media = "INSERT INTO media (media_type, media_URL, discussion_id) VALUES (?, ?, ?)";
                            $stmt_media = $con->prepare($sql_media);
                            $stmt_media->bind_param("ssi", $media_type, $media_URL, $discussion_id);

                            if ($stmt_media->execute()) {
                                $message = "Discussion and associated media have been uploaded successfully.";
                            } else {
                                $message = "Error saving media information: " . $con->error;
                                error_log("Error saving media information: " . $con->error);
                            }
                            $stmt_media->close();
                        } else {
                            $message = "Error uploading your file.";
                            error_log("File upload error: " . error_get_last()['message']);
                        }
                    }
                } else {
                    $message = "Discussion added successfully without media.";
                }
            } else {
                $message = "Error inserting discussion: " . $con->error;
                error_log("Error inserting discussion: " . $con->error);
            }
            $stmt_discussion->close();
        }
        $stmt_check_user->close();
    } else {
        $message = "Please fill in the form completely!";
    }
    mysqli_close($con);
    echo "<script>alert('$message');window.location.href='forumhome.php';</script>";
} else {
    echo "<script>alert('Please fill in the form!');window.location.href='forumhome.php';</script>";
}
?>
