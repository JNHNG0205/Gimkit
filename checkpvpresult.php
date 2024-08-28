<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

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

$user_id = $_SESSION['user_id'] ?? 0;

if ($user_id == 0) {
    die("Invalid session data.");
}

// Fetch all game sessions where the user was a participant
$sessionsSql = "SELECT * FROM game_sessions WHERE player1_id = ? OR player2_id = ?";
$sessionsStmt = $conn->prepare($sessionsSql);
$sessionsStmt->bind_param("ii", $user_id, $user_id);
$sessionsStmt->execute();
$sessionsResult = $sessionsStmt->get_result();
$sessionsStmt->close();

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Previous PvP Sessions</title>
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
        background: #010326;
        min-height: 100vh;
        color: #fff;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5); 
        }

        .container {
            position: relative;
            z-index: 10;
            width: 80%;
            max-width: 1200px;
        }

        h2 {
            font-size: 100px;
            margin-bottom: 30px;
            text-align: center;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
            color: gold;
        }

        .sessions-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
        }

        .session {
      background-color: rgba(66, 165, 245, 0.7);
      padding: 20px;
      width: 400px;
      border-radius: 8px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
      transition: background 0.3s ease;
    }

        .session:hover {
            background-color: rgba(34, 37, 103, 1);
        }

        p {
            font-size: 16px;
            line-height: 1.5;
            color: #ddd;
            margin-bottom: 10px;
        }

        section {
            position: fixed;
            width: 100vw;
            height: vh;
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

        .btn {
            display: block;
            width: 400px;
            padding: 10px;
            margin: 30px auto 0;
            text-align: center;
            color: #fff;
            background-color: #ff9d4d;
            border: none;
            border-radius: 5px;
            font-size: 18px;
            cursor: pointer;
            text-decoration: none;
        }

        .btn:hover {
            background-color: #ffbe8d;
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

        } ?>
    </section>
    <div class="container">
        <h2>Previous PvP Sessions</h2>
        <div class="sessions-container">
            <?php
            if ($sessionsResult->num_rows > 0) {
                while ($session = $sessionsResult->fetch_assoc()) {
                    echo "<div class='session'>";
                    echo "<p>Session ID: {$session['session_id']}</p>";
                    if ($session['player2_id'] !== null) {
                        if ($session['game_state'] === 'in_progress') {
                            echo "<p>Game State: Ongoing - Waiting for an opponent to complete the game.</p>";
                            echo "<p>Room Password: " . ($session['roompassword'] == 0 ? '-' : $session['roompassword']) . "</p>";
                        } else {
                            // Display completed session details
                            $isPlayer1 = $user_id == $session['player1_id'];
                            $userCorrectAnswers = $isPlayer1 ? $session['player1_correct_answers'] : $session['player2_correct_answers'];
                            $userTimeTaken = $isPlayer1 ? $session['player1_timetaken'] : $session['player2_timetaken'];
                            $opponentCorrectAnswers = $isPlayer1 ? $session['player2_correct_answers'] : $session['player1_correct_answers'];
                            $opponentTimeTaken = $isPlayer1 ? $session['player2_timetaken'] : $session['player1_timetaken'];

                            // Determine the winner and assign EXP
                            if ($session['player1_correct_answers'] !== null && $session['player2_correct_answers'] !== null) {
                                if ($session['player1_correct_answers'] > $session['player2_correct_answers']) {
                                    $winner = 'player1';
                                } elseif ($session['player2_correct_answers'] > $session['player1_correct_answers']) {
                                    $winner = 'player2';
                                } else {
                                    // If both have the same correct answers, compare time taken
                                    $winner = ($session['player1_timetaken'] < $session['player2_timetaken']) ? 'player1' : 'player2';
                                }

                                // Assign EXP only if it hasn't been awarded yet
                                if ($session['player1_pvpEXP'] === null && $session['player2_pvpEXP'] === null) {
                                    $conn = new mysqli($servername, $username, $password, $dbname);
                                    if ($winner == 'player1') {
                                        $conn->query("UPDATE game_sessions SET player1_pvpEXP = 20, player2_pvpEXP = 10 WHERE session_id = {$session['session_id']}");
                                    } else {
                                        $conn->query("UPDATE game_sessions SET player1_pvpEXP = 10, player2_pvpEXP = 20 WHERE session_id = {$session['session_id']}");
                                    }
                                    $conn->close();
                                }

                                // Determine if the current user is the winner
                                $userIsWinner = ($isPlayer1 && $winner == 'player1') || (!$isPlayer1 && $winner == 'player2');
                                $resultMessage = $userIsWinner ? "You are the winner!" : "Nice effort, you came in second place.";

                                echo "<p>Game State: Completed</p>";
                                echo "<p>$resultMessage</p>";
                                echo "<p>Your Correct Answers: {$userCorrectAnswers}</p>";
                                echo "<p>Your Time Taken: {$userTimeTaken} seconds</p>";
                                echo "<p>Opponent's Correct Answers: {$opponentCorrectAnswers}</p>";
                                echo "<p>Opponent's Time Taken: {$opponentTimeTaken} seconds</p>";
                                echo "<p>Room Password: " . ($session['roompassword'] == 0 ? '-' : $session['roompassword']) . "</p>";
                            } else {
                                echo "<p>Game State: Completed (waiting for both players' data to be recorded).</p>";
                                echo "<p>Room Password: " . ($session['roompassword'] == 0 ? '-' : $session['roompassword']) . "</p>";
                            }
                        }
                    } else {
                        echo "<p>Game State: Waiting for another player to join the game session.</p>";
                        echo "<p>Room Password: " . ($session['roompassword'] == 0 ? '-' : $session['roompassword']) . "</p>";
                    }
                    echo "</div>";
                }
            } else {
                echo "<p>No previous PvP sessions found.</p>";
            }
            ?>
        </div>
        <a href="home.php" class="btn">Home</a>
    </div>
</body>
</html>