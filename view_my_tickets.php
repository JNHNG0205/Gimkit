<?php
include("conn.php");
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Tickets and Responses</title>
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
            position: relative;
            width: 80%;
            max-width: 1000px;
            padding: 40px;
            background-color: rgba(2, 2, 2, 0.6);
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
            text-align: center;
            z-index: 1001;
        }

        h2 {
            font-size: 36px;
            margin-bottom: 20px;
            color: gold;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
            z-index: 1001;
        }

        .ticket, .response {
            text-align: left;
            margin-bottom: 20px;
            padding: 20px;
            border-radius: 8px;
            background-color: rgba(255, 255, 255, 0.9);
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
            z-index: 1001;
        }

        .ticket {
            border: 2px solid #F2A765;
            z-index: 1001;
        }

        .response {
            border: 2px solid #00ddff;
            margin-left: 20px;
            z-index: 1001;
        }

        hr {
            border: 1px solid #ddd;
            margin: 10px 0;
            z-index: 1001;
        }

        .btn {
            display: block;
            width: 200px;
            padding: 10px 460px;
            margin: 30px auto 0;
            text-align: center;
            color: #fff;
            background-color: #ff9d4d;
            border: none;
            border-radius: 5px;
            font-size: 18px;
            cursor: pointer;
            text-decoration: none;
            z-index: 1001;
        }

        .btn:hover {
            background-color: #ffbe8d;
        }

        section {
            position: fixed;
            width: 100vw;
            height: 500vh;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 2px;
            flex-wrap: wrap;
            overflow: hidden;
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
            0% { transform: translateY(-100%); }
            100% { transform: translateY(100%); }
        }

        section span {
            position: relative;
            display: block;
            width: calc(6.25vw - 2px);
            height: calc(6.25vw - 2px);
            background: #222567;
            z-index: 2;
            transition: 1.3s;
            border-radius: 30%;
        }

        section span:hover {
            background: #00ddff;
            transition: 0s;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php
        if (isset($_SESSION['user_id'])) {
            $user_id = $_SESSION['user_id'];

            $ticket_sql = "SELECT * FROM tickets WHERE user_id = '$user_id'";
            $ticket_result = mysqli_query($con, $ticket_sql);

            if ($ticket_result) {
                if (mysqli_num_rows($ticket_result) > 0) {
                    echo "<h2>Your Tickets and Responses</h2>";
                    while ($ticket_row = mysqli_fetch_assoc($ticket_result)) {
                        echo "<div class='ticket'>";
                        echo "<strong>Ticket ID:</strong> " . $ticket_row['ticket_id'] . "<br>";
                        echo "<strong>Subject:</strong> " . $ticket_row['subject'] . "<br>";
                        echo "<strong>Description:</strong> " . $ticket_row['description'] . "<br>";
                        echo "<strong>Status:</strong> " . $ticket_row['status'] . "<br>";
                        echo "<strong>Created At:</strong> " . $ticket_row['created_at'] . "<br>";
                        echo "<strong>Updated At:</strong> " . $ticket_row['updated_at'] . "<br><br>";

                        $response_sql = "SELECT * FROM ticket_responses WHERE ticket_id = '" . $ticket_row['ticket_id'] . "'";
                        $response_result = mysqli_query($con, $response_sql);

                        if ($response_result && mysqli_num_rows($response_result) > 0) {
                            while ($response_row = mysqli_fetch_assoc($response_result)) {
                                echo "<div class='response'>";
                                echo "<strong>Response ID:</strong> " . $response_row['response_id'] . "<br>";
                                echo "<strong>Response:</strong> " . $response_row['response_text'] . "<br>";
                                echo "<strong>Response Time:</strong> " . $response_row['created_at'] . "<br>";
                                echo "</div>";
                                echo "<hr>";
                            }
                        } else {
                            echo "No responses for this ticket yet.<br><hr>";
                        }

                        echo "</div>";
                    }
                } else {
                    echo "<p>No tickets found.</p>";
                }
            } else {
                echo "<p>Error: " . mysqli_error($con) . "</p>";
            }
        } else {
            echo "<p>Please log in to view your tickets and responses.</p>";
        }

        mysqli_close($con);
        ?>
        <a href="home.php" class="btn">Home</a>
    </div>
    <section>
        <?php for ($i = 0; $i < 300; $i++): ?>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
        <?php endfor; ?>
    </section>
</body>
</html>
