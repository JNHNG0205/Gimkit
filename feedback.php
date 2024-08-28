<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gimkit";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $feedback_text = $_POST['comments'];
    $rating = $_POST['rating'];

    if ($feedback_text != "" && $rating != "") {
        $sql = "INSERT INTO feedback (feedback_text, user_id, rating, created_at) 
                VALUES ('$feedback_text', $user_id, $rating, NOW())";
        
        if ($conn->query($sql) === TRUE) {
            echo "<script>alert('Feedback submitted successfully. Thank you.');</script>";
        } else {
            echo "<script>alert('Error: " . $conn->error . "');</script>";
        }
    } else {
        echo "<script>alert('Please fill in all required fields before submitting.');</script>";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback</title>
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
            color: #222;
        }

        .container {
            position: relative;
            z-index: 10;
            width: 80%;
            max-width: 800px;
            padding: 40px;
            background-color: rgba(2, 2, 2, 0.6);
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
        }

        h1 {
            font-size: 36px;
            margin-bottom: 20px;
            color: gold;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
            text-align: center;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            font-size: 18px;
            margin-bottom: 10px;
            display: inline-block;
            color: #fff;
        }

        input[type="number"],
        textarea {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 16px;
            color: #222;
        }

        input[type="submit"] {
            background-color: #ff9d4d;
            color: white;
            padding: 10px 290px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 18px;
            transition: opacity 0.3s ease;
            display: block;
            margin: 20px auto 0;
        }

        input[type="submit"]:hover {
            opacity: 0.8;
        }

        section {
            position: fixed;
            width: 100vw;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 2px;
            flex-wrap: wrap;
            overflow: hidden;
            z-index: 1;
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

        .home-button {
            position: absolute;
            bottom: 40px;
            background-color: #ff9d4d;
            color: white;
            padding: 10px 340px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 18px;
            transition: opacity 0.3s ease;
            text-decoration: none;
            z-index: 1001;
        }

        .home-button:hover {
            opacity: 0.8;
        }
    </style>
    <script>
        function validateForm() {
            var rating = document.forms["feedbackForm"]["rating"].value;
            var comments = document.forms["feedbackForm"]["comments"].value;
            if (rating == "" || comments == "") {
                alert("Please fill in all required fields before submitting.");
                return false;
            }
            return true;
        }
    </script>
</head>
<body>

    <div class="container">
        <h1>Feedback Form</h1>
        <form name="feedbackForm" method="post" action="" onsubmit="return validateForm()">
            <div class="form-group">
                <label for="rating">Rating (1-5):</label>
                <input type="number" name="rating" min="1" max="5" required>
            </div>
            
            <div class="form-group">
                <label for="comments">Feedback:</label>
                <textarea name="comments" required></textarea>
            </div>
            
            <input type="submit" value="Submit Feedback">
        </form>
        <br>
        <br>
        <br>
        <a href="home.php" class="home-button">Home</a>
    </div>
    

    <section>
        <?php for ($i = 0; $i < 100; $i++) {
            echo "<span></span>";
            echo "<span></span>";
            echo "<span></span>";
        } ?>
    </section>
</body>
</html>
