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

$sql = "SELECT user_id, user_name, user_role, user_email FROM user WHERE user_id = " . $_SESSION['user_id'];

$result = mysqli_query($con, $sql);

if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="logo1.png">
    <title>Profile</title>
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
    display:flex;
    justify-content:center;
    align-items:center;
    min-height:100vh;
    background:#010326;
}
.h1{
    position:absolute;
    margin-top:-700px;
    font-size:400%;
    z-index: 1001;
    color:#F2DC91;
    text-shadow: 0 13px 5px rgba(0,0,0,0.9);
}
.container{
    padding:2%;
    background-color: #333;
    align-items: center;
    justify-content: center;
    width:350px;
    height:auto;
    position: absolute;
    z-index: 1001;
    border-radius: 5%;
    box-shadow: 0 15px 35px rgba(0,0,0,0.5);
    
}
.backbtn{
    z-index: 1001;
    position: absolute;
    justify-content:center;
    align-items:center;
    background: none;
    border: none;
    color:#fff;
    bottom:5px;
    font-size:90px;
    margin-right:-350px;
}
.animation{
    animation: fade-up 0.5s;
    z-index: 1002;
    width:400px;
    height:310px;
}
@keyframes fade-up{
    0%{
        opacity: 0;
        transform: translateY(30px) scale(0.9);
    }
    100%{
        opacity: 1;
        transform: translateY(0px) scale(1);
    }
}
.backbtn .wordback{
    visibility: hidden;
    width: 150px;
    height:50px;
    bottom:80%;
    left:110%;
    margin-bottom: 13%;
    margin-left:-150px;
    background-color: #333;
    color:#F2DC91;
    text-align: center;
    align-items: center;
    border-radius: 6px;
    padding: 10px 0;
    z-index: 1005;
    position:absolute;
    font-size: medium;
    list-style: none;
    text-decoration: none;
}
.backbtn:hover .wordback{
    visibility: visible;
}
.student{
            position:absolute; 
            z-index:1002; 
            width:10%;

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

    <h1 class="h1">Profile</h1>
    
    <div class="container">
        <p style="top:200px; color:#D99E6A; font-size:200%;text-shadow: 0 13px 5px rgba(0,0,0,0.9);">Username: <?php echo htmlspecialchars($row['user_name']);  ?></p>
        <p style="color:#D99E6A;">UserID: <?php echo htmlspecialchars($row['user_id']); ?></p>
        <p style="color:#D99E6A;">Email: <?php echo htmlspecialchars($row['user_email']); ?></p>
        <p style="color:#D99E6A;">User role: <?php echo htmlspecialchars($row['user_role']); ?></p>
        <button style=" height:80%; width:40%; margin-top:20px;margin-left:85px; background: #F2DC91;color: #111;" onclick="document.location='edituserprofile.php'">Edit</button>
        
    </div>
    <a href="home.php" class="backbtn">
            <img class="animation"  src="Student_"alt="Animated Image">
            <span class="wordback">Press me to go to Home!</span>
        </a>

    
    
</body>
</html>

<?php
} else {
    echo "User not found.";
}

$con->close();
?>
