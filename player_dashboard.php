<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gimkit";
 
// Create connection
$con = new mysqli($servername, $username, $password, $dbname);

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
    <title>Dashboard</title>
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
            display:flex;
            justify-content:center;
            align-items:center;
            min-height:100vh;
            background:#010326;

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

        header {
            width:400px;
            border-radius: 20%;
            z-index: 1001;
            position: absolute;
            justify-content:center;
            align-items:center;
            margin-top: -600px;
            color:#F2DC91;
            text-shadow: 0 13px 5px rgba(0,0,0,0.9);
            background-color: none;
            padding: 20px;
            text-align: center;
            font-size: 60px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.5);
        }

        
        main {
            z-index:1001;
            position:absolute;
            padding: 20px;
            max-width: 1200px;
        }

        .card {
            position:absolute;
            background-color: #F2DC91;
            padding: 20px;
            border-radius: 18px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.5);
            margin-top: 200px;
            width:370px;
            margin-left:-690px;
            opacity: 80%;
            border-color: #333;
        }

        .logout-btn {
            z-index:1001;
            display: inline-block;
            padding: 10px 20px;
            background-color: #e74c3c;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
        }

        .logout-btn:hover {
            background-color: #c0392b;
        }

        footer {
            z-index:1001;
            text-align: center;
            padding: 20px;
            background-color: #333;
            color: white;
            margin-top: 20px;
        }
        .loader{
            position: relative;
            height: 350px;
            width: 350px;
            animation: spin 1.5s infinite;
            z-index: 1100;
            transition: opacity 3.8s, visibility 3.6s;
        }
        .loaderBg{
            background-color:#010326;
            padding: 0;
            margin: 0;
            width: 100vw;
            height: 100vh;
            display: grid;
            place-items: center center;
            z-index: 1009;
            transition: opacity 4s, visibility 3.8s;
        }
        @keyframes spin{
            100%{
                transform: rotate(360deg);
            }
        }
        .loader--hidden{
            opacity:0;
            visibility: hidden;
        }
        .loaderBg--hidden{
            opacity:0;
            visibility: hidden;
        }
        .loader>div:nth-child(1){
            z-index: 1100;
            height:75px;
            width: 75px;
            background-image: linear-gradient(
                45deg,
                #DF564A,
                #DFB54A
            );
            position: absolute;
            top: 100px;
            left: 100px;
            box-shadow: 0 0 3px #DF564A;
            border-radius: 50%;
            animation: move1 1.5s infinite;
        }
        @keyframes move1{
            50%{
                transform: translate(-30px,-30px) scale(0.3);
            }
        }
        .loader>div:nth-child(2){
            z-index: 1100;
            height:45px;
            width: 45px;
            background-image: linear-gradient(
                45deg,
                #F2DC91,
                #F4D08A
            );
            position: absolute;
            top: 105px;
            right: 110px;
            box-shadow: 0 0 2px #F2DC91;
            border-radius: 50%;
            animation: move2 1.5s infinite;
        }
        @keyframes move2{
            50%{
                transform: translate(15px,-20px) scale(0.55);
            }
        }
        .loader>div:nth-child(3){
            z-index: 1100;
            height:75px;
            width: 75px;
            background-image: linear-gradient(
                45deg,
                #D99E6A,
                #D5D9AD
            );
            position: absolute;
            bottom: 100px;
            right: 100px;
            box-shadow: 0 0 3px #D99E6A;
            border-radius: 50%;
            animation: move3 1.5s infinite;
        }
        @keyframes move3{
            50%{
                transform: translate(30px,30px) scale(0.3);
            }
        }
        .loader>div:nth-child(4){
            z-index: 1100;
            height:45px;
            width: 45px;
            background-image: linear-gradient(
                45deg,
                #B6D9AD,
                #CDB667
            );
            position: absolute;
            bottom: 105px;
            left: 110px;
            box-shadow: 0 0 3px #B6D9AD;
            border-radius: 50%;
            animation: move4 1.5s infinite;
        }
        @keyframes move4{
            50%{
                transform: translate(-15px,20px) scale(0.55);
            }
        }

a {
    color: white;
}
a:visited {
    color: white;
}
a:hover {
    color: #6F86FF;
}

/* off-screen-menu */
.off-screen-menu {
    background-color:  rgb(34, 37, 49);
    height: 70vh;
    width: 90%;
    max-width: 450px;
    position: fixed;
    top: 0;
    right: -450px;
    display: flex;
    flex-direction: column;
    align-items: center;    
    justify-content: center;
    text-align: center;
    font-size: 1.5em;
    transition: .3s ease;
    border-radius: 10%;
}
.off-screen-menu.active {
    right: 0;
}



/* nav */
.navmain {
    position:absolute;
    z-index: 1001;
    margin-top:-710px;
    display: block;
    margin-right:-1270px;
}
.nav {
    position:absolute;
    z-index: 1001;
    padding:20px;
    display: block;
}




