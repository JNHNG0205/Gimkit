This is a backstage function. No style required.

<?php
// Initialize session
session_start();

// Enable error reporting for debugging
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

// Get the question ID and level ID from the URL
if (isset($_GET['question_id']) && isset($_GET['level_id'])) {
    $questionId = $_GET['question_id'];
    $levelId = $_GET['level_id'];

    // Delete associated options
    $deleteOptionsSql = "DELETE FROM options WHERE question_id = ?";
    $stmt = $conn->prepare($deleteOptionsSql);
    $stmt->bind_param("i", $questionId);
    $stmt->execute();

    // Delete associated answer (if fill-in-the-blank)
    $deleteAnswerSql = "DELETE FROM answer WHERE question_id = ?";
    $stmt = $conn->prepare($deleteAnswerSql);
    $stmt->bind_param("i", $questionId);
    $stmt->execute();

    // Delete associated media
    $deleteMediaSql = "DELETE FROM media WHERE question_id = ?";
    $stmt = $conn->prepare($deleteMediaSql);
    $stmt->bind_param("i", $questionId);
    $stmt->execute();

    // Delete the question itself
    $deleteQuestionSql = "DELETE FROM question WHERE question_id = ?";
    $stmt = $conn->prepare($deleteQuestionSql);
    $stmt->bind_param("i", $questionId);
    $stmt->execute();

    // Redirect back to the edit questions page with the level_id
    header("Location: editquestion.php?level_id=" . $levelId);
    exit();
} else {
    // If question_id or level_id is missing, redirect back to manage levels page
    header("Location: managelevels.php");
    exit();
}

// Close the database connection
$conn->close();
?>
