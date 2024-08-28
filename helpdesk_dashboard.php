<?php
session_start();

// Check if the user is logged in, if not redirect to login page
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html"); // Redirect to login page if not logged in
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="logo1.png">
    <title>Helpdesk Dashboard</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Bagel+Fat+One&family=Quicksand:wght@300..700&display=swap');
        *
        
{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:'Bagel Fat One', sans-serif;
    cursor: url('../images/icons8-cursor-100.png'), auto;
}
body{
    min-height:100vh;
    background:#010326;
}
.animation{
    z-index: 1;
    width: 300px;
}
.cursor{
    width: 20px;
    height: 20px;
    border:1px;
    position: absolute;
}
section{
    position:absolute;
    width:100vw;
    height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
    gap:2px;
    flex-wrap:wrap;
    overflow: hidden;
    z-index: -3;
}
section::before{
    content:'';
    position: absolute;
    width: 100%;
    height: 100%;
    background:linear-gradient(#010326, #37fd12, #37fd12,#37fd12,#37fd12, #010326);
    animation: animate 10s linear infinite;
    opacity: 50%;
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
    border-radius: 10%;
    background:#000;
    z-index: 1;
    transition: 1.3s;
}

        header {
            background-color: #333;
            color: white;
            padding: 15px;
            text-align: center;
            font-size: 24px;
        }

        nav {
            width: 200px;
            background-color: #444;
            position: fixed;
            top: 0;
            left: 0;
            height: 100%;
            padding-top: 20px;
            font-family: 'Bagel Fat One', sans-serif;
        }

        nav ul {
            list-style-type: none;
            padding: 0;
        }

        nav ul li {
            margin: 15px 0;
        }

        nav ul li a {
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            display: block;
            font-size: 16px;
        }

        nav ul li a:hover {
            background-color: #555;
        }

        .main{
            position:absolute;
            background:#fff;
            border-radius: 5%;
            width:30%;
            padding:1%;
            margin-left:210px;
            margin-top:10px;


        }
        .student{
            top:40px;
            margin-left:330px;
            position:absolute; 
            z-index:1002; 
            width:100px; 
            
        }

        h1 {
            color: #333;
        }

        .welcome {
            font-size: 18px;
            color: #666;
        }

        .logout-btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #e74c3c;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }

        .logout-btn:hover {
            background-color: #c0392b;
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
    </section>

<header>
    Helpdesk Dashboard
</header>

<nav>
    <ul>
        <li><a href="userprofile.php">View My Profile</a></li>
        <li><a href="feedback_review.php">Feedback Review</a></li>
        <li><a href="generate_report_feedback.php">View Feedback Report</a></li>
        <li><a href="view_ticket.php">View All Submitted Tickets</a></li>
        <li><a href="addfaq.php">Add FAQ</a></li>
        <li><a href="editfaq.php">Edit FAQ</a></li>
        <li><a href="helpdesk_faqviewpage.php">View FAQ Page</a></li>
        <li><a href="forumhome.php">Forum Page</a></li>
        <li><a href="content.php">View My Latest Activities</a></li>
    </ul>
</nav>

<main class="main">
<img class="student" src="Admin">
    <h1>Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</h1>
    <br>
    <p class="welcome">Your role: <?php echo htmlspecialchars($_SESSION['user_role']); ?></p>
    <br>
    <a href="logout.php" class="logout-btn">Logout</a>
</main>

</body>
</html>
