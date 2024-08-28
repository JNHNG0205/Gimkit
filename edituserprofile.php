<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    echo '<script>alert("Please Login!"); window.location.href="login.html";</script>';
    exit;
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gimkit";

// Create connection
$con = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

// Fetch the user's current information
$sql = "SELECT user_name, user_email, user_password FROM user WHERE user_id = " . $_SESSION['user_id'];
$result = mysqli_query($con, $sql);

if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
} else {
    echo "User not found.";
    exit;
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_username = $_POST["user_name"];
    $new_email = $_POST["user_email"];
    $new_password = $_POST["user_password"];

    // Update the user's information in the database
    $sql = "UPDATE user SET user_name = '$new_username', user_email = '$new_email', user_password = '$new_password' WHERE user_id = " . $_SESSION['user_id'];

    if (mysqli_query($con, $sql)) {
        // Redirect the user back to the profile page
        header("Location: userprofile.php");
        exit;
    } else {
        echo "Error updating profile: " . mysqli_error($con);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
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
            flex-direction: column;
        }

        h1 {
            font-size: 50px;
            margin-bottom: 30px;
            color: gold;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.8);
            text-align: center;
            z-index: 1001;
        }

        form {
            display: flex;
            flex-direction: column;
            align-items: center;
            background: rgba(2, 2, 2, 0.1);
            padding: 20px;
            border-radius: 8px;
            width: 300px;
            z-index: 1001;
        }

        input[type="text"], input[type="email"], input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: none;
            border-radius: 4px;
            z-index: 1001;
        }

        input[type="submit"] {
            background-color: #ff9d4d;
            color: white;
            padding: 10px 73px;
            border: none;
            cursor: pointer;
            transition: opacity 0.3s ease;
            z-index: 1001;

        }

        input[type="submit"]:hover {
            opacity: 0.8;
        }

        .container {
            position: relative;
            z-index: 10;
        }

        section {
            position: absolute;
            width: 100vw;
            height: 150vh;
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
            height: 200%;
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

        button {
        background-color: #ff9d4d;
        color: white;
        padding: 10px 100px;
        border: none;
        cursor: pointer;
        transition: opacity 0.3s ease;
        margin-right: 10px;
        font-family:'Bagel Fat One', sans-serif;
        font-weight: bold;
        z-index: 1001;
    
    }
 
    button:hover {
        opacity: 0.8;
    }
    </style>
</head>
<body>
    <h1>Edit Profile</h1>
    <form method="post">
        <input type="text" name="user_name" value="<?php echo $row['user_name']; ?>" placeholder="Username" required><br>
        <input type="email" name="user_email" value="<?php echo $row['user_email']; ?>" placeholder="Email" required><br>
        <input type="password" name="user_password" value="<?php echo $row['user_password']; ?>" placeholder="Password" required><br>
        <input type="submit" value="Update Profile">
        <br>
        <button onclick="window.location.href='home.php'">Home</button>
    </form>


</body>
</html>

<section>
        <?php for ($i = 0; $i < 100; $i++) {
            echo "<span></span>";
            echo "<span></span>";
            echo "<span></span>";
        } ?>
</section>
