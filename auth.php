<?php
session_start();

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

// The ID for the welcome badge
function giveWelcomeBadge($conn, $user_id) {
    $badge_id = 11; 
    $sql = "INSERT INTO user_badge (user_id, badge_id) VALUES ($user_id, $badge_id)";
    if ($conn->query($sql) === TRUE) {
        return true;
    } else {
        return false;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['action'])) {
        if ($_POST['action'] == 'login') {
            // Login logic
            $user_name = $conn->real_escape_string($_POST['user_name']);
            $user_password = $conn->real_escape_string($_POST['user_password']);

            $sql = "SELECT * FROM user WHERE user_name = '$user_name' AND user_password = '$user_password'";
            $result = $conn->query($sql);

            if ($result->num_rows == 1) {
                $row = $result->fetch_assoc();
                $_SESSION['user_id'] = $row['user_id'];
                $_SESSION['user_name'] = $row['user_name'];
                $_SESSION['user_role'] = $row['user_role'];

                // Check if it's the user's first login and they have a 'player' role
                if ($_SESSION['user_role'] == 'player') {
                    $check_badge_sql = "SELECT * FROM user_badge WHERE user_id = {$_SESSION['user_id']}";
                    $check_badge_result = $conn->query($check_badge_sql);
                    if ($check_badge_result->num_rows == 0) {
                        // Give welcome badge
                        giveWelcomeBadge($conn, $_SESSION['user_id']);
                    }
                }

                // Redirect based on user role
                switch ($_SESSION['user_role']) {
                    case 'player':
                        header("Location: player_dashboard.php");
                        break;
                    case 'content_manager':
                        header("Location: content_manager_dashboard.php");
                        break;
                    case 'helpdesk_support':
                        header("Location: helpdesk_dashboard.php");
                        break;
                }
                exit();
            } else {
                echo "<script>alert('Invalid username or password'); window.location.href = 'login.html';</script>";
            }
        } elseif ($_POST['action'] == 'register') {
            // Registration logic
            $user_name = $conn->real_escape_string($_POST['user_name']);
            $user_password = $conn->real_escape_string($_POST['user_password']);
            $user_email = $conn->real_escape_string($_POST['user_email']);
            $user_gender = $conn->real_escape_string($_POST['user_gender']);
            $user_role = 'player';

            // Check if username already exists
            $check_sql = "SELECT * FROM user WHERE user_name = '$user_name'";
            $check_result = $conn->query($check_sql);

            if ($check_result->num_rows > 0) {
                echo "<script>alert('Username already exists. Please choose a different username.'); window.location.href = 'login.html';</script>";
            } else {
                $sql = "INSERT INTO user (user_name, user_password, user_email, user_role, user_gender) 
                        VALUES ('$user_name', '$user_password', '$user_email', '$user_role', '$user_gender')";

                if ($conn->query($sql) === TRUE) {
                    $new_user_id = $conn->insert_id;
                    // Give welcome badge to the new user
                    giveWelcomeBadge($conn, $new_user_id);
                    echo "<script>alert('Registration successful. You can now login.'); window.location.href = 'login.html';</script>";
                } else {
                    echo "<script>alert('Error: " . $sql . "<br>" . $conn->error . "'); window.location.href = 'login.html';</script>";
                }
            }
        }
    }
}

$conn->close();
?>
