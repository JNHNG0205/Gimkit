<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gimkit";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch materials
$sql_materials = "SELECT * FROM materials";
$result_materials = $conn->query($sql_materials);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Media Display</title>
    <link rel="icon" type="image/x-icon" href="logo1.png">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Bagel+Fat+One&family=Quicksand:wght@300..700&display=swap');
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Bagel Fat One', sans-serif;
            cursor: url('icons8-cursor-100.png'), auto;
        }

        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: #010326;
            overflow-x: hidden;
            margin: 0;
            font-family:'Bagel Fat One', sans-serif;
        }

        .container {
            background-color: rgba(0, 0, 0, 0.65);
            border-radius: 10px;
            box-shadow: 0 15px 50px rgba(0, 0, 0, 0.1);
            width: 800px;
            max-width: 90%;
            padding: 40px;
            z-index: 10;
            overflow-y: auto;
            max-height: 80vh;
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
        }

        .material {
            border: 1px solid rgba(242, 220, 145, 0.2);
            padding: 20px;
            margin-bottom: 20px;
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 5px;
            transition: all 0.3s ease;
        }

        .material:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }

        .material h2 {
            color: #F2DC91;
            font-family: 'Bagel Fat One', sans-serif;
            margin-bottom: 10px;
        }

        .material p {
            color: #fff;
            line-height: 1.6;
        }

        .media {
            margin-top: 10px;
            width: 250px;
            height: 250px;
        }

        .media img {
            max-width: 100%;
            height: auto;
            border-radius: 5px;
        }

        .media a {
            color: #F2DC91;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .media a:hover {
            color: #e0ca7f;
        }

        .back-button {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }

        .back-button a {
            background-color: #F2DC91;
            color: #010326;
            text-decoration: none;
            padding: 10px 340px;
            border-radius: 5px;
            font-weight: bold;
            transition: background-color 0.3s ease;
            font-family: 'Bagel Fat One', sans-serif;
        }

        .back-button a:hover {
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

        p {
            color: white;
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

    <div class="container">
        <h1>Media Display</h1>
        
        <?php
        if ($result_materials->num_rows > 0) {
            while($row_material = $result_materials->fetch_assoc()) {
                echo "<div class='material'>";
                echo "<h2>" . htmlspecialchars($row_material["title"]) . "</h2>";
                echo "<p>" . htmlspecialchars($row_material["description"]) . "</p>";
                
                // Fetch media for this material
                $sql_media = "SELECT * FROM media WHERE material_id = " . $row_material["material_id"];
                $result_media = $conn->query($sql_media);
                
                if ($result_media->num_rows > 0) {
                    while($row_media = $result_media->fetch_assoc()) {
                        echo "<div class='media'>";
                        if ($row_media["media_type"] == "image") {
                            $image_path = htmlspecialchars($row_media["media_URL"]);
                            echo "<img src='" . $image_path . "' alt='Media'>";

                        } else {
                            echo "<p>Media URL: <a href='" . htmlspecialchars($row_media["media_URL"]) . "' target='_blank'>" . htmlspecialchars($row_media["media_URL"]) . "</a></p>";
                        }
                        echo "</div>";
                    }
                } else {
                    echo "<p>No media found for this material.</p>";
                }
                
                echo "</div>";
            }
        } else {
            echo "<p>No materials found.</p>";
        }
        
        $conn->close();
        ?>
        
        <div class="back-button">
            <a href="home.php">Home</a>
        </div>
    </div>

    <script>
        const cursor = document.querySelector('.cursor');

        document.addEventListener('mousemove', e => {
            cursor.setAttribute("style", "top: "+(e.pageY - 10)+"px; left: "+(e.pageX - 10)+"px");
        });
    </script>
</body>
</html>