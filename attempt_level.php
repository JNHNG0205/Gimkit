<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

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

// Get level_id from GET parameter or session
$level_id = isset($_GET['level_id']) ? intval($_GET['level_id']) : ($_SESSION['level_id'] ?? 0);

if ($level_id == 0) {
    die("No level specified.");
}

// Fetch level information
$levelSql = "SELECT * FROM level WHERE level_id = ?";
$levelStmt = $conn->prepare($levelSql);
$levelStmt->bind_param("i", $level_id);
$levelStmt->execute();
$levelResult = $levelStmt->get_result();
$level = $levelResult->fetch_assoc();
$levelStmt->close();

if (!$level) {
    die("Level not found.");
}

// Fetch questions for this level
$questionsSql = "SELECT q.*, m.media_type, m.media_URL 
                 FROM question q 
                 LEFT JOIN media m ON q.question_id = m.question_id 
                 WHERE q.level_id = ?";
$questionsStmt = $conn->prepare($questionsSql);
$questionsStmt->bind_param("i", $level_id);
$questionsStmt->execute();
$questionsResult = $questionsStmt->get_result();
$questions = $questionsResult->fetch_all(MYSQLI_ASSOC);
$questionsStmt->close();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attempt Level: <?php echo htmlspecialchars($level['level_name']); ?></title>
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
            color: #222;
        }

        .container {
            position: relative;
            z-index: 10;
            width: 80%;
            max-width: 800px;
            padding: 40px;
            background-color: rgba(255, 255, 255, 0.6);
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
        }

        h1 {
            font-size: 36px;
            margin-bottom: 20px;
            color: gold;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
            text-align: center;
        }

        .question {
            background-color: rgba(255, 255, 255, .6);
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 8px;
        }

        .media {
            max-width: 100%;
            height: auto;
            margin-bottom: 10px;
        }

        input[type="radio"], input[type="text"] {
            margin-bottom: 10px;
        }

        label {
            font-size: 18px;
            margin-bottom: 10px;
            display: inline-block;
        }

        input[type="submit"] {
            background-color: #F2A765;
            color: white;
            padding: 10px 290px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 18px;
            transition: opacity 0.3s ease;
            display: block;
            margin: 20px auto 0;
        }

        input[type="submit"]:hover {
            opacity: 0.8;
        }

        section {
            position: fixed;
            width: 100vw;
            height: 300vh;
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
            height: 100%;
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
    </style>
</head>
<body>
    <section></section>
    <div class="container">
        <h1>Attempt Level: <?php echo htmlspecialchars($level['level_name']); ?></h1>
        <form action="resultpage.php" method="post">
        <audio autoplay loop>
            <source src="darkshadow-cyberpunk-206176.mp3" type="audio/mp3">
        </audio>
            <input type="hidden" name="level_id" value="<?php echo $level_id; ?>">
            <?php foreach ($questions as $index => $question): ?>
                <div class="question">
                    <h2>Question <?php echo $index + 1; ?></h2>
                    <br>
                    <p><?php echo htmlspecialchars($question['question_text']); ?></p>
                    
                    <?php if (!empty($question['media_URL'])): ?>
                        <?php
                        $mediaPath = 'uploads/' . htmlspecialchars($question['media_URL']);
                        switch ($question['media_type']) {
                            case 'image':
                                echo "<img class='media' src='{$mediaPath}' alt='Question Image'>";
                                break;
                            case 'video':
                                echo "<video class='media' controls>
                                        <source src='{$mediaPath}' type='video/mp4'>
                                        Your browser does not support the video tag.
                                      </video>";
                                break;
                            case 'slides':
                                echo "<a href='{$mediaPath}' target='_blank'>View Slides</a>";
                                break;
                            default:
                                echo "<p>Unsupported media type</p>";
                        }
                        ?>
                    <?php endif; ?>
                    
                    <?php if ($question['question_type'] == 'mcq'): ?>
                        <?php
                        $optionsSql = "SELECT * FROM options WHERE question_id = ?";
                        $optionsStmt = $conn->prepare($optionsSql);
                        $optionsStmt->bind_param("i", $question['question_id']);
                        $optionsStmt->execute();
                        $optionsResult = $optionsStmt->get_result();
                        $options = $optionsResult->fetch_all(MYSQLI_ASSOC);
                        $optionsStmt->close();
                        ?>
                        <br>
                        <?php foreach ($options as $option): ?>
                            <div>
                                <input type="radio" id="q<?php echo $question['question_id']; ?>_<?php echo $option['option_id']; ?>" 
                                       name="q<?php echo $question['question_id']; ?>" 
                                       value="<?php echo $option['option_id']; ?>">
                                <label for="q<?php echo $question['question_id']; ?>_<?php echo $option['option_id']; ?>">
                                    <?php echo htmlspecialchars($option['option_text']); ?>
                                </label>
                            </div>
                        <?php endforeach; ?>
                    <?php elseif ($question['question_type'] == 'fill_blank'): ?>
                        <br>
                        <input type="text" name="q<?php echo $question['question_id']; ?>" placeholder="Your answer">
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>

            <input type="submit" value="Submit Answers">
        </form>
    </div>
</body>
</html>

<?php
$conn->close();
?>
