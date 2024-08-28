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

if (!isset($_SESSION['user_id'])) {
    echo '<script>alert("Please log in first."); window.location.href = "login.html";</script>';
    exit;
}

$user_id = $_SESSION['user_id'];

if (!isset($_GET['session_id'])) {
    echo '<script>alert("Invalid session."); window.location.href = "pvpRooms.php";</script>';
    exit;
}

$session_id = $_GET['session_id'];

$sql = "SELECT * FROM game_sessions WHERE session_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $session_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo '<script>alert("Session not found."); window.location.href = "pvpRooms.php";</script>';
    exit;
}

$session = $result->fetch_assoc();

if ($session['player1_id'] == $user_id) {
    $_SESSION['session_id'] = $session_id;
    header("Location: pvpgame.php?session_id=$session_id");
    exit;
} else {
    if ($session['player2_id'] === null) {
        $update_sql = "UPDATE game_sessions SET player2_id = ? WHERE session_id = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("ii", $user_id, $session_id);
        
        if ($update_stmt->execute()) {
            $_SESSION['session_id'] = $session_id;
            header("Location: pvpgame.php?session_id=$session_id");
            exit;
        } else {
            echo '<script>alert("Failed to join the game. Please try again."); window.location.href = "pvpRooms.php";</script>';
            exit;
        }
    } else {
        echo '<script>alert("This game is already full."); window.location.href = "pvpRooms.php";</script>';
        exit;
    }
}

$conn->close();
?>