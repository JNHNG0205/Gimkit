<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css\internal_style.css">
    <title>Edit Comment</title>
</head>
<style>
    .container{
        background-color: rgba(0, 0, 0, 0.65);
        border-radius: 10px;
        box-shadow: 0 15px 50px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        width: 750px;
        height: auto;
        max-width: 90%;
        max-height: 80%;
        z-index: 10;
        padding: 40px;
        position: absolute;
        padding-bottom: 40px;
        top: 100px;
        overflow-y: auto;
    }
    h2{
        color: #F2DC91;
        z-index: 1001;
        font-size: 50px;
        font-family:'Courier New', Courier, monospace;
        text-decoration: underline;
        position: absolute;
    }
    h3{
        color: #fff;
        z-index: 1001;
        font-size: 28px;
        font-family:'Courier New', Courier, monospace;
        font-weight: bolder;
        text-decoration: underline;
        margin-top: 60px;
    }
    p{
        font-family: 'Courier New', Courier, monospace;
        z-index: 1001;
        font-size: 20px;
        color: #fff;
    }
    h4{
        color: #F2DC91;
        z-index: 1001;
        font-size: 24px;
        font-family:'Courier New', Courier, monospace;
        font-weight: bolder;
        text-decoration: underline;
        z-index: 1001;
    }
    button{
        width: 170px; 
        height:25px; 
        font-family: 'Courier New', Courier, monospace; 
        font-size: 13.5px;
        margin-top: 15px;
        font-weight: bold;
        z-index: 1001;
    }
    label{
        font-family: 'Courier New', Courier, monospace;
        z-index: 1001;
        font-size: 20px;
        color: #fff;
        position: absolute;
        margin-top: 10px;
        margin-bottom: 15px;
    }