/* ham menu */
.ham-menu {
    height: 50px;
    width: 40px;
    margin-left: 0px;
    position: relative;
}
.ham-menu span {
    height: 10px;
    width: 100%;
    background-color: #000;
    border-radius: 25px;
    position: absolute;
    left: 50%;
    top: 50%;
    transform: translate(-50%, -50%);
    transition: .3s ease;
}
.ham-menu span:nth-child(1) {
    top: 10%;
}
.ham-menu span:nth-child(3) {
    top: 90%;
}
.ham-menu.active span {
    background-color: white;
}
.ham-menu.active span:nth-child(1) {
    top: 50%;
    transform: translate(-50%, -50%) rotate(45deg);
}
.ham-menu.active span:nth-child(2) {
    opacity: 0;
}
.ham-menu.active span:nth-child(3) {
    top: 50%;
    transform: translate(-50%, -50%) rotate(-45deg);
}
        .trying2{
            position:absolute;
            background:#000;
            width:1100%;
            margin-left:-690px;
            bottom:-80px;
            padding:20px;
            border-radius: 5%;
            color:#F2DC91;
            opacity:85%;
            box-shadow: 0 15px 35px rgba(0,0,0,0.5);
        }
        .badge{
            position:absolute;
            background:#000;
            width:1100%;
            margin-left:-220px;
            margin-top:-200px;
            padding:20px;
            border-radius: 5%;
            height:1300%;
            color:#F2DC91;
            opacity:85%;
            box-shadow: 0 15px 35px rgba(0,0,0,0.5);
            
        }
        .logofordashboard{
            position:absolute; 
            z-index:1001;
            border-radius: 20%;
            margin-top:-630px; 
            margin-right:-1350px; 
            width:100px; 
            opacity:80%
        }
        .Triangle_{
            position:absolute; 
            z-index:1001; 
            width:500px; 
            left:1027px; 
            top:300px;
        }
        .student{
            position:absolute; 
            z-index:1002; 
            width:100px; 
            bottom:50px; 
            left:300px;
        }
    </style>
    <script>
        window.addEventListener("load", ()=>{
            const loader = document.querySelector(".loader");

            loader.classList.add("loader--hidden");

            loader.addEventListener("transitionend", () => {
                document.body.removeChild(loader);
            });
        });
        window.addEventListener("load", ()=>{
            const loaderBg = document.querySelector(".loaderBg");

            loaderBg.classList.add("loaderBg--hidden");

            loaderBg.addEventListener("transitionend", () => {
                document.body.removeChild(loaderBg);
            });
        });
    </script>
    </style>
</head>
<body>
    <div class="loaderBg">
        <div class="loader">
            <div></div>
            <div></div>
            <div></div>
            <div></div>
        </div>
    </div>
    <audio autoplay loop>
        <source src="Burgundy - All Night (freetouse.com).mp3" type="audio/mp3">
    <div class="cursor"></div>
    <script>
        const cursor = document.querySelector('.cursor');

        document.addEventListener('mousemove', e => {
            cursor.setAttribute("style", "top: "+(e.pageY - 11)+"px; left: "+(e.pageX - 10)+"px")
        })
    </script>
    </audio>
    
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
            echo "<span></span>";
            echo "<span></span>";
        } 
    ?>
    </section>

<header>
    Player Dashboard
</header>
<img class="logofordashboard" src="logo1">
<img class="Triangle_" src="Triangle_ 2">
<img class="student" src="Student_">


<nav class="navmain">
    <div class="off-screen-menu">
      <ul>
        <li><a href="userprofile.php">View My Profile</a></li>
        <li><a href="feedback.php">Submit Feedback</a></li>
        <li><a href="ticket.php">Submit a Ticket</a></li>
        <li><a href="view_my_tickets.php">View My Tickets</a></li>
        <li><a href="levelpage.php">Attempt Level</a></li>
        <li><a href="viewmaterial.php">View Material</a></li>
        <li><a href="forumhome.php">Forum Page</a></li>
        <li><a href="content.php">View My Activities</a></li>
        <li><a href="viewfaqpage.php">View FAQ Page</a></li>
        <li><a href="leaderboard.php">Leaderboard</a></li>
        <li><a href="checkpvpresult.php">Check My Previous PvP Results</a></li>
        <li><a href="creategamesession.php">Create Room for PVP</a></li>
        <li><a href="pvpRoom.php">Find Room for PVP</a></li>
      </ul>
    </div>

    <nav class="nav">
      <div class="ham-menu">
        <span></span>
        <span></span>
        <span></span>
      </div>
    </nav>
</nav>
<script>
    const hamMenu = document.querySelector(".ham-menu");

    const offScreenMenu = document.querySelector(".off-screen-menu");

    hamMenu.addEventListener("click", () => {
    hamMenu.classList.toggle("active");
    offScreenMenu.classList.toggle("active");
    });
