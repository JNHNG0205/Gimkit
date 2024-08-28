<link rel="stylesheet" type="text/css" href="css\internal_style.css">

<?php
session_start();

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

function addLevel($conn, $levelName) {
    $sql = "INSERT INTO level (level_name) VALUES (?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $levelName);
    
    if ($stmt->execute()) {
        $levelId = $conn->insert_id;
        $stmt->close();
        $_SESSION['level_id'] = $levelId; // Store level_id in session
        header("Location: addquestion.php");
        exit();
    } else {
        $stmt->close();
        return "Error: " . $conn->error;
    }
}

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $levelName = $_POST['level_name'];
    $result = addLevel($conn, $levelName);
    if (is_string($result)) {
        $message = $result;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Level</title>
</head>

<style>

@import url('https://fonts.googleapis.com/css2?family=Bagel+Fat+One&family=Quicksand:wght@300..700&display=swap');
    h1 {
        color: #F2DC91;
        z-index: 1001;
        font-size: 50px;
        font-family:'Bagel Fat One', sans-serif;
    }
    .container {
        background-color: rgba(0, 0, 0, 0.65);
        border-radius: 10px;
        box-shadow: 0 15px 50px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        width: 600px;
        height: 300px; /* Increased height to accommodate the new button */
        max-width: 90%;
        z-index: 10;
        padding: 40px;
        position: fixed;
    }

    button {
        background-color: #ff9d4d;
        color: black;
        padding: 10px 15px;
        border: none;
        cursor: pointer;
        transition: opacity 0.3s ease;
        margin-right: 10px;
        font-family:'Bagel Fat One', sans-serif;
        font-weight: bold;
    }
 
    button:hover {
        opacity: 0.8;
    }
        
    .button-container {
        margin-top: 20px;
        text-align: center;
    }

    input[type="submit"], button {
        width: 150px;
        height: 50px;
        padding: 10px;
        background-color: #fedc91;
        font-family:'Bagel Fat One', sans-serif;
        font-weight: bold;
        border: none;
        cursor: pointer;
        transition: opacity 0.3s ease;
    }

    input[type="submit"]:hover, button:hover {
        opacity: 0.8;
    }
</style>

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
        } 
    ?>
    </section>
    
    <div class="container">
        <h1>Add New Level</h1>
        <?php
        if (!empty($message)) {
            echo "<p style='color: red;'>" . htmlspecialchars($message) . "</p>";
        }
        ?>
        <form action="addlevel.php" method="post">
        
            <label style="color: #fff; z-index: 1001; font-size: 22px; font-weight: bold;" for="level_name">Level Name:</label>

            <input style="width: 300px; height: 30px; font-size: 20px; margin-top: 10px;" type="text" id="level_name" name="level_name" required>

            <br><br>
            <div class="button-container">
                <input type="submit" value="Create Level">
                <button onclick="window.location.href='home.php'">Home</button>
            </div>
        </form>
    </div>
</body>
</html>