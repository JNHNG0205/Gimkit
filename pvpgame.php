<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gimkit";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION['user_id'] ?? 0;
if ($user_id == 0) {
    die("User not logged in.");
}

$session_id = $_GET['session_id'] ?? $_SESSION['session_id'] ?? 0;
if ($session_id == 0) {
    die("Invalid session.");
}

$_SESSION['session_id'] = $session_id;

$questionsSql = "SELECT q.*, m.media_type, m.media_URL 
                 FROM question q 
                 LEFT JOIN media m ON q.question_id = m.question_id 
                 WHERE q.question_type = 'mcq'
                 ORDER BY RAND() 
                 LIMIT 10";
$questionsResult = $conn->query($questionsSql);

if ($questionsResult->num_rows == 0) {
    die("No MCQ questions found.");
}

$questions = $questionsResult->fetch_all(MYSQLI_ASSOC);

$_SESSION['start_time'] = time();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PvP Quiz Game</title>
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

        input[type="radio"] {
            margin-right: 10px;
        }

        label {
            font-size: 18px;
            margin-bottom: 10px;
            display: inline-block;
        }

        input[type="submit"] {
            background-color: #F2A765;
            color: white;
            padding: 10px 285px;
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

        #timer {
  font-size: 24px;
  margin-right: 100px;
  text-align: right;
  color: #F2A765;
  position: fixed; /* Change to fixed positioning */
  bottom: 300px;
  right: 10px; /* Adjust right position as needed */
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
        .student{
            position:fixed; 
            z-index:1001; 
            width:300px; 
            bottom:0px; 
            right:80px;
        }
    </style>
    <script>
        let startTime = <?php echo $_SESSION['start_time']; ?>;
        function updateTimer() {
            let now = Math.floor(Date.now() / 1000);
            let elapsed = now - startTime;
            let minutes = Math.floor(elapsed / 60);
            let seconds = elapsed % 60;
            if (seconds < 10) seconds = '0' + seconds;
            document.getElementById('timer').innerText = `Time Elapsed: ${minutes}:${seconds}`;
        }
        setInterval(updateTimer, 1000);

        
    </script>
</head>
<body>
<img class="student" src="Untitled_Artwork 3.gif">
    <div class="container">
        <h1>PvP Quiz Game</h1>
        <audio autoplay loop>
            <source src="darkshadow-cyberpunk-206176.mp3" type="audio/mp3">
        </audio>
        <div id="timer">Time Elapsed: 0:00</div>
        <form action="postpvp.php" method="post">
            <input type="hidden" name="session_id" value="<?php echo $session_id; ?>">
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
                                echo "<video class='media' controls><source src='{$mediaPath}' type='video/mp4'>Your browser does not support the video tag.</video>";
                                break;
                            case 'slides':
                                echo "<a href='{$mediaPath}' target='_blank'>View Slides</a>";
                                break;
                            default:
                                echo "<p>Unsupported media type</p>";
                        }
                        ?>
                    <?php endif; ?>
                    <br>

                    <?php
                    $optionsSql = "SELECT * FROM options WHERE question_id = ?";
                    $optionsStmt = $conn->prepare($optionsSql);
                    $optionsStmt->bind_param("i", $question['question_id']);
                    $optionsStmt->execute();
                    $optionsResult = $optionsStmt->get_result();
                    $options = $optionsResult->fetch_all(MYSQLI_ASSOC);
                    $optionsStmt->close();
                    ?>
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