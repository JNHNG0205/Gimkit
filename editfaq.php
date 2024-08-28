<?php
session_start();
include("conn.php");

// Check if user is logged in and has admin privileges
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'helpdesk_support') {
    echo "<script>alert('You must be logged in as an Helpdesk Support to access this page.'); window.location.href = 'login.html';</script>";
    exit();
}

$message = ''; // Variable to store messages for the user

// Function to edit FAQ
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_faq'])) {
    $faq_id = $_POST['faq_id'];
    $faq_title = $_POST['faq_title'];
    $faq_contents = $_POST['faq_contents'];
    $media_type = $_POST['media_type'];
    
    if ($faq_title != "" && $faq_contents != "") {
        $sql = "UPDATE faq SET faq_title = ?, faq_contents = ? WHERE faq_id = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("ssi", $faq_title, $faq_contents, $faq_id);
        
        if ($stmt->execute()) {
            // Handle file upload
            if (isset($_FILES['fileToUpload']) && $_FILES['fileToUpload']['error'] == 0) {
                $target_dir = "uploads/";
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

                // Check file size (limit to 5MB)
                if ($_FILES["fileToUpload"]["size"] > 5000000) {
                    $message = "FAQ updated, but the file is too large to upload.";
                } else {
                    // Allow certain file formats
                    $allowed_extensions = array("jpg", "jpeg", "png", "gif", "pdf", "mp4", "avi", "mov");
                    if (in_array($fileType, $allowed_extensions)) {
                        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                            $media_URL = basename($target_file);
                            
                            // Check if media already exists for this FAQ
                            $check_media_sql = "SELECT * FROM media WHERE faq_id = ?";
                            $check_media_stmt = $con->prepare($check_media_sql);
                            $check_media_stmt->bind_param("i", $faq_id);
                            $check_media_stmt->execute();
                            $check_media_result = $check_media_stmt->get_result();
                            
                            if ($check_media_result->num_rows > 0) {
                                // Update existing media
                                $update_media_sql = "UPDATE media SET media_type = ?, media_URL = ? WHERE faq_id = ?";
                                $update_media_stmt = $con->prepare($update_media_sql);
                                $update_media_stmt->bind_param("ssi", $media_type, $media_URL, $faq_id);
                                $update_media_stmt->execute();
                                $update_media_stmt->close();
                            } else {
                                // Insert new media
                                $insert_media_sql = "INSERT INTO media (media_type, media_URL, faq_id) VALUES (?, ?, ?)";
                                $insert_media_stmt = $con->prepare($insert_media_sql);
                                $insert_media_stmt->bind_param("ssi", $media_type, $media_URL, $faq_id);
                                $insert_media_stmt->execute();
                                $insert_media_stmt->close();
                            }
                            
                            $check_media_stmt->close();
                            $message = "FAQ and media updated successfully.";
                        } else {
                            $message = "FAQ updated, but there was an error uploading the file.";
                        }
                    } else {
                        $message = "FAQ updated, but only JPG, JPEG, PNG, GIF, PDF, MP4, AVI, and MOV files are allowed for media.";
                    }
                }
            } else {
                $message = "FAQ updated successfully. No new media uploaded.";
            }
        } else {
            $message = "Error updating FAQ: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $message = "Please fill in all fields.";
    }
}

