<?php
session_start();
include("conn.php");

// Check if user is logged in and has player role
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'player') {
    echo "<script>alert('You must be logged in as a player to access this page.'); window.location.href = 'login.php';</script>";
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
    <title>View FAQ</title>
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
            background-color: rgba(1, 3, 38, 0.95);
            border-radius: 10px;
            box-shadow: 0 15px 50px rgba(0, 0, 0, 0.1);
            overflow-y: auto;
            width: 800px;
            max-width: 90%;
            z-index: 10;
            padding: 40px;
            max-height: 80vh;
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(242, 220, 145, 0.2);
            position: relative; /* Add this */
        }

        .container::-webkit-scrollbar {
            width: 10px;
        }

        .container::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 5px;
        }

        .container::-webkit-scrollbar-thumb {
            background: #F2DC91;
            border-radius: 5px;
        }

        .container::-webkit-scrollbar-thumb:hover {
            background: #e0ca7f;
        }

        h1 {
            text-align: center;
            color: #F2DC91;
            margin-bottom: 30px;
            font-family: 'Bagel Fat One', sans-serif;
            position: absolute;
            top: 0;
            background-color: rgba(1, 3, 38, 0.95);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            padding: 20px 0;
            z-index: 1;
            margin-top: 0px;
            margin-left: -20px;
            margin-right: -20px;
            padding-left: 20px;
            padding-right: 20px;
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
        }

        .media-preview {
            max-width: 200px;
            max-height: 200px;
            margin-top: 10px;
            border-radius: 5px;
        }

        .no-faq {
            text-align: center;
            font-size: 18px;
            color: #F2DC91;
            margin-top: 50px;
        }

        .home-button {
            display: block;
            width: 100px;
            padding: 10px;
            margin: 0 auto 20px auto;
            background-color: #F2DC91;
            color: #010326;
            text-align: center;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            transition: background-color 0.3s ease;
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 2;
        }

        .home-button:hover {
            background-color: #e0ca7f;
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
            height: 100vh;
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
    <div class="cursor"></div>

    <section>
        <?php for ($i = 0; $i < 300; $i++): ?>
            <span></span>
        <?php endfor; ?>
    </section>

    <h1>Frequently Asked Questions</h1>         <a href="home.php" class="home-button">Home</a>

    <div class="container">


        
        <?php if ($result->num_rows > 0): ?>
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
        <?php else: ?>
            <div class="no-faq">No FAQs found.</div>
        <?php endif; ?>
    </div>

    <script>
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
