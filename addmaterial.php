<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gimkit";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = '';

// File upload handling
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    $title = $_POST["title"] ?? '';
    $description = $_POST["description"] ?? '';
    $media_type = $_POST["media_type"] ?? '';
    $target_dir = 'uploads/';
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0755, true);
    }
    
    if (isset($_FILES["fileToUpload"]) && $_FILES["fileToUpload"]["error"] == 0) {
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

        $uploadOk = 1;

        // Check file size (limit to 100MB)
        if ($_FILES["fileToUpload"]["size"] > 100000000) {
            $message = "Sorry, your file is too large. Maximum file size is 100MB.";
            $uploadOk = 0;
        }

        // Allow certain file formats
        $allowed_extensions = array("jpg", "jpeg", "png", "gif", "pdf", "mp4", "avi", "mov");
        if (!in_array($fileType, $allowed_extensions)) {
            $message = "Sorry, only JPG, JPEG, PNG, GIF, PDF, MP4, AVI, and MOV files are allowed.";
            $uploadOk = 0;
        }

        // Upload file and save to database
        if ($uploadOk == 1) {
            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                // Insert into material table first
                $sql_material = "INSERT INTO materials (title, description, created_at) VALUES (?, ?, NOW())";
                $stmt_material = $conn->prepare($sql_material);
                $stmt_material->bind_param("ss", $title, $description);
                
                if ($stmt_material->execute()) {
                    $material_id = $stmt_material->insert_id;
                    $media_URL = basename($target_file);
                    
                    // Insert media information with NULL discussion_id
                    $sql_media = "INSERT INTO media (media_type, media_URL, material_id, discussion_id, comment_id, question_id, response_id, badge_id, faq_id) VALUES (?, ?, ?, NULL, NULL, NULL, NULL, NULL, NULL)";
                    $stmt_media = $conn->prepare($sql_media);
                    $stmt_media->bind_param("ssi", $media_type, $media_URL, $material_id);
                    
                    if ($stmt_media->execute()) {
                        $message = "The material and associated media have been uploaded successfully.";
                    } else {
                        $message = "Sorry, there was an error saving the media information. Error: " . $conn->error;
                        error_log("Error saving media information: " . $conn->error);
                    }
                    $stmt_media->close();
                } else {
                    $message = "Sorry, there was an error saving the material information. Error: " . $conn->error;
                    error_log("Error saving material information: " . $conn->error);
                }
                $stmt_material->close();
            } else {
                $message = "Sorry, there was an error uploading your file.";
                error_log("File upload error: " . error_get_last()['message']);
            }
        }
    } else {
        $message = "No file was uploaded or there was an error with the upload.";
    }
}

$conn->close();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Material Upload</title>
    <link rel="icon" type="image/x-icon" href="logo1.png">
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
            overflow-x: hidden;
            margin: 0;
        }

        section {
            position: fixed;
            width: 100vw;
            height: 200vh;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 2px;
            flex-wrap: wrap;
            overflow: hidden;
        }

        section::before {
            content: '';
            position: absolute;
            width: 100%;
            height: 100%;
            background: linear-gradient(#010326, #965edb, #9249e1, rgb(127, 45, 165), rgb(138, 33, 173), #010326);
            animation: animate 10s linear infinite;
        }

        @keyframes animate {
            0% { transform: translateY(-100%); }
            100% { transform: translateY(100%); }
        } */

        section span {
            position: relative;
            display: block;
            width: calc(6.25vw - 2px);
            height: calc(6.25vw - 2px);
            background: #222567;
            z-index: 2;
            transition: 1.3s;
            border-radius: 30%;
        }

        section span:hover {
            background: #00ddff;
            transition: 0s;
        }

        .container {
            background-color: rgba(0, 0, 0, 0.65);
            border-radius: 10px;
            box-shadow: 0 15px 50px rgba(0, 0, 0, 0.1);
            width: 800px;
            max-width: 90%;
            padding: 40px;
            z-index: 1001;
        }

        h2 {
            text-align: center;
            color: #F2DC91;
            margin-bottom: 40px;
            font-size:40px;
            font-family: 'Bagel Fat One', sans-serif;
            z-index: 1001;
        }

        form {
            display: flex;
            flex-direction: column;
            z-index: 1001;
        }

        label {
            color: #F2DC91;
            font-weight: bold;
            margin-top: 10px;
            margin-bottom: 5px;
            font-family:'Bagel Fat One', sans-serif;
            z-index: 1001;
        }

        input, select, textarea {
            width: 100%;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #F2DC91;
            background-color: rgba(2, 2, 2, 0.9);
            color: #fff;
            z-index: 1001;
        }

        textarea {
            resize: vertical;
            min-height: 100px;
            z-index: 1001;
        }

        input[type="submit"] {
            background-color: #F2DC91;
            color: #010326;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s ease;
            margin-top: 15px;
            z-index: 1001;
        }

        input[type="submit"]:hover {
            background-color: #e0ca7f;
        }

        .message {
            margin-bottom: 20px;
            padding: 10px;
            background-color: rgba(255, 255, 255, 0.1);
            border: 1px solid #F2DC91;
            border-radius: 5px;
            color: #F2DC91;
            z-index: 1001;
        }

        .back-button {
            display: flex;
            justify-content: center;
            margin-top: 20px;
            z-index: 1001;
        }

        .back-button a {
            background-color: #F2DC91;
            color: #010326;
            text-decoration: none;
            padding: 10px 340px;
            border-radius: 5px;
            font-weight: bold;
            transition: background-color 0.3s ease;
            font-family: 'Bagel Fat One', sans-serif;
            z-index: 1001;
        }

        .back-button a:hover {
            background-color: #e0ca7f;
        }

        .cursor {
            width: 20px;
            height: 20px;
            border: 1px solid #fff;
            border-radius: 50%;
            position: fixed;
            pointer-events: none;
            z-index: 9999;
        }


    </style>
</head>
<body>

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
        } ?>
    </section>




    <div class="container">
        <h2>Upload Material</h2>
        <?php
        if (!empty($message)) {
            echo "<div class='message'>$message</div>";
        }
        ?>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
            <label for="title">Title:</label>
            <input type="text" name="title" id="title" required>

            <label for="description">Description:</label>
            <textarea name="description" id="description" required></textarea>

            <label for="media_type">Media Type:</label>
            <select name="media_type" id="media_type" required>
                <option value="image">Image</option>
                <option value="video">Video</option>
                <option value="slides">Slides</option>
            </select>

            <label for="fileToUpload">Choose File:</label>
            <input type="file" name="fileToUpload" id="fileToUpload" required>

            <input type="submit" value="Upload Material" name="submit">
        </form>
        
        <div class="back-button">
            <a href="home.php">Home</a>
        </div>
    </div>



    <script>
        const cursor = document.querySelector('.cursor');

        document.addEventListener('mousemove', e => {
            cursor.setAttribute("style", "top: "+(e.pageY - 10)+"px; left: "+(e.pageX - 10)+"px");
        });
    </script>


</body>
</html>