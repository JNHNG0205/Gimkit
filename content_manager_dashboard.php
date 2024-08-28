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
    <title>Content Manager Dashboard</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Bagel+Fat+One&family=Quicksand:wght@300..700&display=swap');
        *
{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:'Bagel Fat One', sans-serif;
    cursor: url('../images/icons8-cursor-100.png'), auto;
    letter-spacing: 0.05em;
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
            padding: 10px 0;
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
        }

        nav ul {
            list-style-type: none;
            padding: 0;
        }

        nav ul li {
            margin: 10px 0;
        }

        nav ul li a {
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            display: block;
        }

        nav ul li a:hover {
            background-color: #555;
        }

        main {
            width:30%;
            margin-left: 210px;
            margin-top: 10px;
            padding: 20px;
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
        .main{
            background:#fff;
            border-radius: 5%;

        }
        .student{
            position:absolute; 
            z-index:1002; 
            width:100px; 
            margin-left:300px;
            
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
    Content Manager Dashboard
</header>

<nav>
    <ul>
        <li><a href="userprofile.php">View My Profile</a></li>
        <li><a href="addlevel.php">Create a New Level</a></li>
        <li><a href="managelevels.php">Manage Questions</a></li>
        <li><a href="forumhome.php">Forum Page</a></li>
        <li><a href="contentmanager.php">View All User Activity</a></li>
        <li><a href="addmaterial.php">Add Material</a></li>
        <li><a href="viewmaterial.php">View Material</a></li>
        <li><a href="editmaterial.php">Edit Material</a></li>
        <li><a href="deletematerial.php">Delete Material</a></li>
        <li><a href="edit_badge.php">Badges Management</a></li>
        <li><a href="content.php">View My Activity</a></li>
    </ul>
</nav>

<main class="main">
<img class="student" src="Admin">
    <h1>Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</h1>
    <p class="welcome">Your role: <?php echo htmlspecialchars($_SESSION['user_role']); ?></p>
    <a href="logout.php" class="logout-btn">Logout</a>
</main>

</body>
</html>