// Fetch all FAQs
$sql = "SELECT f.*, m.media_type, m.media_URL FROM faq f LEFT JOIN media m ON f.faq_id = m.faq_id";
$result = $con->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="logo1.png">
    <title>Edit FAQ</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Bagel+Fat+One&family=Quicksand:wght@300..700&display=swap');
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Bagel Fat One', sans-serif;
            cursor: url('../images/icons8-cursor-100.png'), auto;
        }

        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: #010326;
        }

        .container {
            position: absolute;
            background-color: rgba(1, 3, 38, 0.95);
            backdrop-filter: blur(10px);
            webkit-backdrop-filter: blur(10px);
            border-radius: 10px;
            box-shadow: 0 15px 50px rgba(0, 0, 0, 0.1);
            overflow-y: auto; /* Add vertical scrolling */
            width: 800px;
            max-width: 90%;
            z-index: 10;
            padding: 40px;
            max-height: 80vh; /* Set maximum height to 80% of viewport height */
            border: 1px solid rgba(242, 220, 145, 0.2); /* Subtle golden border */
        }

        h1 {
            color: #F2DC91;
            position: absolute;
            top: 0;
            background-color: rgba(1, 3, 38, 0.95);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            padding: 10px 0;
            z-index: 1001;
            margin-top: 0px; /* To offset the container's top padding */
            margin-left: -20px;
            padding-left: 20px;
            padding-right: 20px;
            align: center;

        }

        form {
            margin-bottom: 20px;
            padding: 20px;
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 5px;
            transition: all 0.3s ease;
        }

        form:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }

        label {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 5px;
            color: #F2DC91;
            font-family: 'Bagel Fat One', sans-serif;
        }

        input, textarea, select {
            width: 100%;
            padding: 8px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            background-color: #f4f4f4;
            color: #333;
        }

        textarea {
            resize: vertical;
            min-height: 100px;
            font-family: 'Bagel Fat One', sans-serif;
        }

        input[type="submit"], button {
            display: flex;
            justify-content: center;
            width: 200px;
            margin: 10px 0;
            padding: 10px;
            background-color: #F2DC91;
            color: #010326;
            text-align: center;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
            font-family: 'Bagel Fat One', sans-serif;
            cursor: pointer;
        }

        input[type="submit"]:hover, button:hover {
            background-color: #e0ca7f;
        }

        .delete-btn {
            background-color: #DF564A;
        }

        .delete-btn:hover {
            background-color: #c94d42;
        }

        .media-preview {
            max-width: 200px;
            max-height: 200px;
            margin-top: 10px;
            border-radius: 5px;
        }

        .loaderBg {
            background-color: #010326;
            padding: 0;
            margin: 0;
            width: 100vw;
            height: 100vh;
            display: grid;
            place-items: center center;
            z-index: 1009;
            transition: opacity 4s, visibility 3.8s;
            position: fixed;
            top: 0;
            left: 0;
        }

        .loader {
            position: relative;
            height: 350px;
            width: 350px;
            animation: spin 1.5s infinite;
            z-index: 1100;
            transition: opacity 3.8s, visibility 3.6s;
        }

        @keyframes spin {
            100% {
                transform: rotate(360deg);
            }
        }

        .loader--hidden, .loaderBg--hidden {
            opacity: 0;
            visibility: hidden;
        }

        .loader > div {
            position: absolute;
            border-radius: 50%;
        }

        .loader > div:nth-child(1) {
            height: 75px;
            width: 75px;
            background-image: linear-gradient(45deg, #DF564A, #DFB54A);
            top: 100px;
            left: 100px;
            box-shadow: 0 0 3px #DF564A;
            animation: move1 1.5s infinite;
        }

        .loader > div:nth-child(2) {
            height: 45px;
            width: 45px;
            background-image: linear-gradient(45deg, #F2DC91, #F4D08A);
            top: 105px;
            right: 110px;
            box-shadow: 0 0 2px #F2DC91;
            animation: move2 1.5s infinite;
        }

        .loader > div:nth-child(3) {
            height: 75px;
            width: 75px;
            background-image: linear-gradient(45deg, #D99E6A, #D5D9AD);
            bottom: 100px;
            right: 100px;
            box-shadow: 0 0 3px #D99E6A;
            animation: move3 1.5s infinite;
        }

        .loader > div:nth-child(4) {
            height: 45px;
            width: 45px;
            background-image: linear-gradient(45deg, #B6D9AD, #CDB667);
            bottom: 105px;
            left: 110px;
            box-shadow: 0 0 3px #B6D9AD;
            animation: move4 1.5s infinite;
        }

        @keyframes move1 {
            50% { transform: translate(-30px,-30px) scale(0.3); }
        }

        @keyframes move2 {
            50% { transform: translate(15px,-20px) scale(0.55); }
        }

        @keyframes move3 {
            50% { transform: translate(30px,30px) scale(0.3); }
        }

        @keyframes move4 {
            50% { transform: translate(-15px,20px) scale(0.55); }
        }
        .animation{
            z-index: 1;
            width: 300px;
        }
        .cursor{
            width: 20px;
            height: 20px;
            border:1px;
            position: absolute;
        }

        section{
            position:absolute;
            width:100vw;
            height:100vh;
            display:flex;
            justify-content:center;
            align-items:center;
            gap:2px;
            flex-wrap:wrap;
            overflow: hidden;
        }

        section::before{
            content:'';
            position: absolute;
            width: 100%;
            height: 100%;
            background:linear-gradient(#010326, #965edb, #9249e1,rgb(127, 45, 165),rgb(138, 33, 173), #010326);
            animation: animate 10s linear infinite;
        }

        @keyframes animate {
            0% {
                transform: translateY(-100%);
            }
            100% {
                transform: translateY(100%);
            }
        }

        section span {
            position: relative;
            display: block;
            width: calc(6.25vw - 2px);
            height: calc(6.25vw - 2px);
            border-radius: 30%;
            background: #222567;
            z-index: 2;
            transition: 1.3s;
        }

        section span:hover {
            background: #00ddff;
            transition: 0s;
        }
        .container::-webkit-scrollbar {
            width: 10px;
        }

        .container::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 5px;
        }

        .container::-webkit-scrollbar-thumb {
            background: #F2DC91;
            border-radius: 5px;
        }

        .container::-webkit-scrollbar-thumb:hover {
            background: #e0ca7f;
        }
        form {
            margin-bottom: 20px;
            padding: 20px;
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 5px;
            transition: all 0.3s ease;
        }

        input, textarea, select {
            width: 100%;
            max-width: 100%; /* Ensure inputs don't overflow */
        }
        .home-button {
            position: absolute;
            top: 20px;
            right: 20px;
            z-index: 1002;
            padding: 10px 20px;
            background-color: #F2DC91;
            color: #000;
            text-decoration: none;
            font-family: 'Bagel Fat One', sans-serif;
            font-weight: bold;
            border-radius: 5px;
        }
   </style>
</head>
<body>

    <div class="cursor"></div>>

    <h1>Edit FAQ</h1>
    <a href="home.php" class="home-button">Home</a>
    <section>
        <?php for ($i = 0; $i < 300; $i++): ?>
            <span></span>
        <?php endfor; ?>
    </section>

    <div class="container">
 
        <?php if (!empty($message)) echo "<p>$message</p>"; ?>

        <?php while ($row = $result->fetch_assoc()): ?>
        <form method="post" action="" enctype="multipart/form-data">
            <input type="hidden" name="faq_id" value="<?php echo $row['faq_id']; ?>">

            <label for="faq_title_<?php echo $row['faq_id']; ?>">FAQ Title:</label>
            <input type="text" id="faq_title_<?php echo $row['faq_id']; ?>" name="faq_title" value="<?php echo htmlspecialchars($row['faq_title']); ?>" required>

            <label for="faq_contents_<?php echo $row['faq_id']; ?>">FAQ Contents:</label>
            <textarea id="faq_contents_<?php echo $row['faq_id']; ?>" name="faq_contents" rows="4" cols="50" required><?php echo htmlspecialchars($row['faq_contents']); ?></textarea>

            <label for="media_type_<?php echo $row['faq_id']; ?>">Media Type:</label>
            <select name="media_type" id="media_type_<?php echo $row['faq_id']; ?>">
                <option value="image" <?php echo ($row['media_type'] == 'image') ? 'selected' : ''; ?>>Image</option>
                <option value="video" <?php echo ($row['media_type'] == 'video') ? 'selected' : ''; ?>>Video</option>
                <option value="slides" <?php echo ($row['media_type'] == 'slides') ? 'selected' : ''; ?>>Slides</option>
            </select>

            <label for="fileToUpload_<?php echo $row['faq_id']; ?>">Choose File:</label>
            <input type="file" name="fileToUpload" id="fileToUpload_<?php echo $row['faq_id']; ?>">

            <?php if (!empty($row['media_URL'])): ?>
                <p>Current Media:</p>
                <?php if ($row['media_type'] == 'image'): ?>
                    <img src="uploads/<?php echo $row['media_URL']; ?>" alt="FAQ Media" class="media-preview">
                <?php else: ?>
                    <p><?php echo $row['media_URL']; ?></p>
                <?php endif; ?>
            <?php endif; ?>

            <div>
                <input type="submit" name="edit_faq" value="Update FAQ">
                <button type="button" class="delete-btn" onclick="deleteFAQ(<?php echo $row['faq_id']; ?>)">Delete FAQ</button>
            </div>
        </form>
        <hr>
        <?php endwhile; ?>
    </div>  

    <script>
    function deleteFAQ(faqId) {
        if (confirm('Are you sure you want to delete this FAQ?')) {
            window.location.href = 'deletefaq.php?faq_id=' + faqId;
        }
    }
    </script>
</body>
</html>

<?php
$con->close();
?>