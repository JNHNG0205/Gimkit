<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
    @import url('https://fonts.googleapis.com/css2?family=Bagel+Fat+One&family=Quicksand:wght@300..700&display=swap');
    * 
    {
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
    height:200vh;
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
/*#010326, #82c471, #cc926c,#d4cf6e,#cd5f5f, #010326*/
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
section span:hover{
    background:#00ddff;
    transition:0s;
}
.chart-container {
            width: 500px;
            height: 300px;
            position:relative;
            z-index: 1001;
            top: 200px;
            left:500px;
        }
        #activityChart {
            width: 100% !important;
            height: 100% !important;
            z-index: 1001;
        }
        .container {
            position: relative;
            z-index: 10;
            width: 820px;
            padding: 20px;
            background: rgba(2, 2, 2, 0.5);
            border-radius: 8px;
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

        th, td{
            background: #222;
        }
        .action-button {
    padding: 5px 10px;
    background-color: #F2DC91;
    color: #000;
    text-decoration: none;
    font-family: 'Bagel Fat One', sans-serif;
    font-weight: bold;
    border-radius: 5px;
    display: inline-block;
    margin: 2px;
}

.action-button:hover {
    background-color: #e0c870;
}
    </style>
</head>
<body>

<section>
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
        <span></span>
        <span></span>
    </section>
    <a href="home.php" class="home-button">Home</a>

    <h1 style="position:absolute; z-index:1001; margin-left:520px;color:#F2DC91;text-shadow: 0 13px 5px rgba(0,0,0,0.9); font-size:400%">Your Content</h1>
    
    <h2 style="position:absolute; z-index:1001;margin-left:660px;color:#D99E6A;text-shadow: 0 13px 5px rgba(0,0,0,0.9); font-size:200% ;margin-top:120px;">Overview</h2>
    <div class="chart-container">
        <canvas id="activityChart"></canvas>
    </div>

    <h2 style="position:absolute; z-index:1001;margin-left:660px;color:#D99E6A;text-shadow: 0 13px 5px rgba(0,0,0,0.9); font-size:200% ;margin-top:220px;">Your Activity</h2>
    
    <form style="z-index:1001; position:absolute; margin-top:280px;margin-left:620px;color:#D99E6A;" method="get" action="">
        <label for="filter">Filter by:</label>
        <select name="filter" id="filter">
            <option value="all" <?php echo !isset($_GET['filter']) || $_GET['filter'] == 'all' ? 'selected' : ''; ?>>All</option>
            <option value="posts" <?php echo isset($_GET['filter']) && $_GET['filter'] == 'discussion' ? 'selected' : ''; ?>>Posts</option>
            <option value="comments" <?php echo isset($_GET['filter']) && $_GET['filter'] == 'comments' ? 'selected' : ''; ?>>Comments</option>
        </select>
        <input  type="submit" value="Apply Filter">
    </form>
<div style="z-index:1001; position:absolute; margin-left:300px; margin-top:1000px;">
    <?php
    include("conn.php");

    if (!isset($_SESSION['user_id'])) {
        echo 'You need to login first.';
        exit();
    }

    $user_id = $_SESSION['user_id'];

    $sql_discussion = "SELECT * FROM discussion WHERE user_id = $user_id";
    $sql_comments = "SELECT * FROM comment WHERE user_id = $user_id";

    $discussion_result = mysqli_query($con, $sql_discussion);
    $comments_result = mysqli_query($con, $sql_comments);

    $discussion_count = mysqli_num_rows($discussion_result);
    $comment_count = mysqli_num_rows($comments_result);

    mysqli_close($con);
    ?>

    <script>
        
        var ctx = document.getElementById('activityChart').getContext('2d');
        var activityChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Post', 'Comments'],
                datasets: [{
                    label: 'Activity Count',
                    data: [<?php echo $discussion_count; ?>, <?php echo $comment_count; ?>],
                    backgroundColor: [
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)'
                    ],
                    borderColor: [
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            font: {
                                size: 8 
                            }
                        }
                    },
                    x: {
                        ticks: {
                            font: {
                                size: 8 
                            }
                        }
                    }
                },
                plugins: {
                    legend: {
                        labels: {
                            font: {
                                size:20 
                            }
                        }
                    }
                },
                responsive: true,
                maintainAspectRatio: false, 
                layout: {
                    padding: {
                        top: 5,
                        bottom: 5
                    }
                }
            }
        });
    </script>
</div>
<div style="z-index:1001; position:absolute; margin-top:350px; margin-left:200px; color:#D99E6A;">
<?php
   
    include("conn.php");

    $filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';

    $sql = "";

    if ($filter === 'comments' || $filter === 'all') {
        $sql .= "SELECT 'comment' AS type, comment.comment_id AS id, discussion.discussion_id AS discussion_id, discussion.discussion_title AS discussion_title, comment.comment_text AS additional_info, comment.created_at AS created_at 
                 FROM comment 
                 JOIN discussion ON comment.discussion_id = discussion.discussion_id 
                 WHERE comment.user_id = $user_id";
    }

    if ($filter === 'posts' || $filter === 'all') {
        if ($sql) $sql .= " UNION ALL ";
        $sql .= "SELECT 'discussion' AS type, discussion.discussion_id AS id, NULL AS discussion_id, NULL AS discussion_title, discussion.discussion_title AS additional_info, discussion.created_at AS created_at 
                 FROM discussion 
                 WHERE discussion.user_id = $user_id";
    }

    $result = mysqli_query($con, $sql);

    if (mysqli_num_rows($result) > 0) {
        echo '<table border="1" cellspacing="0" cellpadding="10">';
        echo '<tr><th>Type</th><th>Title</th><th>Created At</th><th>Additional Info</th><th>Actions</th></tr>';
        while ($row = mysqli_fetch_array($result)) {
            echo '<tr>';
            echo '<td>' . ($row['type'] === 'comment' ? 'Comment' : 'Post') . '</td>';
            echo '<td>';
            
            if ($row['type'] === 'comment') {
                echo '<a href="view.php?id=' . $row['discussion_id'] . '">' . $row['discussion_title'] . '</a>';
            } else {
                echo '<a href="view.php?id=' . $row['id'] . '">' . $row['additional_info'] . '</a>';
            }
            echo '</td>';
            echo '<td>' . $row['created_at'] . '</td>';
            echo '<td>';
            if ($row['type'] === 'comment') {
                
                echo $row['additional_info'];
            } else {
                
                echo '';
            }
            echo '</td>';
            echo '<td>';
            if ($row['type'] === 'comment') {
                echo '<a class="action-button" href="edit_comment.php?id=' . $row['id'] . '">Edit</a> ';
                echo '<a class="action-button" href="delete_comment.php?id=' . $row['id'] . '" onclick="return confirm(\'Are you sure you want to delete this comment?\');">Delete</a>';
            } else {
                echo '<a class="action-button" href="edit_post.php?id=' . $row['id'] . '">Edit</a> ';
                echo '<a class="action-button" href="delete_post.php?id=' . $row['id'] . '" onclick="return confirm(\'Are you sure you want to delete this post?\');">Delete</a>';
            }
            echo '</td>';
            echo '</tr>';
        }
        echo '</table>';
    } else {
        echo '<p>No results found.</p>';
    }

    mysqli_close($con);
    ?>
</div>
</body>
</html>
