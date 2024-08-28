<?php
session_start();
include("conn.php");

// Check if user is logged in and has admin privileges
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'helpdesk_support') {
    echo "<script>alert('You must be logged in as an Helpdesk Support to access this page.'); window.location.href = 'login.html';</script>";
    exit();
}

$message = ''; // Variable to store messages for the user


// Function to add FAQ and upload media
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_faq'])) {
    $faq_title = $_POST['faq_title'];
    $faq_contents = $_POST['faq_contents'];
    $media_type = $_POST['media_type'];
    
    if ($faq_title != "" && $faq_contents != "") {
        // Insert FAQ
        $sql = "INSERT INTO faq (faq_title, faq_contents) VALUES (?, ?)";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("ss", $faq_title, $faq_contents);
        
        if ($stmt->execute()) {
            $faq_id = $stmt->insert_id;
            
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
                    $message = "Sorry, your file is too large.";
                } else {
                    // Allow certain file formats
                    $allowed_extensions = array("jpg", "jpeg", "png", "gif", "pdf", "mp4", "avi", "mov");
                    if (in_array($fileType, $allowed_extensions)) {
                        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                            $media_URL = basename($target_file);
                            
                            // Insert media information
                            $sql_media = "INSERT INTO media (media_type, media_URL, faq_id) VALUES (?, ?, ?)";
                            $stmt_media = $con->prepare($sql_media);
                            $stmt_media->bind_param("ssi", $media_type, $media_URL, $faq_id);
                            
                            if ($stmt_media->execute()) {
                                $message = "FAQ added successfully with media.";
                            } else {
                                $message = "FAQ added, but there was an error uploading the media.";
                            }
                            $stmt_media->close();
                        } else {
                            $message = "FAQ added, but there was an error uploading the file.";
                        }
                    } else {
                        $message = "FAQ added, but only JPG, JPEG, PNG, GIF, PDF, MP4, AVI, and MOV files are allowed for media.";
                    }
                }
            } else {
                $message = "FAQ added successfully without media.";
            }
        } else {
            $message = "Error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $message = "Please fill in all fields.";
    }
}

// Fetch all tickets, ordered by newest first
$sql = "SELECT * FROM tickets ORDER BY created_at DESC";
$result = $con->query($sql);

