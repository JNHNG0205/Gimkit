<?php
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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if(isset($_POST["email"]) && isset($_POST["newpassword"]) && isset($_POST["otp"])) {
        $email = $_POST["email"];
        $otp = $_POST["otp"];
        $newpassword = $_POST["newpassword"]; // No hashing, direct password insertion

        // Retrieve the expected OTP for the provided email
        $sql = "SELECT OTP FROM user WHERE user_email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $expectedOTP = $row['OTP'];

            if ($otp === $expectedOTP) {
                $sql2 = "UPDATE user SET user_password = ?, OTP = NULL WHERE user_email = ?";
                $stmt2 = $conn->prepare($sql2);
                $stmt2->bind_param("ss", $newpassword, $email);

                if($stmt2->execute()) {
                    echo '<script>alert("Password changed successfully!");
                    window.location.href="login.html";
                    </script>';
                } else {
                    echo "Error updating password: " . $conn->error;
                }
            } else {
                echo '<script>alert("Invalid OTP");</script>';
            }
        } else {
            echo '<script>alert("Email not found in our records");</script>';
        }
    } else {
        echo '<script>alert("Email, new password, or OTP not provided");</script>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify OTP</title>
    <style>
    @import url("https://fonts.googleapis.com/css2?family=ADLaM+Display&display=swap");
    body {
        font-family: "ADLaM Display";
        background-color: #f0f0f0;
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    h1 {
        text-align: center;
        margin-top: 100px;
    }

    form {
        width: 300px;
        margin: 20px auto;
        background-color: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    label {
        display: block;
        margin-bottom: 8px;
    }

    input[type="email"],
    input[type="text"],
    input[type="password"] {
        width: calc(100% - 22px);   
        padding: 10px;
        margin-bottom: 15px;
        border: 1px solid #ccc;
        border-radius: 4px;
    }

    button {
        width: 100%;
        padding: 10px;
        background-color: #007bff;
        color: #fff;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    button:hover {
        background-color: #0056b3;
    }

    button:active {
        background-color: #004080;
    }
    </style>
    <link rel="icon" type="image/x-icon" href="icon1.png">
</head>
<body>
    <h1>Verify OTP</h1>
    <form method="post">
        <label for="email">Email:</label>
        <input type="email" name="email" required>
        <label for="newpassword">New Password:</label>
        <input type="password" name="newpassword" required>
        <label for="otp">OTP:</label>
        <input type="text" name="otp" required>
        <button type="submit">Verify OTP</button>
    </form>
</body>
</html>
