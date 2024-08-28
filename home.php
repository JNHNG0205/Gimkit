This is a backstage function. No style required.

<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

// Redirect based on user role
$user_role = $_SESSION['user_role'];

switch ($user_role) {
    case 'player':
        header("Location: player_dashboard.php");
        break;
    case 'helpdesk_support':
        header("Location: helpdesk_dashboard.php");
        break;
    case 'content_manager':
        header("Location: content_manager_dashboard.php");
        break;
    default:
        echo "Invalid user role.";
        exit();
}
?>
