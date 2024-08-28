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

// Get level_id and user_id from session or POST
$level_id = isset($_POST['level_id']) ? intval($_POST['level_id']) : 0;
$user_id = isset($_SESSION['user_id']) ? intval($_SESSION['user_id']) : 0;

if ($level_id == 0 || $user_id == 0) {
    die("Invalid request.");
}

// Function to check and award level badge
function checkAndAwardAllLevelsBadge($user_id, $conn) {
    // Get total number of levels
    $totalLevelsSql = "SELECT COUNT(*) as total FROM level";
    $totalLevelsResult = $conn->query($totalLevelsSql);
    $totalLevels = $totalLevelsResult->fetch_assoc()['total'];

    // Get number of levels completed by the user
    $completedLevelsSql = "SELECT COUNT(DISTINCT level_id) as completed FROM user_levels WHERE user_id = ?";
    $completedLevelsStmt = $conn->prepare($completedLevelsSql);
    $completedLevelsStmt->bind_param("i", $user_id);
    $completedLevelsStmt->execute();
    $completedLevelsResult = $completedLevelsStmt->get_result();
    $completedLevels = $completedLevelsResult->fetch_assoc()['completed'];

    // Check if all levels are completed
    if ($completedLevels == $totalLevels) {
        // Define the badge ID for completing all levels
        $badge_id = 8; // Assuming badge_id 2 is for completing all levels

        // Check if the user already has this badge
        $sql = "SELECT * FROM user_badge WHERE user_id = ? AND badge_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $user_id, $badge_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows == 0) {
            // User doesn't have this badge, so award it
            $sql = "INSERT INTO user_badge (user_id, badge_id) VALUES (?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ii", $user_id, $badge_id);
            if ($stmt->execute()) {
                return "Congratulations! You've been awarded a new badge for completing all levels!";
            } else {
                return "Error awarding badge: " . $conn->error;
            }
        }
    }
    
    return null; // No new badge awarded
}

// Function to award badge for perfect score
function awardBadgeForPerfectScore($user_id, $level_id, $conn) {
    // Define the badge ID for perfect score
    $badge_id = 10; // Assuming badge_id 5 is for perfect score

    // Check if the user already has this badge
    $sql = "SELECT * FROM user_badge WHERE user_id = ? AND badge_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $user_id, $badge_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 0) {
        // User doesn't have this badge, so award it
        $sql = "INSERT INTO user_badge (user_id, badge_id) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $user_id, $badge_id);
        if ($stmt->execute()) {
            return "Congratulations! You've been awarded a new badge for achieving a perfect score!";
        } else {
            return "Error awarding badge: " . $conn->error;
        }
    }
    
    return null; // No new badge awarded
}

// Fetch level name
$levelNameSql = "SELECT level_name FROM level WHERE level_id = ?";
$levelNameStmt = $conn->prepare($levelNameSql);
$levelNameStmt->bind_param("i", $level_id);
$levelNameStmt->execute();
$levelNameResult = $levelNameStmt->get_result();
$levelName = $levelNameResult->fetch_assoc();
$levelNameStmt->close();

if (!$levelName) {
    die("Level not found.");
}

// Check if the user has already attempted this level
$userLevelSql = "SELECT * FROM user_levels WHERE user_id = ? AND level_id = ?";
$userLevelStmt = $conn->prepare($userLevelSql);
$userLevelStmt->bind_param("ii", $user_id, $level_id);
$userLevelStmt->execute();
$userLevelResult = $userLevelStmt->get_result();
$userLevel = $userLevelResult->fetch_assoc();
$userLevelStmt->close();

// Fetch questions and calculate correct answers
$questionsSql = "SELECT q.question_id, q.question_type, 
                 (SELECT COUNT(*) FROM options o 
                  WHERE o.question_id = q.question_id AND o.iscorrect = 1) AS correct_options_count
                 FROM question q
                 WHERE q.level_id = ?";
$questionsStmt = $conn->prepare($questionsSql);
$questionsStmt->bind_param("i", $level_id);
$questionsStmt->execute();
$questionsResult = $questionsStmt->get_result();
$questions = $questionsResult->fetch_all(MYSQLI_ASSOC);
$questionsStmt->close();

$correctAnswers = 0;

