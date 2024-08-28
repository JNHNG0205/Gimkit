<?php
// Initialize session
session_start();

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

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

// Fetch level ID from the URL
$levelId = $_GET['level_id'] ?? 0;

if (!$levelId) {
    echo "No Level ID provided. Please go back and select a level.";
    exit();
}

// Fetch level name
$levelNameSql = "SELECT level_name FROM level WHERE level_id = ?";
$stmt = $conn->prepare($levelNameSql);
$stmt->bind_param("i", $levelId);
$stmt->execute();
$result = $stmt->get_result();
$level = $result->fetch_assoc();

if ($level) {
    $levelName = $level['level_name'];
} else {
    echo "Invalid Level ID.";
    exit();
}

// Update logic if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($_POST['question_ids'] as $index => $questionId) {
        $questionText = $_POST['question_texts'][$index];

        // Update question text
        $updateQuestionSql = "UPDATE question SET question_text = ? WHERE question_id = ?";
        $stmt = $conn->prepare($updateQuestionSql);
        $stmt->bind_param("si", $questionText, $questionId);
        $stmt->execute();

        // Handle multiple choice questions
        if (isset($_POST['options'][$questionId])) {
            foreach ($_POST['options'][$questionId] as $optionId => $optionText) {
                $isCorrect = ($_POST['correct_option'][$questionId] == $optionId) ? 1 : 0;
                $updateOptionSql = "UPDATE options SET option_text = ?, iscorrect = ? WHERE option_id = ?";
                $stmt = $conn->prepare($updateOptionSql);
                $stmt->bind_param("sii", $optionText, $isCorrect, $optionId);
                $stmt->execute();
            }
        }

        // Handle fill-in-the-blank questions
        if (isset($_POST['answers'][$questionId])) {
            $answerText = $_POST['answers'][$questionId];
            $updateAnswerSql = "UPDATE answer SET answer_text = ? WHERE question_id = ?";
            $stmt = $conn->prepare($updateAnswerSql);
            $stmt->bind_param("si", $answerText, $questionId);
            $stmt->execute();
        }

        // Handle media upload
        if (isset($_FILES['media']['name'][$questionId]) && $_FILES['media']['error'][$questionId] == 0) {
            $target_dir = __DIR__ . '/uploads/';
            $file_extension = pathinfo($_FILES['media']['name'][$questionId], PATHINFO_EXTENSION);
            $new_filename = 'question_' . $questionId . '_' . time() . '.' . $file_extension;
            $target_file = $target_dir . $new_filename;

            // Determine media type
            $media_type = '';
            if (in_array($file_extension, ['jpg', 'jpeg', 'png', 'gif'])) {
                $media_type = 'image';
            } elseif (in_array($file_extension, ['mp4', 'avi', 'mov', 'wmv'])) {
                $media_type = 'video';
            } elseif ($file_extension == 'pdf') {
                $media_type = 'slides';
            }

            if ($media_type && move_uploaded_file($_FILES['media']['tmp_name'][$questionId], $target_file)) {
                // Delete old media file
                $oldMediaSql = "SELECT media_URL FROM media WHERE question_id = ?";
                $stmt = $conn->prepare($oldMediaSql);
                $stmt->bind_param("i", $questionId);
                $stmt->execute();
                $result = $stmt->get_result();
                if ($oldMedia = $result->fetch_assoc()) {
                    $oldFilePath = $target_dir . $oldMedia['media_URL'];
                    if (file_exists($oldFilePath)) {
                        unlink($oldFilePath);
                    }
                }

                // Delete old media entry from database
                $deleteOldMediaSql = "DELETE FROM media WHERE question_id = ?";
                $stmt = $conn->prepare($deleteOldMediaSql);
                $stmt->bind_param("i", $questionId);
                $stmt->execute();

                // Insert new media
                $insertMediaSql = "INSERT INTO media (media_type, media_URL, question_id) VALUES (?, ?, ?)";
                $stmt = $conn->prepare($insertMediaSql);
                $stmt->bind_param("ssi", $media_type, $new_filename, $questionId);
                $stmt->execute();
            }
        }
    }

    // Redirect to the same page to show updated questions
    header("Location: editquestion.php?level_id=" . $levelId);
    exit();
}

// Fetch questions and their respective options/answers
$sql = "SELECT q.*, a.answer_text, m.media_type, m.media_URL 
        FROM question q 
        LEFT JOIN answer a ON q.question_id = a.question_id 
        LEFT JOIN media m ON q.question_id = m.question_id
        WHERE q.level_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $levelId);
$stmt->execute();
$result = $stmt->get_result();

$questions = [];

