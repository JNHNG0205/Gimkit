<?php
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

function getQuestionsByLevel($conn, $levelId) {
    $questions = [];

    $sql = "SELECT q.question_id, q.question_text 
            FROM question q
            WHERE q.level_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $levelId);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $question = [
            'id' => $row['question_id'],
            'text' => $row['question_text']
        ];

        // Check the question type based on the presence of options or answer
        $sql = "SELECT o.option_text, o.iscorrect 
                FROM options o
                WHERE o.question_id = ?";
        $optionStmt = $conn->prepare($sql);
        $optionStmt->bind_param("i", $row['question_id']);
        $optionStmt->execute();
        $optionResult = $optionStmt->get_result();

        if ($optionResult->num_rows > 0) {
            $question['type'] = 'mcq';
            $options = [];
            while ($optionRow = $optionResult->fetch_assoc()) {
                $options[] = [
                    'text' => $optionRow['option_text'],
                    'is_correct' => (bool)$optionRow['iscorrect']
                ];
            }
            $question['options'] = $options;
        } else {
            $sql = "SELECT answer_text 
                    FROM answer
                    WHERE question_id = ?";
            $answerStmt = $conn->prepare($sql);
            $answerStmt->bind_param("i", $row['question_id']);
            $answerStmt->execute();
            $answerResult = $answerStmt->get_result();
            if ($answerResult->num_rows > 0) {
                $question['type'] = 'fill_blank';
                $answerRow = $answerResult->fetch_assoc();
                $question['answer'] = $answerRow['answer_text'];
            } else {
                $question['type'] = 'unknown';
            }
            $answerStmt->close();
        }

        $optionStmt->close();
        $questions[] = $question;
    }

    $stmt->close();
    return $questions;
}

function deleteQuestion($conn, $questionId) {
    try {
        // Delete options associated with the question
        $sql = "DELETE FROM options WHERE question_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $questionId);
        $stmt->execute();
        $stmt->close();

        // Delete answers associated with the question
        $sql = "DELETE FROM answer WHERE question_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $questionId);
        $stmt->execute();
        $stmt->close();

        // Delete the question itself
        $sql = "DELETE FROM question WHERE question_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $questionId);
        $stmt->execute();
        $stmt->close();
    } catch (Exception $e) {
        throw new Exception("Error deleting question: " . $e->getMessage());
    }
}

function deleteLevel($conn, $levelId) {
    $conn->begin_transaction();

    try {
        // Debugging output
        echo "Attempting to delete level with ID: $levelId<br>";

        // Get all questions for the level
        $questions = getQuestionsByLevel($conn, $levelId);
        echo "Number of questions found: " . count($questions) . "<br>";

        // Delete all questions for the level
        foreach ($questions as $question) {
            echo "Deleting question ID: " . $question['id'] . "<br>";
            deleteQuestion($conn, $question['id']);
        }

        // Delete the level
        $sql = "DELETE FROM level WHERE level_id = ?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            echo "Error preparing statement: " . $conn->error . "<br>";
            throw new Exception("Prepare failed: " . $conn->error);
        }
        $stmt->bind_param("i", $levelId);
        if (!$stmt->execute()) {
            echo "Error executing statement: " . $stmt->error . "<br>";
            throw new Exception("Execution failed: " . $stmt->error);
        }
        $stmt->close();

        echo "Level deleted successfully.<br>";
        $conn->commit();
        return true;
    } catch (Exception $e) {
        echo "Transaction failed: " . $e->getMessage() . "<br>";
        $conn->rollback();
        return "Error: " . $e->getMessage();
    }
}

function editLevel($conn, $levelId, $newName) {
    $sql = "UPDATE level SET level_name = ? WHERE level_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $newName, $levelId);
    
    if ($stmt->execute()) {
        $stmt->close();
        return true;
    } else {
        $stmt->close();
        return "Error: " . $conn->error;
    }
}