foreach ($questions as $question) {
    $question_id = $question['question_id'];
    if ($question['question_type'] == 'mcq') {
        $selectedOption = isset($_POST["q{$question_id}"]) ? intval($_POST["q{$question_id}"]) : 0;
        $optionsSql = "SELECT iscorrect FROM options WHERE option_id = ?";
        $optionsStmt = $conn->prepare($optionsSql);
        $optionsStmt->bind_param("i", $selectedOption);
        $optionsStmt->execute();
        $isCorrectResult = $optionsStmt->get_result();
        $isCorrect = $isCorrectResult->fetch_assoc();
        $optionsStmt->close();
        if ($isCorrect && $isCorrect['iscorrect'] == 1) {
            $correctAnswers++;
        }
    } elseif ($question['question_type'] == 'fill_blank') {
        $userAnswer = isset($_POST["q{$question_id}"]) ? $_POST["q{$question_id}"] : '';
        $answersSql = "SELECT answer_text FROM answer WHERE question_id = ?";
        $answersStmt = $conn->prepare($answersSql);
        $answersStmt->bind_param("i", $question_id);
        $answersStmt->execute();
        $answersResult = $answersStmt->get_result();
        $correctAnswersList = $answersResult->fetch_all(MYSQLI_ASSOC);
        $answersStmt->close();

        foreach ($correctAnswersList as $correctAnswer) {
            if (strcasecmp(trim($userAnswer), trim($correctAnswer['answer_text'])) == 0) {
                $correctAnswers++;
                break;
            }
        }
    }
}

$totalQuestions = count($questions);
$allCorrect = ($correctAnswers == $totalQuestions);

$message = "";
$badgeMessage = "";

if (!$userLevel) {
    // First attempt
    $expAllocated = $correctAnswers;
    $insertSql = "INSERT INTO user_levels (user_id, level_id, isdone, expAllocated, completion_time) VALUES (?, ?, 1, ?, NOW())";
    $insertStmt = $conn->prepare($insertSql);
    $insertStmt->bind_param("iii", $user_id, $level_id, $expAllocated);
    $insertStmt->execute();
    $insertStmt->close();

    $message = "Congratulations! You have completed the level. You earned {$expAllocated} EXP!";
    
    // Check and award badge for completing all levels
    $allLevelsBadgeMessage = checkAndAwardAllLevelsBadge($user_id, $conn);
    if ($allLevelsBadgeMessage) {
        $badgeMessage .= $allLevelsBadgeMessage;
    }
    
    // Check and award badge for perfect score
    if ($allCorrect) {
        $perfectScoreBadgeMessage = awardBadgeForPerfectScore($user_id, $level_id, $conn);
        if ($perfectScoreBadgeMessage) {
            $badgeMessage .= " " . $perfectScoreBadgeMessage;
        }
    }
} else {
    // Subsequent attempts
    $message = "Note: You have attempted the level before. No EXP will be given.";
    
    // Check and award badge for perfect score (even on subsequent attempts)
    if ($allCorrect) {
        $perfectScoreBadgeMessage = awardBadgeForPerfectScore($user_id, $level_id, $conn);
        if ($perfectScoreBadgeMessage) {
            $badgeMessage = $perfectScoreBadgeMessage;
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
    <link rel="icon" type="image/x-icon" href="logo1.png">
    <title>Level Completion - <?php echo htmlspecialchars($levelName['level_name']); ?></title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Bagel+Fat+One&family=Quicksand:wght@300..700&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Bagel Fat One', sans-serif;
            cursor: url('../images/icons8-cursor-100.png'), auto;
            z-index: 1001;
        }

        body {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: #010326;
            overflow-x: hidden;
            color: #fff;
            z-index: 1001;
        }

        h1 {
            text-align: center;
            color: #F2DC91;
            margin-bottom: 30px;
            font-family: 'Bagel Fat One', sans-serif;
            z-index: 1001;
        }

        p {
            margin-bottom: 20px;
            line-height: 1.6;
            z-index: 1001;
            font-family: 'Bagel Fat One', sans-serif;
        }

        .back-to-home-btn{
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
            z-index: 1001;
        }

        .back-to-home-btn:hover{
            background-color: #e0ca7f;
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
    </style>
</head>
<body>
    <section>
        <?php for ($i = 0; $i < 300; $i++): ?>
            <span></span>
        <?php endfor; ?>
    </section>
    <div class="container">

    <h1>Level Completion - <?php echo htmlspecialchars($levelName['level_name']); ?></h1>
    <p><?php echo $message; ?></p>
    <?php if ($badgeMessage): ?>
        <p><?php echo $badgeMessage; ?></p>
    <?php endif; ?>
    <p>Correct answers: <?php echo htmlspecialchars($correctAnswers); ?> out of <?php echo htmlspecialchars($totalQuestions); ?></p>
    <a href="player_dashboard.php" class="back-to-home-btn">Back to Homepage</a>
    </div>
</body>
</html>