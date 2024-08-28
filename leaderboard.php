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

// Calculate totalexp for each user from both user_levels and game_sessions
$calcExpSql = "
    SELECT user_id, SUM(expAllocated) as totalexp
    FROM user_levels
    GROUP BY user_id
    UNION ALL
    SELECT player1_id AS user_id, SUM(player1_pvpEXP) AS totalexp
    FROM game_sessions
    WHERE player1_pvpEXP IS NOT NULL
    GROUP BY player1_id
    UNION ALL
    SELECT player2_id AS user_id, SUM(player2_pvpEXP) AS totalexp
    FROM game_sessions
    WHERE player2_pvpEXP IS NOT NULL
    GROUP BY player2_id
";
$calcExpResult = $conn->query($calcExpSql);

if ($calcExpResult->num_rows > 0) {
    // Clear the leaderboard table before updating
    $clearLeaderboardSql = "DELETE FROM leaderboard";
    $conn->query($clearLeaderboardSql);

    // Aggregate the total EXP for each user and update the leaderboard
    $totalExp = [];
    while ($row = $calcExpResult->fetch_assoc()) {
        $user_id = $row['user_id'];
        if (!isset($totalExp[$user_id])) {
            $totalExp[$user_id] = 0;
        }
        $totalExp[$user_id] += $row['totalexp'];
    }

    foreach ($totalExp as $user_id => $totalexp) {
        // Get the username from the users table
        $usernameSql = "SELECT user_name FROM user WHERE user_id = ?";
        $usernameStmt = $conn->prepare($usernameSql);
        $usernameStmt->bind_param("i", $user_id);
        $usernameStmt->execute();
        $usernameResult = $usernameStmt->get_result();
        $usernameRow = $usernameResult->fetch_assoc();
        $usernameStmt->close();

        if ($usernameRow) {
            $user_name = $usernameRow['user_name'];

            $insertLeaderboardSql = "
                INSERT INTO leaderboard (user_id, user_name, totalexp)
                VALUES (?, ?, ?)
            ";
            $insertLeaderboardStmt = $conn->prepare($insertLeaderboardSql);
            $insertLeaderboardStmt->bind_param("isi", $user_id, $user_name, $totalexp);
            $insertLeaderboardStmt->execute();
            $insertLeaderboardStmt->close();
        }
    }
}

// Retrieve leaderboard data
$leaderboardSql = "SELECT user_name, totalexp FROM leaderboard ORDER BY totalexp DESC";
$leaderboardResult = $conn->query($leaderboardSql);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leaderboard</title>
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

        .leaderboard-container {
            max-width: 800px;
            margin: 0 auto;
            background-color: rgba(2, 2, 2, 0.1);
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            z-index: 10;
            position: relative;
        }

        h1 {
            text-align: center;
            color: gold;
            font-size: 50px;
            margin-bottom: 30px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.8);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: rgba(2, 2, 2, 0.1);
            border-radius: 8px;
            overflow: hidden;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #ff9d4d;
            color: #222;
        }

        tr:nth-child(even) {
            background-color: rgba(255, 255, 255, 0.1);
        }

        .button-container {
            margin-top: 20px;
            text-align: center;
        }

        button {
            background-color: #ff9d4d;
            color: white;
            padding: 10px 150px;
            border: none;
            cursor: pointer;
            transition: opacity 0.3s ease;
        }

        button:hover {
            opacity: 0.8;
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
    <div class="leaderboard-container">
        <h1>Leaderboard</h1>
        <table>
            <tr>
                <th>Rank</th>
                <th>Username</th>
                <th>User Level</th>
            </tr>
            <?php if ($leaderboardResult->num_rows > 0): ?>
                <?php $rank = 1; ?>
                <?php while($row = $leaderboardResult->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $rank++; ?></td>
                        <td><?php echo htmlspecialchars($row['user_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['totalexp']); ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="3">No data available</td>
                </tr>
            <?php endif; ?>
        </table>
        <div class="button-container">
            <button onclick="window.location.href='home.php'">Home</button>
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
