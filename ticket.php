<?php
session_start();
include("conn.php");

if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('You must be logged in to submit a ticket.'); window.location.href = 'login.html';</script>";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $subject = $_POST["subject"];
    $description = $_POST["description"];
    
    if ($subject == "" || $description == "") {
        echo "<script>alert('Please fill in all required fields.');</script>";
    } else {
        $status = 'in_progress';
        $created_at = date('Y-m-d H:i:s');
        $updated_at = date('Y-m-d H:i:s');
        $user_id = $_SESSION['user_id'];
        
        $sql = "INSERT INTO tickets (subject, description, status, created_at, updated_at, user_id) 
                VALUES (?, ?, ?, ?, ?, ?)";
        
        $stmt = $con->prepare($sql);
        $stmt->bind_param("sssssi", $subject, $description, $status, $created_at, $updated_at, $user_id);
        
        if ($stmt->execute()) {
            echo "<script>alert('Ticket submitted successfully. Please wait for future updates.');</script>";
        } else {
            echo "<script>alert('Error: " . $stmt->error . "');</script>";
        }
        
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Ticket</title>
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
            max-width: 600px;
            padding: 40px;
            background-color: rgba(2, 2, 2, 0.6);
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
            text-align: center;
        }

        h2 {
            font-size: 36px;
            margin-bottom: 20px;
            color: gold;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
        }

        form {
            text-align: left;
            margin-bottom: 20px;
        }

        input[type="text"], textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 16px;
            font-family: 'Bagel Fat One', sans-serif;
        }

        input[type="submit"] {
            background-color: #F2A765;
            color: white;
            padding: 10px 230px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 18px;
            transition: opacity 0.3s ease;
            margin-top: 10px;
        }

        .home-button {
            background-color: #F2A765;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 18px;
            transition: opacity 0.3s ease;
            margin-top: 10px;
        }

        input[type="submit"]:hover, .home-button:hover {
            opacity: 0.8;
        }

        .home-button {
            display: block;
            margin: 20px auto 0;
            text-align: center;
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
    </style>
    <script>
        function validateForm() {
            var subject = document.forms["ticketForm"]["subject"].value;
            var description = document.forms["ticketForm"]["description"].value;
            if (subject == "" || description == "") {
                alert("Please fill in all required fields.");
                return false;
            }
            return true;
        }
    </script>
</head>
<body>
    <div class="container">
        <h2>Create a Ticket</h2>
        <form name="ticketForm" method="post" action="" onsubmit="return validateForm()">
            <input type="text" name="subject" placeholder="Subject" required>
            <textarea name="description" rows="4" cols="50" placeholder="Description" required></textarea>
            <input type="submit" value="Submit">
        </form>
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
