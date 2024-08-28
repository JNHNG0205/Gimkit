<?php
include("conn.php");

// Get feedback from database
$sql = "SELECT * FROM feedback";
$result = $con->query($sql);

// Calculate average rating
$total_rating = 0;
$count = 0;
while($row = $result->fetch_assoc()) {
    $total_rating += $row['rating'];
    $count++;
}
$average_rating = $total_rating / $count;
$average_rating = $total_rating / $count;

// Determine the number of decimal places needed to round to 2 significant figures
$significant_figures = 2;
$rounding_precision = $significant_figures - floor(log10(abs($average_rating))) - 1;

// Round the value to the appropriate number of decimal places
$average_rating = round($average_rating, $rounding_precision);

// Get feedback trends
$trend_sql = "SELECT DATE_FORMAT(created_at, '%Y-%m') as month, COUNT(*) as total FROM feedback GROUP BY month";
$trend_result = $con->query($trend_sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback Report</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="icon" type="image/x-icon" href="logo1.png">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Bagel+Fat+One&family=Quicksand:wght@300..700&display=swap');
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            list-style: none;
            text-decoration: none;
            font-family:'Bagel Fat One', sans-serif;
            cursor: url('icons8-cursor-100.png'), auto;
            letter-spacing: 0.05em;
        }
        body {
            margin: 0;
            padding: 0;
            min-height:100vh;
            background:#010326;
        }
        section{
            position:absolute;
            width:100vw;
            height:300vh;
            display:flex;
            justify-content:center;
            align-items:center;
            gap:2px;
            flex-wrap:wrap;
            overflow: hidden;
            z-index:0;
        }
        section::before{
            content:'';
            position: absolute;
            width: 100%;
            height: 100%;
            background:linear-gradient(#010326, #965edb, #9249e1,rgb(127, 45, 165),rgb(138, 33, 173), #010326);
            animation: animate 10s linear infinite;
        }
        @keyframes animate {
            0%{
                transform: translateY(-100%);
            }
            100%{
                transform: translateY(100%);
            }
        }
        section span{
            position:relative;
            display:circle;
            width: calc(6.25vw - 2px);
            height: calc(6.25vw - 2px);
            border-radius: 30%;
            background:#222567;
            z-index: 2;
            transition: 1.3s;
        }
        .home-button {
            position: absolute;
            top: 20px;
            right: 20px;
            z-index: 1002;
            padding: 10px 20px;
            background-color: #F2DC91;
            color: #000;
            text-decoration: none;
            font-family: 'Bagel Fat One', sans-serif;
            font-weight: bold;
            border-radius: 5px;
        }


    </style>
</head>
<body>
    <a href="home.php" class="home-button">Home</a>
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

   

    <h1 style="position:absolute; z-index:1001; margin-left:500px;color:#F2DC91;text-shadow: 0 13px 5px rgba(0,0,0,0.9); font-size:400%">Feedback Report</h1>
    <p style="position:absolute; z-index:1001;margin-left:600px;color:#D99E6A;text-shadow: 0 13px 5px rgba(0,0,0,0.9); font-size:200% ;margin-top:120px;">Average Rating: <?php echo $average_rating; ?></p>

    <h2 style="position:absolute; z-index:1001;margin-left:650px;margin-top:180px;color:#D99E6A;">Feedback Trends</h2>
    <canvas id="feedbackChart" width="500" height="300" style="position:absolute; z-index:1001;margin-left:510px; margin-top:220px; background-color:#333;"></canvas>
    <br>

        <h2 style="position:absolute; z-index:1001;margin-left:600px;margin-top:530px;color:#D99E6A;text-shadow: 0 13px 5px rgba(0,0,0,0.9); font-size:200% ">Monthly Feedback Received</h2>
        
        <br>
        <br>
        <table style="font-size:150%;border:1;position:absolute; z-index:1001;margin-left:50px;margin-top:590px;color:#D99E6A;background-color:#333;">
            <tr style=" z-index:1001;color:#F2DC91; font-size: 40px;">
                <th>Rating</th>
                <th>Feedback</th>
            </tr>
            <?php
            $result->data_seek(0);
            while($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row['rating'] . "</td>";
                echo "<td>" . $row['feedback_text'] . "</td>";
                echo "</tr>";
            }
            ?>
        </table>

        <script>
        var ctx = document.getElementById('feedbackChart').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: [
                    <?php
                    while($row = $trend_result->fetch_assoc()) {
                        echo "'" . $row['month'] . "',";
                    }
                    ?>
                ],
                datasets: [{
                    label: 'Feedbacks',
                    data: [
                        <?php
                        $trend_result->data_seek(0);
                        while($row = $trend_result->fetch_assoc()) {
                            echo $row['total'] . ",";
                        }
                        ?>
                    ],
                    backgroundColor: 'blue'
                }]
            },
            options: {
                responsive: false
            }
        });
        </script>
</body>
</html>

<?php
$con->close();
?>