</script>
<main>
    <div class="card">
        <h2 style="color:#333;">Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</h2>
        <p>Your role: <?php echo htmlspecialchars($_SESSION['user_role']); ?></p>
        <a href="logout.php" class="logout-btn">Logout</a>
    </div>


<div class="badge">
<?php
    // Function to check and award exp badge
    function checkAndAwardExpBadge($user_id, $con) {
    // Check user's total exp
    $sql = "SELECT totalexp FROM leaderboard WHERE user_id = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    $messages = [];

    if ($row) {
        $totalExp = $row['totalexp'];
        $expLevels = [
            ['exp' => 50, 'badge_id' => 9, 'name' => 'The Overachiever'],
            ['exp' => 100, 'badge_id' => 13, 'name' => 'The Hardworker'],
            ['exp' => 150, 'badge_id' => 14, 'name' => 'Maestro of Learning'],
            ['exp' => 200, 'badge_id' => 15, 'name' => 'Mr. Prof All Knowing'],
            ['exp' => 250, 'badge_id' => 16, 'name' => 'The Sigma Grinder'],
            ['exp' => 300, 'badge_id' => 17, 'name' => 'The Gigachad']
        ];

        foreach ($expLevels as $level) {
            if ($totalExp >= $level['exp']) {
                // Check if user already has this badge
                $sql = "SELECT * FROM user_badge WHERE user_id = ? AND badge_id = ?";
                $stmt = $con->prepare($sql);
                $stmt->bind_param("ii", $user_id, $level['badge_id']);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows == 0) {
                    // User doesn't have this badge, so award it
                    $sql = "INSERT INTO user_badge (user_id, badge_id) VALUES (?, ?)";
                    $stmt = $con->prepare($sql);
                    $stmt->bind_param("ii", $user_id, $level['badge_id']);
                    if ($stmt->execute()) {
                        $messages[] = "Congratulations! You've been awarded the '{$level['name']}' badge for reaching {$level['exp']} total EXP!";
                    }
                }
            }
        }
    }

    return $messages;
}
    

    // Check and award exp badge
    $expBadgeMessage = checkAndAwardExpBadge($_SESSION['user_id'], $con);
    ?>
<?php
    // Function to retrieve all badges for a user with their image URLs
    function getUserBadges($user_id, $con) {
        $sql = "
            SELECT b.badge_id, b.badge_name, b.description, m.media_URL
            FROM user_badge ub
            JOIN badge b ON ub.badge_id = b.badge_id
            LEFT JOIN media m ON m.badge_id = b.badge_id
            WHERE ub.user_id = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        $badges = [];
        while ($row = $result->fetch_assoc()) {
            $badges[] = $row;
        }

        return $badges;
    }

    // Fetch and display user badges
    $badges = getUserBadges($_SESSION['user_id'], $con);
    
    if (count($badges) > 0) {
        echo "<div style='display: flex; flex-wrap: wrap;'>";
        foreach ($badges as $badge) {
            echo "<div style='margin: 10px; text-align: center;'>";
            if (!empty($badge['media_URL'])) {
                echo "<img src='" . htmlspecialchars($badge['media_URL']) . "' alt='" . htmlspecialchars($badge['badge_name']) . "' style='width: 100px; height: 100px; object-fit: cover;'><br>";
            } else {
                echo "<div style='width: 100px; height: 100px; background-color: #ccc; display: flex; justify-content: center; align-items: center;'>No Image</div><br>";
            }
            echo "<strong>" . htmlspecialchars($badge['badge_name']) . "</strong><br>";
            echo "<small>" . htmlspecialchars($badge['description']) . "</small>";
            echo "</div>";
        }
        echo "</div>";
    } else {
        echo "<p>This user has not obtained any badges yet.</p>";
    }
    ?>

</div>
<div class="trying2">
<?php
    // Function to retrieve completed levels for a user
    function getUserCompletedLevels($user_id, $con) {
        $sql = "
            SELECT level.level_name, user_levels.completion_time, user_levels.expAllocated 
            FROM user_levels 
            JOIN level ON user_levels.level_id = level.level_id 
            WHERE user_levels.user_id = ? AND user_levels.isdone = 1";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $levels = [];
        while ($row = $result->fetch_assoc()) {
            $levels[] = $row;
        }
        
        return $levels;
    }

    // Fetch and display completed levels
    $levels = getUserCompletedLevels($_SESSION['user_id'], $con);
    
    if (count($levels) > 0) {
        echo "<table border='1'>";
        echo "<tr><th>Level Name</th><th>Completion Time</th><th>EXP Earned</th></tr>";
        foreach ($levels as $level) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($level['level_name']) . "</td>";
            echo "<td>" . htmlspecialchars($level['completion_time']) . "</td>";
            echo "<td>" . htmlspecialchars($level['expAllocated']) . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>This user has not completed any levels yet.</p>";
    }
    ?>
</div>
</main>
</body>
</html>
