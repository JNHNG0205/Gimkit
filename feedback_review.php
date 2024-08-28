<?php
include("conn.php");

// Check if search is set
if (isset($_GET['search'])) {
    $search = $_GET['search'];
} else {
    $search = '';
}

// Get feedbacks from database, searching across all relevant fields
$sql = "SELECT * FROM feedback 
        WHERE feedback_text LIKE '%" . $search . "%' 
        OR rating LIKE '%" . $search . "%' 
        OR user_id LIKE '%" . $search . "%' 
        OR feedback_id LIKE '%" . $search . "%' 
        ORDER BY created_at DESC";
$result = $con->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Feedbacks</title>
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
            color: #222;
        }

        .container {
            margin-top: 500px;
            position: absolute;
            z-index: 1001;
            width: 80%;
            max-width: 1000px;
            padding: 40px;
            background-color: rgba(2, 2, 2, 0.6);
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
            text-align: center;
        }

        h1 {
            font-size: 36px;
            margin-bottom: 20px;
            color: gold;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
        }

        form {
            margin-bottom: 20px;
        }

        input[type="text"] {
            width: 70%;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 16px;
        }

        input[type="submit"] {
            background-color: #F2A765;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 18px;
            transition: opacity 0.3s ease;
            margin-left: 10px;
        }

        input[type="submit"]:hover {
            opacity: 0.8;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            text-align: left;
            background-color: rgba(255, 255, 255, 0.9);
        }

        th, td {
            padding: 12px;
            border: 1px solid #ddd;
        }

        th {
            background-color: #F2A765;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        section {
            position: absolute;
            width: 100vw;
            height: 300vh;
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
            height: 100%;
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

        .btn {
            display: block;
            width: 200px;
            padding: 10px;
            margin: 30px auto 0;
            text-align: center;
            color: #fff;
            background-color: #ff9d4d;
            border: none;
            border-radius: 5px;
            font-size: 18px;
            cursor: pointer;
            text-decoration: none;
        }

        .btn:hover {
            background-color: #ffbe8d;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>All Feedbacks</h1>
        <form method="get">
            <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="Search feedback...">
            <input type="submit" value="Search">
        </form>
        <table>
            <tr>
                <th>Feedback ID</th>
                <th>User ID</th>
                <th>Rating</th>
                <th>Feedback</th>
                <th>Date</th>
            </tr>
            <?php
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['feedback_id'] . "</td>";
                    echo "<td>" . $row['user_id'] . "</td>";
                    echo "<td>" . $row['rating'] . "</td>";
                    echo "<td>" . htmlspecialchars($row['feedback_text']) . "</td>";
                    echo "<td>" . $row['created_at'] . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='5'>No feedback found</td></tr>";
            }
            ?>
        </table>
        <a href="home.php" class="btn">Home</a>
    </div>

    <section>
        <?php for ($i = 0; $i < 100; $i++) {
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

<?php
$con->close();
?>