</style>
<body>
    <button style="width: 180px; height:30px; font-family: Courier New, Courier, monospace; font-size: 20px; font-weight: bold; z-index: 1001; top: 35px; left: 30px; position: absolute;"><a href="home.php">Home</a></button>;
    <?php
    session_start();
    include("conn.php");

    if (isset($_GET['id'])) {
        $discussion_id = intval($_GET['id']); 
        $user_id = $_SESSION['user_id'];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $discussion_title = mysqli_real_escape_string($con, $_POST['discussion_title']);
            $discussion_text = mysqli_real_escape_string($con, $_POST['discussion_text']);
            $created_at = date('Y-m-d H:i:s'); 

            $update_discussion_sql = "UPDATE discussion SET discussion_title = '$discussion_title', discussion_text = '$discussion_text', created_at = '$created_at' WHERE discussion_id = $discussion_id AND user_id = $user_id";
            
            if (mysqli_query($con, $update_discussion_sql)) {
                // Handle image upload
                if (isset($_FILES['fileToUpload']) && $_FILES['fileToUpload']['error'] == 0) {
                    $target_dir = __DIR__ . "/uploads/";
                    $uploadOk = 1;

                    // Check if the file is an image
                    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
                    if ($check === false) {
                        $uploadOk = 0;
                        echo '<script>alert("File is not an image.");</script>';
                    }

                    // Generate unique filename
                    $original_filename = basename($_FILES["fileToUpload"]["name"]);
                    $fileType = strtolower(pathinfo($original_filename, PATHINFO_EXTENSION));
                    $base_filename = pathinfo($original_filename, PATHINFO_FILENAME);
                    $target_file = $target_dir . $base_filename . '.' . $fileType;

                    // Ensure file does not already exist
                    $counter = 1;
                    while (file_exists($target_file)) {
                        $target_file = $target_dir . $base_filename . '_' . $counter . '.' . $fileType;
                        $counter++;
                    }

                    // Limit file size to 5MB
                    if ($_FILES["fileToUpload"]["size"] > 5000000) {
                        $uploadOk = 0;
                        echo '<script>alert("Sorry, your file is too large.");</script>';
                    }

                    // Allow certain file formats
                    $allowed_extensions = array("jpg", "jpeg", "png", "gif");
                    if (!in_array($fileType, $allowed_extensions)) {
                        $uploadOk = 0;
                        echo '<script>alert("Sorry, only JPG, JPEG, PNG, and GIF files are allowed.");</script>';
                    }

                    if ($uploadOk == 1) {
                        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                            // Update the media record in the database
                            $media_URL = "uploads/" . basename($target_file);

                            // Delete old media record
                            $sql_delete_old = "DELETE FROM media WHERE discussion_id = ? AND media_type = 'image'";
                            $stmt_delete_old = $con->prepare($sql_delete_old);
                            $stmt_delete_old->bind_param("i", $discussion_id);
                            $stmt_delete_old->execute();

                            // Insert new media record
                            $sql_insert_new = "INSERT INTO media (media_type, media_URL, discussion_id) VALUES ('image', ?, ?)";
                            $stmt_insert_new = $con->prepare($sql_insert_new);
                            $stmt_insert_new->bind_param("si", $media_URL, $discussion_id);
                            $stmt_insert_new->execute();

                            $stmt_delete_old->close();
                            $stmt_insert_new->close();
                        } else {
                            echo '<script>alert("Sorry, there was an error uploading your file.");</script>';
                        }
                    }
                } elseif (isset($_POST['delete_image']) && $_POST['delete_image'] == '1') {
                    // Handle image deletion
                    $sql_delete_old = "DELETE FROM media WHERE discussion_id = ? AND media_type = 'image'";
                    $stmt_delete_old = $con->prepare($sql_delete_old);
                    $stmt_delete_old->bind_param("i", $discussion_id);
                    $stmt_delete_old->execute();

                    // Also delete the actual file from the server
                    $media_result = $con->query("SELECT media_URL FROM media WHERE discussion_id = $discussion_id AND media_type = 'image'");
                    if ($media_result && $media_row = $media_result->fetch_assoc()) {
                        $image_path = __DIR__ . '/' . $media_row['media_URL'];
                        if (file_exists($image_path)) {
                            unlink($image_path);
                        }
                    }

                    $stmt_delete_old->close();
                }

                header("Location: content.php");
                exit();
            } else {
                echo "Error updating post: " . mysqli_error($con);
            }
        } else {
            $sql = "SELECT * FROM discussion WHERE discussion_id = $discussion_id AND user_id = $user_id";
            $result = mysqli_query($con, $sql);

            if (mysqli_num_rows($result) > 0) {
                $row = mysqli_fetch_array($result);

                // Fetch current image if exists
                $image_sql = "SELECT media_URL FROM media WHERE discussion_id = $discussion_id AND media_type = 'image'";
                $image_result = mysqli_query($con, $image_sql);
                $image_url = "";
                if ($image_result && $image_row = mysqli_fetch_assoc($image_result)) {
                    $image_url = $image_row['media_URL'];
                }
                ?>
                <div class="container">
                    <form method="post" action="" enctype="multipart/form-data">
                        <label for="discussion_title">Post Title:</label>
                        <input type="text" style="width: 350px; height: 30px; overflow: hidden; font-family: 'Courier New', Courier, monospace; font-size: 17px; margin-top: 10px; margin-left:160px;" name="discussion_title" value="<?php echo htmlspecialchars($row['discussion_title']); ?>">
                        <br>
                        <label for="discussion_text">Post Text:</label>
                        <textarea style="width: 350px; min-height: 50px; height: auto; overflow: hidden; font-family: 'Courier New', Courier, monospace; font-size: 17px; margin-top: 10px; margin-left:160px;"name="discussion_text"><?php echo htmlspecialchars($row['discussion_text']); ?></textarea>
                        <br>

                        <?php if ($image_url): ?>
                            <img src="<?php echo $image_url; ?>" alt="Discussion Image" style="max-width: 300px; max-height: 200px;">
                            <br>
                            <label for="delete_image">Delete current image:</label>
                            <input type="checkbox" style="position: absolute; left: 42%; transform: translateX(-50%); z-index: 1001; margin-top: 15px;" name="delete_image" value="1">
                            <br>
                        <?php endif; ?>

                        <label for="fileToUpload">Upload new image:</label>
                        <input style="width: 170px; height:25px; font-family: 'Courier New', Courier, monospace; font-size: 13.5px; margin-top: 10px; font-weight: bold; z-index: 1001;margin-left:210px;" type="file" id="fileToUpload" name="fileToUpload" accept="image/*">
                        <br><br>
                        <input style="width: 170px; height:25px; font-family: 'Courier New', Courier, monospace; font-size: 13.5px; margin-top: 15px; font-weight: bold; z-index: 1001;" type="submit" type="submit" value="Update">
                    </form>
                </div>
                <?php
            } else {
                echo "No post found.";
            }
        }
    } else {
        echo "Invalid request.";
    }

    mysqli_close($con);
    ?>
    <section>
    <?php for ($i = 0; $i < 100; $i++) {
            echo "<span></span>";
            echo "<span></span>";
            echo "<span></span>";
            echo "<span></span>";
            echo "<span></span>";
            echo "<span></span>";
            echo "<span></span>";
            echo "<span></span>";
            echo "<span></span>";
            echo "<span></span>";
            echo "<span></span>";
            echo "<span></span>";
        } 
    ?>
    </section>

</body>
