<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gimkit";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION['user_id'] ?? 0;
if ($user_id == 0) {
    die("User not logged in.");
}

$session_id = $_POST['session_id'] ?? $_SESSION['session_id'] ?? 0;
$room_password = $_POST['room_password'] ?? $_SESSION['room_password'] ?? '';

if ($session_id == 0 && empty($room_password)) {
    die("Invalid session or missing room password.");
}

// If we have a room password, find the corresponding session
if (!empty($room_password)) {
    $findSessionSql = "SELECT session_id, player1_id, player2_id FROM game_sessions WHERE room_password = ?";
    $findSessionStmt = $conn->prepare($findSessionSql);
    $findSessionStmt->bind_param("s", $room_password);
    $findSessionStmt->execute();
    $findSessionResult = $findSessionStmt->get_result();
    $sessionRow = $findSessionResult->fetch_assoc();

    if (!$sessionRow) {
        die("Game session not found for the given room password.");
    }

    $session_id = $sessionRow['session_id'];
    $_SESSION['session_id'] = $session_id;
} else {
    // Check if the user is part of this game session
    $checkUserSql = "SELECT player1_id, player2_id FROM game_sessions WHERE session_id = ?";
    $checkUserStmt = $conn->prepare($checkUserSql);
    $checkUserStmt->bind_param("i", $session_id);
    $checkUserStmt->execute();
    $checkUserResult = $checkUserStmt->get_result();
    $sessionRow = $checkUserResult->fetch_assoc();

    if (!$sessionRow) {
        die("Game session not found.");
    }
}

// If player2 is not set, update player2_id
if ($sessionRow['player2_id'] == 0 && $sessionRow['player1_id'] != $user_id) {
    $updatePlayer2Sql = "UPDATE game_sessions SET player2_id = ? WHERE session_id = ?";
    $updatePlayer2Stmt = $conn->prepare($updatePlayer2Sql);
    $updatePlayer2Stmt->bind_param("ii", $user_id, $session_id);
    $updatePlayer2Stmt->execute();
    $sessionRow['player2_id'] = $user_id;
}

// Check if the user is one of the players in the session
if ($sessionRow['player1_id'] != $user_id && $sessionRow['player2_id'] != $user_id) {
    die("User not found in this game session.");
}

// Calculate time taken
$start_time = $_SESSION['start_time'];
$end_time = time();
$time_taken = $end_time - $start_time;

// Calculate correct answers
$correct_answers = 0;
$total_questions = 0;

foreach ($_POST as $key => $value) {
    if (strpos($key, 'q') === 0) {
        $total_questions++;
        $question_id = substr($key, 1);
        $selected_option = $value;

        $checkSql = "SELECT iscorrect FROM options WHERE question_id = ? AND option_id = ?";
        $checkStmt = $conn->prepare($checkSql);
        $checkStmt->bind_param("ii", $question_id, $selected_option);
        $checkStmt->execute();
        $checkResult = $checkStmt->get_result();
        $row = $checkResult->fetch_assoc();

        if ($row && $row['iscorrect'] == 1) {
            $correct_answers++;
        }
    }
}

// Determine which player to update
if ($sessionRow['player1_id'] == $user_id) {
    $updateSql = "UPDATE game_sessions SET 
                  player1_timetaken = ?, player1_correct_answers = ?
                  WHERE session_id = ?";
} elseif ($sessionRow['player2_id'] == $user_id) {
    $updateSql = "UPDATE game_sessions SET 
                  player2_timetaken = ?, player2_correct_answers = ?
                  WHERE session_id = ?";
} else {
    die("User not found in this game session.");
}

$updateStmt = $conn->prepare($updateSql);
$updateStmt->bind_param("iii", $time_taken, $correct_answers, $session_id);

if ($updateStmt->execute()) {
    // Successfully updated game session
} else {
    die("Failed to update game session.");
}

$conn->close();

// Redirect to results page
header("Location: pvpgame_result.php?session_id=" . $session_id);
exit();
?>