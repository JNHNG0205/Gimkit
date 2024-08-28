<?php
session_start();

// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gimkit";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT gs.session_id, u.user_name, gs.game_state, gs.roompassword 
        FROM game_sessions gs 
        INNER JOIN user u ON gs.player1_id = u.user_id 
        WHERE gs.game_state = 'in_progress'";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html>
<head>
    <title>PVP Rooms</title>
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

        h2 {
            font-size: 50px;
            margin-bottom: 30px;
            color: gold;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.8);
            text-align: left;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            background: rgba(2, 2, 2, 0.1);
            border-radius: 8px;
            overflow: hidden;
        }

        th, td {
            text-align: left;
            padding: 12px;
            border-bottom: 1px solid #ddd;
        }

        tr:nth-child(even) {
            background-color: rgba(255, 255, 255, 0.1);
        }

        th {
            background-color: #ff9d4d;
            color: #222;
        }

        button {
            background-color: #ff9d4d;
            color: white;
            padding: 10px 15px;
            border: none;
            cursor: pointer;
            transition: opacity 0.3s ease;
            margin-right: 10px; /* Add some spacing between buttons */
        }

        button:hover {
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
    </style>
</head>
<body>
    <div class="container">
        <h2>PVP Rooms</h2>
        <?php
        if ($result->num_rows > 0) {
            echo "<table>";
            echo "<tr><th>Session ID</th><th>Player 1</th><th>Game State</th><th>Room Details</th><th>Join</th></tr>";

            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["session_id"] . "</td>";
                echo "<td>" . $row["user_name"] . "</td>";
                echo "<td>In Progress</td>";
                if ($row['roompassword'] == 0) {
                    echo "<td>Welcome all random players! No Password Required.</td>";
                } else {
                    echo "<td>Room Password is required!</td>";
                }
                echo "<td><button onclick=\"window.location.href='joingamesession.php?session_id=" . $row["session_id"] . "'\">Join</button></td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p>No ongoing game sessions.</p>";
        }
        $conn->close();
        ?>
        <br>
        <div class="button-container">
            <button onclick="window.location.href='home.php'">Back to Home</button>
        </div>
    </div>
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
            echo "<span></span>";

        } ?>
    </section>
</body>
</html>
