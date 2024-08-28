<?php
session_start();
include("conn.php");

// Check if user is logged in and has helpdesk_support role
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'helpdesk_support') {
    echo "<script>alert('You must be logged in as helpdesk support to access this page.'); window.location.href = 'login.html';</script>";
    exit();
}

// Fetch all FAQs
$sql = "SELECT f.*, m.media_type, m.media_URL FROM faq f LEFT JOIN media m ON f.faq_id = m.faq_id ORDER BY f.faq_id DESC";
$result = $con->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="logo1.png">
    <title>Helpdesk FAQ View</title>
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
            overflow-x: hidden;
        }

        .container {
            background-color: rgba(0, 0, 0, 0.65);
            border-radius: 10px;
            box-shadow: 0 15px 50px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            width: 800px;
            max-width: 90%;
            z-index: 10;
            padding: 40px;
        }

        h1 {
            text-align: center;
            color: #F2DC91;
            margin-bottom: 30px;
            font-family: 'Bagel Fat One', sans-serif;
        }

        .faq-item {
            margin-bottom: 20px;
            padding: 20px;
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 5px;
            transition: all 0.3s ease;
        }

        .faq-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }

        .faq-title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;
            color: #F2DC91;
            font-family: 'Bagel Fat One', sans-serif;
        }

        .faq-content {
            margin-bottom: 10px;
            line-height: 1.6;
            color: #fff;
            font-family: 'Bagel Fat One', sans-serif;
        }

        .media-preview {
            max-width: 200px;
            max-height: 200px;
            margin-top: 10px;
            border-radius: 5px;
            font-family: 'Bagel Fat One', sans-serif;
        }

        .loaderBg {
            background-color: #010326;
            padding: 0;
            margin: 0;
            width: 100vw;
            height: 100vh;
            display: grid;
            place-items: center center;
            z-index: 1009;
            transition: opacity 4s, visibility 3.8s;
            position: fixed;
            top: 0;
            left: 0;
        }

        .loader {
            position: relative;
            height: 350px;
            width: 350px;
            animation: spin 1.5s infinite;
            z-index: 1100;
            transition: opacity 3.8s, visibility 3.6s;
        }

        @keyframes spin {
            100% {
                transform: rotate(360deg);
            }
        }

        .loader--hidden, .loaderBg--hidden {
            opacity: 0;
            visibility: hidden;
        }

        .loader > div {
            position: absolute;
            border-radius: 50%;
        }

        .loader > div:nth-child(1) {
            height: 75px;
            width: 75px;
            background-image: linear-gradient(45deg, #DF564A, #DFB54A);
            top: 100px;
            left: 100px;
            box-shadow: 0 0 3px #DF564A;
            animation: move1 1.5s infinite;
        }

        .loader > div:nth-child(2) {
            height: 45px;
            width: 45px;
            background-image: linear-gradient(45deg, #F2DC91, #F4D08A);
            top: 105px;
            right: 110px;
            box-shadow: 0 0 2px #F2DC91;
            animation: move2 1.5s infinite;
        }

        .loader > div:nth-child(3) {
            height: 75px;
            width: 75px;
            background-image: linear-gradient(45deg, #D99E6A, #D5D9AD);
            bottom: 100px;
            right: 100px;
            box-shadow: 0 0 3px #D99E6A;
            animation: move3 1.5s infinite;
        }

        .loader > div:nth-child(4) {
            height: 45px;
            width: 45px;
            background-image: linear-gradient(45deg, #B6D9AD, #CDB667);
            bottom: 105px;
            left: 110px;
            box-shadow: 0 0 3px #B6D9AD;
            animation: move4 1.5s infinite;
        }

        @keyframes move1 {
            50% { transform: translate(-30px,-30px) scale(0.3); }
        }

        @keyframes move2 {
            50% { transform: translate(15px,-20px) scale(0.55); }
        }

        @keyframes move3 {
            50% { transform: translate(30px,30px) scale(0.3); }
        }

        @keyframes move4 {
            50% { transform: translate(-15px,20px) scale(0.55); }
        }

        .cursor {
            width: 20px;
            height: 20px;
            border: 1px solid #fff;
            border-radius: 50%;
            position: fixed;
            pointer-events: none;
            z-index: 9999;
        }

        section {
            position: fixed;
            width: 100vw;
            height: 400vh;
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
        .button-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 20px;
        }

        .back-to-home-btn,
        .add-faq-btn {
            display: flex;
            justify-content: center;
            width: 200px;
            margin: 10px 0;
            padding: 10px;
            background-color: #F2DC91;
            color: #010326;
            text-align: center;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
            font-family: 'Bagel Fat One', sans-serif;
        }

        .back-to-home-btn:hover,
        .add-faq-btn:hover {
            background-color: #e0ca7f;
        }
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

    <div class="cursor"></div>

    <section>
        <?php for ($i = 0; $i < 300; $i++): ?>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
        <?php endfor; ?>
    </section>

    <div class="container">
        <h1>Helpdesk FAQ View</h1>
        
       
        
        <a href="addfaq.php" class="add-faq-btn">Add New FAQ</a>
        
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="faq-item">
                <div class="faq-title"><?php echo htmlspecialchars($row['faq_title']); ?></div>
                <div class="faq-content"><?php echo nl2br(htmlspecialchars($row['faq_contents'])); ?></div>
                <?php if (!empty($row['media_URL'])): ?>
                    <div class="faq-media">
                        <?php if ($row['media_type'] == 'image'): ?>
                            <img src="uploads/<?php echo $row['media_URL']; ?>" alt="FAQ Media" class="media-preview">
                        <?php elseif ($row['media_type'] == 'video'): ?>
                            <video width="200" controls>
                                <source src="uploads/<?php echo $row['media_URL']; ?>" type="video/mp4">
                                Your browser does not support the video tag.
                            </video>
                        <?php else: ?>
                            <p>Media: <?php echo $row['media_URL']; ?></p>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php endwhile; ?>
        <a href="helpdesk_dashboard.php" class="back-to-home-btn">Back to Home</a>
    </div>

    <script>
        window.addEventListener("load", () => {
            const loader = document.querySelector(".loader");
            const loaderBg = document.querySelector(".loaderBg");

            loader.classList.add("loader--hidden");
            loaderBg.classList.add("loaderBg--hidden");

            loaderBg.addEventListener("transitionend", () => {
                document.body.removeChild(loaderBg);
            });
        });

        const cursor = document.querySelector('.cursor');

        document.addEventListener('mousemove', e => {
            cursor.setAttribute("style", "top: "+(e.pageY - 10)+"px; left: "+(e.pageX - 10)+"px");
        });
    </script>
</body>
</html>

<?php
$con->close();
?>