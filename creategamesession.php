<?php
// Include database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gimkit";

// Create connection
$con = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

// Function to generate a random 6-digit room password
function generateRoomPassword() {
    return rand(100000, 999999);
}

// Check if user is logged in and retrieve player1_id
session_start();
if (!isset($_SESSION['user_id'])) {
    die('User not logged in.');
}
$player1_id = $_SESSION['user_id'];

// Check if user wants to encrypt the session
$encryptSession = isset($_POST['encrypt']) ? true : false;

if (isset($_POST['submit'])) {
    // User has submitted the form, process the data
    // Generate room password or set to null
    $roompassword = $encryptSession ? generateRoomPassword() : 0; // Set default room password if not encrypted

    // Prepare SQL statement
    $sql = "INSERT INTO game_sessions (player1_id, game_state, player2_id, roompassword, player1_timetaken, player2_timetaken) 
            VALUES (?, 'in_progress', NULL, ?, NULL, NULL)";

    if ($stmt = $con->prepare($sql)) {
        // Bind parameters
        $stmt->bind_param("ii", $player1_id, $roompassword); // Change to 'ii' for both integer

        // Execute the statement
        if ($stmt->execute()) {
            // Get the last inserted ID (session_id)
            $session_id = $con->insert_id;

            // Success message
            $message = "Game session created successfully. Your session ID is: " . $session_id;

            // Display additional message based on room password
            if ($roompassword) {
                $message .= " and room password is: " . $roompassword . ". Room password required!";
            } else {
                $message .= ". Random player is welcome!";
            }
        } else {
            // Error message
            $message = "Error: " . $stmt->error;
        }

        // Close statement
        $stmt->close();
    } else {
        // Error preparing statement
        $message = "Error preparing statement: " . $con->error;
    }

    // Close connection
    $con->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Game Session</title>
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
            color: #fff;
        }

        .container {
            position: relative;
            z-index: 10;
            width: 80%;
            max-width: 600px;
            padding: 40px;
            background-color: rgba(2, 2, 2, 0.5);
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
            margin-bottom: 20px;
        }

        label {
            font-size: 18px;
            margin-right: 10px;
        }

        input[type="checkbox"] {
            transform: scale(1.5);
            margin-right: 10px;
        }

        input[type="submit"], .btn {
            background-color: #F2A765;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 18px;
            transition: opacity 0.3s ease;
            text-decoration: none;
            display: inline-block;
            margin-top: 20px;
        }

        input[type="submit"]:hover, .btn:hover {
            opacity: 0.8;
        }

        p {
            font-size: 18px;
            line-height: 1.5;
            margin-bottom: 20px;
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
        <?php if (isset($message)): ?>
            <h2>Game Session Created</h2>
            <p><?php echo htmlspecialchars($message); ?></p>
            <a href="pvpRoom.php" class="btn">Go to PvP Room</a>
            <a href="home.php" class="btn">Return to Home</a>
        <?php else: ?>
            <h2>Create Game Session</h2>
            <form method="post">
                <label for="encrypt">Apply custom Room Password?</label>
                <input type="checkbox" name="encrypt" id="encrypt"> 
                <br>
                <input type="submit" name="submit" value="Confirm">
                <br>
                <a href="home.php" class="btn">Return to Home</a>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>
