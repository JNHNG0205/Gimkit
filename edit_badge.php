<?php
session_start();
include("conn.php");

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'content_manager') {
    echo "<script>alert('You must be logged in as a content manager to access this page.'); window.location.href = 'login.html';</script>";
    exit();
}

function sanitize_input($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

$message = '';
$edit_id = isset($_GET['edit']) ? intval($_GET['edit']) : 0;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'add':
            case 'edit':
                $badge_name = sanitize_input($_POST["badge_name"]);
                $description = sanitize_input($_POST["description"]);

                if ($_POST['action'] == 'add') {
                    $stmt = $con->prepare("INSERT INTO badge (badge_name, description) VALUES (?, ?)");
                    $stmt->bind_param("ss", $badge_name, $description);
                } else {
                    $stmt = $con->prepare("UPDATE badge SET badge_name = ?, description = ? WHERE badge_id = ?");
                    $stmt->bind_param("ssi", $badge_name, $description, $edit_id);
                }

                if ($stmt->execute()) {
                    $badge_id = $_POST['action'] == 'add' ? $stmt->insert_id : $edit_id;

                    if (isset($_FILES['badge_image']) && $_FILES['badge_image']['error'] == 0) {
                        $target_dir = "uploads/";
                        $file_extension = pathinfo($_FILES["badge_image"]["name"], PATHINFO_EXTENSION);
                        $new_filename = "badge_" . $badge_id . "." . $file_extension;
                        $target_file = $target_dir . $new_filename;

                        if (move_uploaded_file($_FILES["badge_image"]["tmp_name"], $target_file)) {
                            $media_url = $target_file;
                            $media_type = "image";

                            if ($_POST['action'] == 'add') {
                                $stmt = $con->prepare("INSERT INTO media (media_type, media_url, badge_id) VALUES (?, ?, ?)");
                                $stmt->bind_param("ssi", $media_type, $media_url, $badge_id);
                            } else {
                                $stmt = $con->prepare("UPDATE media SET media_url = ? WHERE badge_id = ?");
                                $stmt->bind_param("si", $media_url, $badge_id);
                            }

                            $stmt->execute();
                        }
                    }
                    $message = "Badge " . ($_POST['action'] == 'add' ? "added" : "updated") . " successfully!";
                    $edit_id = 0; // Reset edit mode
                } else {
                    $message = "Error: " . $stmt->error;
                }
                break;

            case 'delete':
                $delete_id = intval($_POST['delete_id']);
                $con->begin_transaction();
                try {
                    $con->query("DELETE FROM media WHERE badge_id = $delete_id");
                    $con->query("DELETE FROM badge WHERE badge_id = $delete_id");
                    $con->commit();
                    $message = "Badge deleted successfully!";
                } catch (Exception $e) {
                    $con->rollback();
                    $message = "Error deleting badge: " . $e->getMessage();
                }
                break;
        }
    }
}

// Fetch all badges
$sql = "SELECT b.badge_id, b.badge_name, b.description, m.media_url FROM badge b LEFT JOIN media m ON b.badge_id = m.badge_id ORDER BY b.badge_id DESC";
$result = $con->query($sql);

// Fetch badge for editing if in edit mode
$edit_badge = null;
if ($edit_id) {
    $edit_stmt = $con->prepare("SELECT b.*, m.media_url FROM badge b LEFT JOIN media m ON b.badge_id = m.badge_id WHERE b.badge_id = ?");
    $edit_stmt->bind_param("i", $edit_id);
    $edit_stmt->execute();
    $edit_result = $edit_stmt->get_result();
    $edit_badge = $edit_result->fetch_assoc();
    $edit_stmt->close();
}