// Search bar function
$search = isset($_GET['search']) ? $_GET['search'] : '';
$sql = "SELECT * FROM tickets WHERE subject LIKE ? OR description LIKE ?";
$stmt = $con->prepare($sql);
$search_param = "%$search%";
$stmt->bind_param("ss", $search_param, $search_param);
$stmt->execute();
$result = $stmt->get_result();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add FAQ and View Tickets</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Bagel+Fat+One&family=Quicksand:wght@300..700&display=swap');
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Bagel Fat One', sans-serif;
            cursor: url('../images/icons8-cursor-100.png'), auto;
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
        .cursor {
            width: 20px;
            height: 20px;
            border: 1px solid #fff;
            border-radius: 50%;
            position: fixed;
            pointer-events: none;
            z-index: 9999;
        }

        :root {
            --primary-bg: #010326;
            --button-color: #F2DC91;
            --form-bg: rgba(0, 0, 0, 0.65);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Bagel Fat One', sans-serif;
        }

        body {
            min-height: 100vh;
            background: var(--primary-bg);
            color: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
            z-index: 10;
        }

        .container {
            width: 100%;
            max-width: 800px;
            height: 400px;
            background: var(--form-bg);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(242, 220, 145, 0.3);
            z-index: 10;
        }

        h1 {
            font-family: 'Bagel Fat One', sans-serif;
            text-align: center;
            color: var(--button-color);
            margin-bottom: 20px;
            z-index: 10;
        }

        form {
            display: flex;
            flex-direction: column;
            z-index: 10;
        }

        label {
            margin-top: 10px;
            margin-bottom: 5px;
            font-weight: bold;
            z-index: 10;
        }

        input, select, textarea {
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid var(--button-color);
            border-radius: 4px;
            background: rgba(2, 2, 2, 0.9);
            color: #fff;
            z-index: 10;
        }

        textarea {
            resize: vertical;
            min-height: 100px;
            z-index: 10;
        }

        input[type="submit"], input[type="file"] {
            background-color: var(--button-color);
            color: var(--primary-bg);
            border: none;
            cursor: pointer;
            padding: 10px;
            font-weight: bold;
            transition: all 0.3s ease;
            z-index: 10;
        }

        input[type="submit"]:hover, input[type="file"]:hover {
            background-color: #fff;
            z-index: 10;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            z-index: 10;
        }

        th, td {
            padding: 10px;
            border: 1px solid var(--button-color);
            text-align: left;
            z-index: 10;
        }

        th {
            background-color: rgba(242, 220, 145, 0.2);
            z-index: 10;
        }

        .search-form {
            display: flex;
            margin-bottom: 20px;
            z-index: 10;
        }

        .search-form input[type="text"] {
            flex-grow: 1;
            margin-right: 10px;
            z-index: 10;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
            z-index: 10;
        }

        .container {
            width: 100%;
            max-width: 800px;
            background: var(--form-bg);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(242, 220, 145, 0.3);
            z-index: 10;
            height: 180vh; /* Added to ensure full viewport height */
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
        }
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
        .ticket-table-container {
            overflow-y: scroll;
            max-height: 400px; /* Adjust the max height as needed */
        } 
        .go-back-button {
            display: block;
            margin: 20px auto;
            background-color: var(--button-color);
            color: var(--primary-bg);
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            transition: all 0.3s ease;
            text-decoration: none;
            font-family: 'Bagel Fat One', sans-serif;
            z-index: 10;
        }

        .go-back-button:hover {
            background-color: #fff;
            color: var(--button-color);
            z-index: 10;
        }    
    </style>
</head>
<body>
    <section>
        <?php for ($i = 0; $i < 100; $i++) {
            echo "<span></span>";
            echo "<span></span>";
            echo "<span></span>";
        } ?>
    </section>

    <div class="container">
        <h1>Add FAQ</h1>
        <a href="home.php" class="go-back-button">Home</a>
        <?php if (!empty($message)) echo "<p>$message</p>"; ?>
        <form method="post" action="" enctype="multipart/form-data">
            <label for="faq_title">FAQ Title:</label>
            <input type="text" id="faq_title" name="faq_title" required>
            
            <label for="faq_contents">FAQ Contents:</label>
            <textarea id="faq_contents" name="faq_contents" rows="4" cols="50" required></textarea>
            
            <label for="media_type">Media Type:</label>
            <select name="media_type" id="media_type">
                <option value="image">Image</option>
                <option value="video">Video</option>
                <option value="slides">Slides</option>
            </select>
            
            <label for="fileToUpload">Choose File:</label>
            <input type="file" name="fileToUpload" id="fileToUpload">
            
            <input type="submit" name="add_faq" value="Add FAQ">
        </form>

        <h1>View Tickets</h1>
        <form method="get" action="" class="search-form">
            <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="Search tickets...">
            <input type="submit" value="Search">
        </form>
        
       
        
        <div class="ticket-table-container">
            <table>
                <thead>
                    <tr>
                        <th>Ticket ID</th>
                        <th>Subject</th>
                        <th>Description</th>
                        <th>Status</th>
                        <th>Created At</th>
                        <th>Updated At</th>
                        <th>User ID</th>
                    </tr>
                </thead> 
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['ticket_id']); ?></td>
                            <td><?php echo htmlspecialchars($row['subject']); ?></td>
                            <td><?php echo htmlspecialchars($row['description']); ?></td>
                            <td><?php echo htmlspecialchars($row['status']); ?></td>
                            <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                            <td><?php echo htmlspecialchars($row['updated_at']); ?></td>
                            <td><?php echo htmlspecialchars($row['user_id']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>    
            </table>
        </div>
    </div>
    
    
    <script>
        function goBack() {
          window.history.go(-1);
        }
    </script>
</body>
</html>

<?php
$con->close();
?>