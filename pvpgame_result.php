<?php
session_start();

// Function to check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['user_id']) && $_SESSION['user_id'] > 0;
}

// Function to check if there's an active game session
function hasActiveGameSession() {
    return isset($_SESSION['session_id']) && $_SESSION['session_id'] > 0;
}

function checkAndAwardGameCompletionBadge($session, $conn) {
    // Determine the winner
    $winner_id = null;
    if ($session['player1_correct_answers'] > $session['player2_correct_answers'] || 
        ($session['player1_correct_answers'] == $session['player2_correct_answers'] && 
         $session['player1_timetaken'] < $session['player2_timetaken'])) {
        $winner_id = $session['player1_id'];
    } else {
        $winner_id = $session['player2_id'];
    }

    // Define the badge ID for winning a game
    $badge_id =12; // Assuming badge_id 4 is for winning a game

    // Check if the winner already has this badge
    $sql = "SELECT * FROM user_badge WHERE user_id = ? AND badge_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $winner_id, $badge_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 0) {
        // Winner doesn't have this badge, so award it
        $sql = "INSERT INTO user_badge (user_id, badge_id) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $winner_id, $badge_id);
        if ($stmt->execute()) {
            return true;
        }
    }
    
    return false; // Badge not awarded (winner already has it or error occurred)
}

$error_message = "";
$info_message = "";
$session = null;

if (!isLoggedIn()) {
    $error_message = "You are not logged in. Please log in to view game results.";
} else {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "gimkit";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        $error_message = "Connection failed: " . $conn->connect_error;
    } else {
        $user_id = $_SESSION['user_id'];

        // Check for session_id in SESSION, POST, and GET
        $session_id = null;
        if (isset($_SESSION['session_id'])) {
            $session_id = $_SESSION['session_id'];
        } elseif (isset($_POST['session_id'])) {
            $session_id = $_POST['session_id'];
        } elseif (isset($_GET['session_id'])) {
            $session_id = $_GET['session_id'];
        }

        if ($session_id) {
            // Fetch the latest game session data
            $sessionSql = "SELECT * FROM game_sessions WHERE session_id = ?";
            $sessionStmt = $conn->prepare($sessionSql);
            $sessionStmt->bind_param("i", $session_id);
            $sessionStmt->execute();
            $sessionResult = $sessionStmt->get_result();
            $session = $sessionResult->fetch_assoc();
            $sessionStmt->close();

            if (!$session) {
                $error_message = "No game session found. The session may have expired or been deleted.";
            } else {
                // Determine if both players have finished
                $player1Finished = $session['player1_timetaken'] > 0; 
                $player2Finished = $session['player2_timetaken'] > 0; 

                if ($player1Finished && $player2Finished) {
                    // Update the time taken and correct answers in the database
                    $updateSql = "UPDATE game_sessions 
                                  SET player1_timetaken = ?, player2_timetaken = ?, 
                                      player1_correct_answers = ?, player2_correct_answers = ?,
                                      game_state = 'completed'
                                  WHERE session_id = ?";
                    $updateStmt = $conn->prepare($updateSql);
                    $updateStmt->bind_param("ssiii", 
                        $session['player1_timetaken'], 
                        $session['player2_timetaken'], 
                        $session['player1_correct_answers'], 
                        $session['player2_correct_answers'], 
                        $session_id);
                    $updateStmt->execute();

                    if ($updateStmt->affected_rows > 0) {
                        $success_message = "Game results saved successfully.";
            
                        // Award badge to the winner
                        $badge_awarded = checkAndAwardGameCompletionBadge($session, $conn);

                        if ($badge_awarded) {
                            $success_message .= " A new badge has been awarded to the winner!";
                        }

                        $redirect_delay = 7; // Redirect delay in seconds
                    } else {
                        $error_message = "Failed to save game results or no changes made.";
                    }
                    $updateStmt->close();
                } else {
                    $info_message = "Both players must finish the game session before the results can be finalized. Please check your result in Check My PvP Result Page. Redirected to Home Page in 10 Seconds.";
                    $redirect_delay = 7; // Redirect delay in seconds
                }
            }
        } else {
            $error_message = "No active game session found. Please start or join a game.";
            $redirect_delay = 10; // Redirect delay in seconds
        }

        $conn->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Game Result</title>
    <meta http-equiv="refresh" content="<?php echo isset($redirect_delay) ? $redirect_delay : 0; ?>;url=home.php">
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
            max-width: 800px;
            padding: 20px;
            background-color: rgba(2, 2, 2, 0.8);
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
        }

        h2 {
            font-size: 36px;
            margin-bottom: 20px;
            text-align: center;
            color: #222;
        }

        .error, .result, .info {
            margin: 20px 0;
            padding: 20px;
            border-radius: 5px;
        }

        .error {
            background-color: rgba(248, 215, 218, 0.7);
            color: #721c24;
        }

        .result {
            background-color: rgba(212, 237, 218, 0.7);
            color: #155724;
        }

        .info {
            background-color: rgba(255, 243, 205, 0.7);
            color: #856404;
        }

        p {
            font-size: 18px;
            line-height: 1.5;
            margin-bottom: 10px;
        }

        .button {
            display: inline-block;
            padding: 10px 20px;
            margin-top: 20px;
            font-size: 18px;
            color: #fff;
            background-color: #F2A765;
            text-decoration: none;
            border-radius: 5px;
            transition: opacity 0.3s ease;
        }

        .button:hover {
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
        <?php
        if ($error_message) {
            echo "<div class='error'>";
            echo "<h2>Error</h2>";
            echo "<p>$error_message</p>";
            echo "</div>";
        } elseif ($info_message) {
            echo "<div class='info'>";
            echo "<h2>Information</h2>";
            echo "<p>$info_message</p>";
            echo "</div>";
        } elseif (isset($success_message)) {
            echo "<div class='result'>";
            echo "<h2>Game Results</h2>";
            echo "<p>Player 1: " . $session['player1_correct_answers'] . " correct answers in " . $session['player1_timetaken'] . " seconds</p>";
            echo "<p>Player 2: " . $session['player2_correct_answers'] . " correct answers in " . $session['player2_timetaken'] . " seconds</p>";
            echo "<p>$success_message</p>";
            echo "</div>";
        }
        ?>
        <a href="home.php" class="button">Return to Home</a>
    </div>
</body>
</html>