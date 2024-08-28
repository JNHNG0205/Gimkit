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

// Function to safely get POST data
function getPostValue($key) {
    return isset($_POST[$key]) ? htmlspecialchars($_POST[$key]) : '';
}

$message = "";

// Fetch all material titles
$fetch_sql = "SELECT material_id, title FROM materials ORDER BY title";
$result = $conn->query($fetch_sql);
$materials = $result->fetch_all(MYSQLI_ASSOC);

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $material_id = getPostValue('material_id');
    
    if (!empty($material_id)) {
        // Start transaction
        $conn->begin_transaction();

        try {
            // Fetch associated media files
            $media_sql = "SELECT media_URL FROM media WHERE material_id = ?";
            $media_stmt = $conn->prepare($media_sql);
            $media_stmt->bind_param("i", $material_id);
            $media_stmt->execute();
            $media_result = $media_stmt->get_result();
            $media_files = $media_result->fetch_all(MYSQLI_ASSOC);

            // Delete media files from server
            foreach ($media_files as $media) {
                $file_path = __DIR__ . '/' . $media['media_URL'];
                if (file_exists($file_path)) {
                    unlink($file_path);
                }
            }

            // Delete media entries from database
            $delete_media_sql = "DELETE FROM media WHERE material_id = ?";
            $delete_media_stmt = $conn->prepare($delete_media_sql);
            $delete_media_stmt->bind_param("i", $material_id);
            $delete_media_stmt->execute();

            // Delete the material
            $delete_material_sql = "DELETE FROM materials WHERE material_id = ?";
            $delete_material_stmt = $conn->prepare($delete_material_sql);
            $delete_material_stmt->bind_param("i", $material_id);
            $delete_material_stmt->execute();

            // Commit transaction
            $conn->commit();

            $message = "Material and associated media deleted successfully.";

            // Refresh the materials list after deletion
            $result = $conn->query($fetch_sql);
            $materials = $result->fetch_all(MYSQLI_ASSOC);

        } catch (Exception $e) {
            // Rollback transaction on error
            $conn->rollback();
            $message = "Error deleting material and media: " . $e->getMessage();
        }

        // Close statements
        if (isset($media_stmt)) $media_stmt->close();
        if (isset($delete_media_stmt)) $delete_media_stmt->close();
        if (isset($delete_material_stmt)) $delete_material_stmt->close();

    } else {
        $message = "Please select a material to delete.";
    }
}

// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Material</title>
    <link rel="icon" type="image/x-icon" href="logo1.png">
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
            margin: 0;
            font-family: 'Bagel Fat One', sans-serif;
        }

        .container {
            background-color: rgba(1, 3, 38, 0.95);
            border-radius: 10px;
            box-shadow: 0 15px 50px rgba(0, 0, 0, 0.1);
            overflow-y: auto;
            width: 70%;  
            padding: 40px;
            max-height: 80vh;
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(242, 220, 145, 0.2);
            margin: 0 auto; 
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
            position: sticky;
            top: 0;
            background-color: rgba(1, 3, 38, 0.95);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            padding: 20px 0;
            z-index: 1;
            margin-top: -20px;
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
            font-family: 'Bagel Fat One', sans-serif;
        }

        input, select, textarea {
            width: 100%;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #F2DC91;
            background-color: rgba(2, 2, 2, 0.8);
            color: #F2DC91;
            font-family: 'Bagel Fat One', sans-serif;
        }

        input[type="submit"] {
            background-color: #F2DC91;
            color: #010326;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s ease;
            font-family: 'Bagel Fat One', sans-serif;
        }

        input[type="submit"]:hover {
            background-color: #e0ca7f;
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
            padding: 10px 480px;
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
            color: #fff;
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
        <h1>Delete Material</h1>
        <?php
        if (!empty($message)) {
            echo "<p>$message</p>";
        }
        ?>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <label for="material_id">Select Material to Delete:</label>
            <select id="material_id" name="material_id" required>
                <option value="">Select a material</option>
                <?php foreach ($materials as $material): ?>
                    <option value="<?php echo $material['material_id']; ?>">
                        <?php echo htmlspecialchars($material['title']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <input type="submit" value="Delete Material">
        </form>
        
        <div class="back-button">
            <a href="content_manager_dashboard.php">Back</a>
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