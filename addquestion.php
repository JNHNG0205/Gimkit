<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', '/path/to/your/error.log'); // Replace with actual path

session_start();

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

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $levelId = $_SESSION['level_id'] ?? (int)($_GET['level_id'] ?? 0);
    $questionText = $_POST['question_text'] ?? '';
    $questionType = $_POST['question_type'] ?? '';

    // Check if the level_id is valid
    $levelCheckSql = "SELECT COUNT(*) FROM level WHERE level_id = ?";
    $levelCheckStmt = $conn->prepare($levelCheckSql);
    if ($levelCheckStmt === false) {
        $message = "Error preparing level check statement: " . $conn->error;
        error_log($message);
    } else {
        $levelCheckStmt->bind_param("i", $levelId);
        $levelCheckStmt->execute();
        $levelCheckStmt->bind_result($levelExists);
        $levelCheckStmt->fetch();
        $levelCheckStmt->close();

        if ($levelExists == 0) {
            $message = "Error: Invalid Level ID. Please ensure the level exists.";
            error_log($message);
        } else {
            // Attempt to insert the question
            $sql = "INSERT INTO question (question_text, level_id, question_type) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            if ($stmt === false) {
                $message = "Error preparing statement: " . $conn->error;
                error_log($message);
            } else {
                $stmt->bind_param("sis", $questionText, $levelId, $questionType);
                if ($stmt->execute()) {
                    $questionId = $conn->insert_id;
                    $message = "Question inserted successfully. ID: $questionId";
                    error_log($message);

                    // Handle MCQ options or fill-in-the-blank answer
                    if ($questionType === 'mcq') {
                        $optionsInserted = 0;
                        for ($i = 1; $i <= 4; $i++) {
                            $optionText = $_POST["option$i"] ?? '';
                            $isCorrect = (isset($_POST['correct_option']) && $_POST['correct_option'] == $i) ? 1 : 0;

                            if (!empty($optionText)) {
                                $optionSql = "INSERT INTO options (option_text, iscorrect, question_id) VALUES (?, ?, ?)";
                                $optionStmt = $conn->prepare($optionSql);
                                if ($optionStmt === false) {
                                    $message .= "\\nError preparing option statement: " . $conn->error;
                                    error_log("Error preparing option statement: " . $conn->error);
                                } else {
                                    $optionStmt->bind_param("sii", $optionText, $isCorrect, $questionId);
                                    if (!$optionStmt->execute()) {
                                        $message .= "\\nError inserting option: " . $optionStmt->error;
                                        error_log("Error inserting option: " . $optionStmt->error);
                                    } else {
                                        $optionsInserted++;
                                    }
                                    $optionStmt->close();
                                }
                            }
                        }
                        $message .= "\\nMCQ: $optionsInserted options inserted.";
                    } elseif ($questionType === 'fill_blank') {
                        $answer = $_POST['answer'] ?? '';

                        if (!empty($answer)) {
                            $answerSql = "INSERT INTO answer (answer_text, question_id) VALUES (?, ?)";
                            $answerStmt = $conn->prepare($answerSql);
                            if ($answerStmt === false) {
                                $message .= "\\nError preparing answer statement: " . $conn->error;
                                error_log("Error preparing answer statement: " . $conn->error);
                            } else {
                                $answerStmt->bind_param("si", $answer, $questionId);
                                if (!$answerStmt->execute()) {
                                    $message .= "\\nError inserting answer: " . $answerStmt->error;
                                    error_log("Error inserting answer: " . $answerStmt->error);
                                } else {
                                    $message .= "\\nFill-in-the-blank answer inserted successfully.";
                                }
                                $answerStmt->close();
                            }
                        } else {
                            $message .= "\\nError: Answer cannot be empty.";
                            error_log("Error: Answer is empty.");
                        }
                    }

                    // Handle media upload
                    if (isset($_FILES['media']) && $_FILES['media']['error'] == 0) {
                        $media_type = $_POST['media_type'] ?? '';
                        $upload_dir = 'uploads/'; // Make sure this directory exists and is writable
                        $file_name = basename($_FILES['media']['name']);
                        $upload_file = $upload_dir . $file_name;

                        // Move uploaded file
                        if (move_uploaded_file($_FILES['media']['tmp_name'], $upload_file)) {
                            $mediaSql = "INSERT INTO media (media_type, media_URL, question_id) VALUES (?, ?, ?)";
                            $mediaStmt = $conn->prepare($mediaSql);
                            if ($mediaStmt === false) {
                                $message .= "\\nError preparing media statement: " . $conn->error;
                                error_log("Error preparing media statement: " . $conn->error);
                            } else {
                                $mediaStmt->bind_param("ssi", $media_type, $file_name, $questionId);
                                if (!$mediaStmt->execute()) {
                                    $message .= "\\nError inserting media: " . $mediaStmt->error;
                                    error_log("Error inserting media: " . $mediaStmt->error);
                                } else {
                                    $message .= "\\nMedia uploaded and inserted successfully.";
                                }
                                $mediaStmt->close();
                            }
                        } else {
                            $message .= "\\nError uploading media file.";
                            error_log("Error uploading media file.");
                        }
                    }
                } else {
                    $message = "Error executing statement: " . $stmt->error;
                    error_log($message);
                }
                $stmt->close();
            }
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Question</title>
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
        }

        h1 {
            font-size: 50px;
            margin-bottom: 30px;
            color: gold;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.8);
            text-align: left;
        }

        h3 {
            margin-bottom: 30px;
            color: gold;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.8);
            text-align: left;
        }

        .container {
            position: relative;
            z-index: 10;
            width: 820px;
            padding: 20px;
            background: rgba(2, 2, 2, 0.5);
            border-radius: 8px;
        }

        label {
            color: #fff;
            font-size: 20px;
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
        }

        input[type="text"], textarea, select {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            background: rgba(2, 2, 2, 0.8);
            border: 1px solid #ff9d4d;
            color: #fff;
            border-radius: 4px;
            font-family: 'Bagel Fat One', sans-serif;
        }


        button, input[type="submit"] {
            background-color: #ff9d4d;
            color: white;
            padding: 10px 15px;
            border: none;
            cursor: pointer;
            transition: opacity 0.3s ease;
            margin-right: 10px;
            font-weight: bold;
            border-radius: 5px;
        }

        button:hover, input[type="submit"]:hover {
            opacity: 0.8;
        }

        section {
            position: fixed;
            width: 100vw;
            height: 500vh;
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
    <script>
        function toggleQuestionType() {
            var questionType = document.getElementById("question_type").value;
            document.getElementById("mcq_options").style.display = questionType === "mcq" ? "block" : "none";
            document.getElementById("fill_blank_answer").style.display = questionType === "fill_blank" ? "block" : "none";
        }

        <?php
        if (!empty($message)) {
            echo "alert(" . json_encode($message) . ");";
        }
        ?>

        function confirmRedirect() {
            if (confirm("Are you sure you want to finish? No further questions will be added to this level.")) {
                window.location.href = 'content_manager_dashboard.php';
            }
        }
    </script>
</head>
<body>
    <section>
        <?php for ($i = 0; $i < 200; $i++) {
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

        } ?>
    </section>
    <div class="container">
        <h1>Add New Question</h1>
        <form action="addquestion.php" method="post" enctype="multipart/form-data">
            <label for="question_text">Question Text:</label>
            <textarea id="question_text" name="question_text" required></textarea>
            
            <label for="question_type">Question Type:</label>
            <select id="question_type" name="question_type" onchange="toggleQuestionType()" required>
                <option value="">Select Type</option>
                <option value="mcq">Multiple Choice</option>
                <option value="fill_blank">Fill in the Blank</option>
            </select>
            
            <div id="mcq_options" style="display: none;">
                <h3>Multiple Choice Options</h3>
                <?php for ($i = 1; $i <= 4; $i++): ?>
                    <label for="option<?php echo $i; ?>">Option <?php echo $i; ?>:</label>
                    <input type="text" id="option<?php echo $i; ?>" name="option<?php echo $i; ?>">
                    <input type="radio" name="correct_option" value="<?php echo $i; ?>"> Correct
                <?php endfor; ?>
            </div>
            
            <div id="fill_blank_answer" style="display: none;">
                <label for="answer">Correct Answer:</label>
                <input type="text" id="answer" name="answer">
            </div>
            
            <h3>Optional Media Upload</h3>
            <label for="media_type">Media Type:</label>
            <select name="media_type" id="media_type">
                <option value="">Select Type</option>
                <option value="image">Image</option>
                <option value="video">Video</option>
                <option value="slides">Slides</option>
            </select>
            
            <label for="media">Upload Media:</label>
            <input type="file" name="media" id="media">
            <br>
            <br>
            
            <input type="submit" value="Add Question">
        </form>
        <br>

        <button onclick="confirmRedirect()">Finish Adding Questions</button>
        <button onclick="window.location.href='home.php'">Home</button>
    </div>
</body>
</html>