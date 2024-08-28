<?php
session_start();
include("conn.php");

// Check if user is logged in and has appropriate role
if (!isset($_SESSION['user_id']) || ($_SESSION['user_role'] != 'content_manager' )) {
    echo "<script>alert('You must be logged in as an admin or instructor to access this page.'); window.location.href = 'login.html';</script>";
    exit();
}

// Fetch all materials for the dropdown
$sql = "SELECT material_id, title FROM materials";
$result = $con->query($sql);

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // ... (keep the existing form handling code) ...
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="logo1.png">
    <title>Edit Material</title>
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
            webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(242, 220, 145, 0.2);
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
            padding: 10px 200px;
            z-index: 1001;
            margin-top: 0px;
            margin-left: -20px;
            margin-right: -20px;
            padding-left: 20px;
            padding-right: 20px;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        label {
            color: #F2DC91;
            font-weight: bold;
        }

        input, select, textarea {
            width: 100%;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #F2DC91;
            background-color: rgba(2, 2, 2, 0.9);
            color: #fff;
        }

        input[type="file"] {
            padding: 5px;
        }

        input[type="submit"] {
            background-color: #F2DC91;
            color: #010326;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        input[type="submit"]:hover {
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

        .back-button {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }

        .back-button a {
            background-color: #F2DC91;
            color: #010326;
            text-decoration: none;
            padding: 10px 335px;
            border-radius: 5px;
            font-weight: bold;
            transition: background-color 0.3s ease;
            font-family: 'Bagel Fat One', sans-serif;
        }

        .back-button a:hover {
            background-color: #e0ca7f;
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
    <h1>Edit Material</h1>
    <div class="container">

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
            <label for="material_id">Select Material:</label>
            <select name="material_id" id="material_id" required>
                <option value="">Select a material</option>
                <?php
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<option value='" . $row["material_id"] . "'>" . $row["title"] . "</option>";
                    }
                }
                ?>
            </select>
            
            <label for="new_title">New Title:</label>
            <input type="text" id="new_title" name="new_title" required>
            
            <label for="new_description">New Description:</label>
            <textarea id="new_description" name="new_description" rows="4" required></textarea>
            
            <label for="new_media">New Media (Image, Video, or PDF):</label>
            <input type="file" id="new_media" name="new_media" accept="image/*,video/*,application/pdf">
            
            <input type="submit" value="Update Material">

            <div class="back-button">
            <a href="home.php">Home</a>
        </div>
        </form>
    </div>

    <script>
        const cursor = document.querySelector('.cursor');

        document.addEventListener('mousemove', e => {
            cursor.setAttribute("style", "top: "+(e.pageY - 10)+"px; left: "+(e.pageX - 10)+"px");
        });
    </script>


</body>
</html>