while ($row = $result->fetch_assoc()) {
    $questionId = $row['question_id'];

    // Fetch options for MCQ
    if ($row['question_type'] == 'mcq') {
        $optionSql = "SELECT * FROM options WHERE question_id = ?";
        $optionStmt = $conn->prepare($optionSql);
        $optionStmt->bind_param("i", $questionId);
        $optionStmt->execute();
        $optionResult = $optionStmt->get_result();

        $options = [];
        while ($optionRow = $optionResult->fetch_assoc()) {
            $options[] = $optionRow;
        }

        $row['options'] = $options;
    }

    $questions[] = $row;
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Questions</title>
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
            flex-direction: column;
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

        .question {
            border: 1px solid #ccc;
            padding: 15px;
            margin-bottom: 20px;
            background: rgba(2, 2, 2, 0.1);
            border-radius: 8px;
            width: 100%;
        }

        .media-preview {
            max-width: 300px;
            max-height: 200px;
            margin-top: 10px;
        }

        button {
            background-color: #ff9d4d;
            color: white;
            padding: 10px 324px;
            border: none;
            cursor: pointer;
            transition: opacity 0.3s ease;
            margin-right: 10px; /* Add some spacing between buttons */
        }

        button:hover {
            opacity: 0.8;
        }

        .container {
            position: relative;
            z-index: 10;
            width: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        section {
            position: absolute;
            width: 100vw;
            height: 700vh;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 2px;
            flex-wrap: wrap;
            overflow: hidden;
            z-index: 1;
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

        .home-button {
            position: absolute;
            top: 20px;
            right: 20px;
            z-index: 1002;
            padding: 10px 20px;
            background-color: #ff9d4d;
            color: white;
            text-decoration: none;
            font-family: 'Bagel Fat One', sans-serif;
            font-weight: bold;
            border-radius: 5px;
        }
        form {
            width: 700px;
            background: rgba(2, 2, 2, 0.5);
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
        <button class="home-button" onclick="window.location.href='home.php'">Home</button>
        <h1>Edit Questions for Level: <?php echo htmlspecialchars($levelName); ?></h1>
        <form action="editquestion.php?level_id=<?php echo $levelId; ?>" method="post" enctype="multipart/form-data">
            <?php foreach ($questions as $question) : ?>
                <div class="question">
                    <h3>Question ID: <?php echo $question['question_id']; ?></h3>
                    <br>
                    <label>Question: <label>
                        <br>
                    <textarea name="question_texts[]" cols="60" rows="3"><?php echo htmlspecialchars($question['question_text']); ?></textarea>
                    <input type="hidden" name="question_ids[]" value="<?php echo $question['question_id']; ?>">



                    <?php if ($question['question_type'] == 'mcq' && !empty($question['options'])) : ?>
                        <?php foreach ($question['options'] as $option) : ?>
                            <div>
                            <br>
                                <input type="radio" name="correct_option[<?php echo $question['question_id']; ?>]" value="<?php echo $option['option_id']; ?>" <?php echo ($option['iscorrect']) ? 'checked' : ''; ?>>
                                <input type="text" name="options[<?php echo $question['question_id']; ?>][<?php echo $option['option_id']; ?>]" value="<?php echo htmlspecialchars($option['option_text']); ?>">
                            </div>
                        <?php endforeach; ?>
                        <br>

                    <?php elseif ($question['question_type'] == 'fill_blank') : ?>
                        <div>
                            <br>
                            Answer: <br>
                            <input type="text" name="answers[<?php echo $question['question_id']; ?>]" value="<?php echo htmlspecialchars($question['answer_text']); ?>">
                        </div>
                    <?php endif; ?>
                    <br>

                    <!-- Media display -->
                    <?php if ($question['media_type']) : ?>
                        <div>
                            <strong>Current Media:</strong>
                            <?php if ($question['media_type'] == 'image') : ?>
                                <img src="uploads/<?php echo $question['media_URL']; ?>" class="media-preview">
                            <?php elseif ($question['media_type'] == 'video') : ?>
                                <video controls class="media-preview">
                                    <source src="uploads/<?php echo $question['media_URL']; ?>">
                                </video>
                            <?php elseif ($question['media_type'] == 'slides') : ?>
                                <embed src="uploads/<?php echo $question['media_URL']; ?>" class="media-preview">
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                    <!-- Media upload -->
                    <div>
                        <label for="media[<?php echo $question['question_id']; ?>]">Upload New Media:</label>
                        <br>
                        <input type="file" name="media[<?php echo $question['question_id']; ?>]">
                    </div>
                </div>
            <?php endforeach; ?>
            <button type="submit">Save Changes</button>
            
        </form>
    </div>
</body>
</html>

