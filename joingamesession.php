<?php
session_start();

// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gimkit";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['session_id'])) {
    $session_id = $_GET['session_id'];

    // Fetch session details and player name
    $sql = "SELECT gs.session_id, gs.player1_id, gs.game_state, gs.roompassword, u.user_name as player1_name 
            FROM game_sessions gs 
            JOIN user u ON gs.player1_id = u.user_id 
            WHERE gs.session_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $session_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $session = $result->fetch_assoc();
    } else {
        echo '<script>alert("Session not found."); window.location.href = "pvpRooms.php";</script>';
        exit;
    }
    $stmt->close();
} else {
    echo '<script>alert("Invalid session ID."); window.location.href = "pvpRooms.php";</script>';
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $roompassword = $_POST['roompassword'];

    if ($session['roompassword'] == $roompassword) {
        // Room password is correct
        // Redirect to game page (or handle joining logic)
        header("Location: pvpgame.php?session_id=$session_id");
        exit;
    } else {
        $error_message = "Incorrect room password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Join Game Session</title>
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

        p {
            font-size: 18px;
            line-height: 1.5;
            margin-bottom: 20px;
        }

        form {
            margin-bottom: 20px;
        }

        label {
            font-size: 18px;
            margin-right: 10px;
        }

        input[type="password"] {
            padding: 8px;
            font-size: 16px;
            border-radius: 5px;
            border: none;
            margin-right: 10px;
        }

        button, .btn {
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
        }

        button:hover, .btn:hover {
            opacity: 0.8;
        }

        .error {
            color: #ff6b6b;
            font-size: 18px;
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
        <h2>Join Game Session</h2>
        <p>Session ID: <?php echo $session['session_id']; ?></p>
        <p>Player 1: <?php echo $session['player1_name']; ?></p>
        <p>Game State: <?php echo $session['game_state']; ?></p>

        <?php if (isset($error_message)): ?>
            <p class="error"><?php echo $error_message; ?></p>
        <?php endif; ?>

        <?php if ($session['roompassword'] > 0): ?>
            <form method="post">
                <label for="roompassword">Room Password:</label>
                <input type="password" name="roompassword" required>
                <button type="submit">Join</button>
            </form>
        <?php else: ?>
            <p>No room password required.</p>
            <button onclick="window.location.href='beforepvp.php?session_id=<?php echo $session['session_id']; ?>'">Join</button>
        <?php endif; ?>

        <button class="btn" onclick="window.location.href='home.php'">Go back to Home</button>
    </div>
</body>
</html>
