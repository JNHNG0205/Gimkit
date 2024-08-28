<?php
include("session.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forum</title>
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
            color: #fff;
        }

        h1 {
            font-size: 50px;
            margin-bottom: 30px;
            color: gold;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.8);
            text-align: center;
        }

        .container {
            position: relative;
            z-index: 10;
            width: 600px;
            padding: 20px;
            background: rgba(2, 2, 2, 0.1);
            border-radius: 8px;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        input[type="text"], textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid #ff9d4d;
            color: #fff;
            border-radius: 4px;
        }

        input[type="file"] {
            margin-bottom: 20px;
        }

        input[type="submit"] {
            background-color: #ff9d4d;
            color: white;
            padding: 10px 15px;
            border: none;
            cursor: pointer;
            transition: opacity 0.3s ease;
            border-radius: 4px;
        }

        input[type="submit"]:hover {
            opacity: 0.8;
        }

        .home-button {
            background-color: #ff9d4d;
            color: white;
            padding: 10px 260px;
            border: none;
            cursor: pointer;
            transition: opacity 0.3s ease;
            border-radius: 4px;
            margin-top: 10px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
        }

        .home-button:hover {
            opacity: 0.8;
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
            z-index: -1;
        }

        section::before {
            content: '';
            position: absolute;
            width: 100%;
            height: 200%;
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
    </style>
</head>
<body>
    <section>
        <?php for ($i = 0; $i < 100; $i++) {
            echo "<span></span>";
            echo "<span></span>";
            echo "<span></span>";
            echo "<span></span>";
            echo "<span></span>";
        } ?>
    </section>

    <div class="container">
        <h1>Forum page</h1>
        <form action="insert.php" method="post" enctype="multipart/form-data">
            <input type="text" name="discussion_title" required placeholder="Topic">
            <textarea name="discussion_text" rows="4" required placeholder="Content"></textarea>
            <input type="file" id="fileToUpload" name="fileToUpload" accept="image/*">
            <input type="submit" value="Upload Post" name="submitBtn">
        </form>
        <a href="home.php" class="home-button">Home</a>
    </div>
</body>
</html>