$con->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="logo1.png">
    <title>Badge Management</title>
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
            position: relative;
            z-index: 10;
            width: 820px;
            padding: 20px;
            background: rgba(2, 2, 2, 0.5);
            border-radius: 8px;
        }

        h1, h2 {
            font-size: 50px;
            margin-bottom: 30px;
            color: gold;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.8);
            text-align: left;
        }

        h3, p {
            color: #fff;
            z-index: 1001;
        }

        form {
            display: flex;
            flex-direction: column;
            align-items: center;
            color: #fff;
            z-index: 1001;
        }

        label {
            display: block;
            margin-bottom: 5px;
            z-index: 1001;
            color: #fff;
            font-size: 20px;
            font-weight: bold;
        }

        input[type="text"], textarea, input[type="file"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid #ff9d4d;
            color: #fff;
            border-radius: 4px;
            font-family: 'Bagel Fat One', sans-serif;
        }

        input[type="submit"], .action-button, .back-btn {
            background-color: #ff9d4d;
            color: white;
            padding: 10px 15px;
            border: none;
            cursor: pointer;
            transition: opacity 0.3s ease;
            margin-right: 10px;
            font-weight: bold;
            border-radius: 5px;
        }

            .back-btn {
            background-color: #ff9d4d;
            color: white;
            padding: 10px 350px;
            border: none;
            cursor: pointer;
            transition: opacity 0.3s ease;
            margin-right: 10px;
            font-weight: bold;
            border-radius: 5px;
        }

        input[type="submit"]:hover, .action-button:hover, .back-btn:hover {
            opacity: 0.8;
        }

        .badge-image {
            max-width: 100px;
            max-height: 100px;
            margin-top: 10px;
            border-radius: 5px;
            z-index: 1001;
        }

        .message {
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
            text-align: center;
            z-index: 1001;
        }

        .success {
            background-color: #d4edda;
            color: #155724;
        }

        .error {
            background-color: #f8d7da;
            color: #721c24;
        }

        section {
            position: fixed;
            width: 100vw;
            height: 500vh;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 2px;
            flex-wrap: wrap;
            overflow: hidden;
            z-index: -1;
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

        .action-button.delete {
            background-color: #f8d7da;
            color: #721c24;
        }

        .action-button.delete:hover {
            background-color: #e6bdc2;
        }
    </style>
</head>
<body>
    <section>
        <?php for ($i = 0; $i < 300; $i++): ?>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
        <?php endfor; ?>
    </section>

    <div class="container">
        <h1>Badge Management</h1>

        <?php if ($message): ?>
            <div class="message <?php echo strpos($message, 'Error') !== false ? 'error' : 'success'; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <h2><?php echo $edit_id ? 'Edit' : 'Add New'; ?> Badge</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
            <input type="hidden" name="action" value="<?php echo $edit_id ? 'edit' : 'add'; ?>">
            <?php if ($edit_id): ?>
                <input type="hidden" name="edit_id" value="<?php echo $edit_id; ?>">
            <?php endif; ?>

            <label for="badge_name">Badge Name:</label>
            <input type="text" id="badge_name" name="badge_name" required value="<?php echo $edit_badge ? htmlspecialchars($edit_badge['badge_name']) : ''; ?>">

            <label for="description">Description:</label>
            <textarea id="description" name="description" rows="4" required><?php echo $edit_badge ? htmlspecialchars($edit_badge['description']) : ''; ?></textarea>


            <label for="badge_image">Badge Image:</label>
            <input type="file" id="badge_image" name="badge_image" accept="image/*" <?php echo $edit_id ? '' : 'required'; ?>>

            <?php if ($edit_badge && $edit_badge['media_url']): ?>
                <p>Current Image: <img src="<?php echo htmlspecialchars($edit_badge['media_url']); ?>" alt="Current Badge" class="badge-image"></p>
            <?php endif; ?>

            <input type="submit" value="<?php echo $edit_id ? 'Update' : 'Add'; ?> Badge">
        </form>

        <h2>Available Badges</h2>
        <div class="badge-list">
        <?php
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<br>";
                echo "<div class='badge-item'>";
                if (!empty($row["media_url"])) {
                    echo "<img src='" . htmlspecialchars($row["media_url"]) . "' alt='" . htmlspecialchars($row["badge_name"]) . "' class='badge-image'>";
                }
                echo "<br>";
                echo "<h3>" . htmlspecialchars($row["badge_name"]) . "</h3>";
                echo "<br>";
                echo "<p>" . htmlspecialchars($row["description"]) . "</p>";
                echo "<br>";
                echo "<a href='?edit=" . $row["badge_id"] . "' class='action-button'>Edit</a>"; 
                echo "<form action='" . htmlspecialchars($_SERVER["PHP_SELF"]) . "' method='post' style='display:inline;'>";
                echo "<input type='hidden' name='action' value='delete'>";
                echo "<input type='hidden' name='delete_id' value='" . $row["badge_id"] . "'>";
                echo "<input type='submit' value='Delete' onclick='return confirm(\"Are you sure you want to delete this badge?\")' class='action-button delete'>";
                echo "</form>";
                echo "</div>";
                
            }
        } else {
            echo "<p>No badges available.</p>";
        }
        ?>
        </div>
        
        <br>
        <a href="home.php" class="back-btn">Home</a>
    </div>
</body>
</html>