// Handle edit request
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_level_id'])) {
    $levelId = intval($_POST['edit_level_id']); // Sanitize input
    $newName = $_POST['new_level_name'];
    $result = editLevel($conn, $levelId, $newName);
    if ($result === true) {
        echo "<script>alert('Level name updated successfully!'); window.location.href='managelevels.php';</script>";
    } else {
        echo "<script>alert('Error: " . $result . "');</script>";
    }
}

// Handle delete request
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['delete_level'])) {
    $levelId = intval($_GET['delete_level']); // Sanitize input
    $result = deleteLevel($conn, $levelId);
    if ($result === true) {
        echo "<script>alert('Level deleted successfully!'); window.location.href='managelevels.php';</script>";
    } else {
        echo "<script>alert('Error: " . $result . "');</script>";
    }
}

// Fetch levels and questions
$levels = [];
$result = $conn->query("SELECT * FROM level");
while ($row = $result->fetch_assoc()) {
    $levelId = $row['level_id'];
    $levelName = $row['level_name'];
    $questions = getQuestionsByLevel($conn, $levelId);
    $levels[] = ['id' => $levelId, 'name' => $levelName, 'questions' => $questions];
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Levels</title>
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

    h2 {
        font-size: 50px;
        margin-bottom: 30px;
        color: gold;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.8);
        text-align: left;
    }

    table {
        border-collapse: collapse;
        width: 100%;
        background: rgba(2, 2, 2, 0.1);
        border-radius: 8px;
        overflow: hidden;
    }

    th, td {
        text-align: left;
        padding: 12px;
        border-bottom: 1px solid #ddd;
    }

    tr:nth-child(even) {
        background-color: rgba(255, 255, 255, 0.1);
    }

    th {
        background-color: #ff9d4d;
        color: #222;
    }

    button {
        background-color: #ff9d4d;
        color: white;
        padding: 10px 15px;
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
    }

    .button-container {
        margin-top: 20px;
        text-align: center;
    }

    section {
        position: absolute;
        width: 100vw;
        height: 150vh;
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
</style>
</head>
<body>
    <div class="container">
        <h2>Manage Levels</h2>
        <table>
        <tr>
            <th>Level Name</th>
            <th>Questions</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($levels as $level): ?>
        <tr>
            <td><?php echo htmlspecialchars($level['name']); ?></td>
            <td><?php echo count($level['questions']); ?></td>
            <td>
                <a href="addquestion.php?level_id=<?php echo $level['id']; ?>">
                    <button>Add Question</button>
                </a>
                <a href="#" onclick="confirmDeleteLevel(<?php echo $level['id']; ?>)">
                    <button>Delete</button>
                </a>
                <a href="editquestion.php?level_id=<?php echo $level['id']; ?>">
                    <button>Edit Questions</button>
                </a>
                <a href="#" onclick="editLevelPrompt('<?php echo $level['id']; ?>', '<?php echo htmlspecialchars($level['name']); ?>')">
                    <button>Edit Level Name</button>
                </a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>

    <div class="button-container">
        <a href="addlevel.php"><button>Add New Level</button></a>
        <a href="home.php"><button>Home</button></a>
    </div>
</div>

<section>
    <?php for ($i = 0; $i < 1000; $i++): ?>
    <span></span>
    <?php endfor; ?>
</section>

<script>
    function confirmDeleteLevel(levelId) {
        const url = "managelevels.php?delete_level=" + levelId;
        console.log(url); // Check the URL in the browser's console
        if (confirm("Are you sure you want to delete this level and all its questions?")) {
            window.location.href = url;
        }
    }

    function editLevelPrompt(levelId, currentName) {
        const newName = prompt("Enter the new name for the level:", currentName);
        if (newName !== null && newName.trim() !== "") {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = 'managelevels.php';

            const levelIdField = document.createElement('input');
            levelIdField.type = 'hidden';
            levelIdField.name = 'edit_level_id';
            levelIdField.value = levelId;

            const newNameField = document.createElement('input');
            newNameField.type = 'hidden';
            newNameField.name = 'new_level_name';
            newNameField.value = newName;

            form.appendChild(levelIdField);
            form.appendChild(newNameField);

            document.body.appendChild(form);
            form.submit();
        }
    }
</script>
</body>
</html>