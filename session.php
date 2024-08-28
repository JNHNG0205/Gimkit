<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    echo '<script>alert("Please Login!"); window.location.href="login.html";</script>';
    exit;
}
?>
