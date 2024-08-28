<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gimkit";

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    echo "<script>alert('Connection failed: " . mysqli_connect_error() . "');</script>";
    exit();
}

$levelsSql = "SELECT * FROM level";
$levelsResult = mysqli_query($conn, $levelsSql);

if (!$levelsResult) {
    echo "<script>alert('Error fetching levels: " . mysqli_error($conn) . "');</script>";
    exit();
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <title>Select a Level</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Bagel+Fat+One&family=Quicksand:wght@300..700&display=swap');
        h1 {
            color: #F2DC91;
            z-index: 1001;
            font-size: 50px;
        }
        .level_select {
            max-width: 800px;
            background-color: #333;
            padding: 30px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            z-index: 1001;
        }
        .select_container {
            background-color: rgba(0, 0, 0, 0.65);
            border-radius: 10px;
            box-shadow: 0 15px 50px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            width: 600px;
            max-width: 90%;
            z-index: 10;
            padding: 40px;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
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
    <a href="home.php" class="home-button">Home</a>
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
    <div class="select_container">
        <h1>Select a Level</h1>
        <div class="level_select">
            <form id="levelForm" method="GET" action="attempt_level.php" onsubmit="return validateForm()">
                <select style="width: 170px; height: 50px;" name="level_id" id="level_id">
                    <option value="">Choose a level</option>
                    <?php
                    while ($row = mysqli_fetch_assoc($levelsResult)) {
                        echo "<option value='" . $row['level_id'] . "'>" . $row['level_name'] . "</option>";
                    }
                    ?>
                </select>
                <input style="width: 170px; height: 50px; margin-left: 50px; padding: 10px; background-color: #fedc91;" type="submit" value="Attempt Level">
            </form>
        </div>
    </div>

    <script>
    function validateForm() {
        var levelId = document.getElementById("level_id").value;
        if (levelId == "") {
            alert("Please select a level before attempting.");
            return false;
        }
        return true;
    }
    </script>
</body>
</html>