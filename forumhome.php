<?php
    session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forum Page</title>
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
        
        h3 {
            margin-bottom: 30px;
            color: gold;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.8);
            text-align: left;

        }

        p {
            margin-bottom: 30px;
            color: white;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.8);
            text-align: left;

        }

        h1 {
            font-size: 50px;
            margin-bottom: 30px;
            color: gold;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.8);
            text-align: left;

        }

        h4 {
            color: #fff;
        }

        .container {
            position: relative;
            z-index: 10;
            width: 820px;
            padding: 20px;
            background: rgba(2, 2, 2, 0.5);
            border-radius: 8px;
        }

        .box {
            background: rgba(2, 2, 2, 0.1);
            border: 1px solid #ff9d4d;
            margin-bottom: 20px;
            padding: 15px;
            border-radius: 8px;
        }

        .comments-section {
            display: none;
            margin-top: 10px;
        }

        .media {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            margin-top: 10px;
        }

        input[type="text"], textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid #ff9d4d;
            color: #fff;
            border-radius: 4px;
        }

        button, input[type="submit"] {
            background-color: #ff9d4d;
            color: white;
            padding: 10px 40px;
            border: none;
            cursor: pointer;
            transition: opacity 0.3s ease;
            margin-right: 10px;
            border-radius: 5px;
        }

        button:hover, input[type="submit"]:hover {
            opacity: 0.8;
        }

        section {
            position: fixed;
            width: 100vw;
            height: 900vh;
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
        function pressEnter(event) {
            if (event.key === "Enter") {
                event.preventDefault();
                document.getElementById("searchBtn").click();
            }
        }

        function toggleComments(postId) {
            var commentsSection = document.getElementById('comments-' + postId);
            if (commentsSection.style.display === 'none' || commentsSection.style.display === '') {
                commentsSection.style.display = 'block';
            } else {
                commentsSection.style.display = 'none';
            }
        }
    </script>
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
        <h1>Forum</h1>
        <form method="POST" action="">
            <input id="myInput" name="search_key" onkeypress="pressEnter(event)" placeholder="Search...">
            <button id="searchBtn" name="searchBtn" type="submit">Search</button>
        </form>
        <br>
        <button onclick="document.location='addforum.php'">Add Forum</button>
        <button onclick="document.location='home.php'">Home</button>
        <br><br>

        <?php
        include("conn.php");

        $search_key = '';

        if (isset($_POST['searchBtn']) && !empty($_POST['search_key'])) {
            $search_key = mysqli_real_escape_string($con, $_POST['search_key']);
            $sql = "
                SELECT discussion.*, user.user_name AS author_username 
                FROM discussion 
                JOIN user ON discussion.user_id = user.user_id 
                WHERE discussion.discussion_title LIKE '%$search_key%'
            ";
        } else {
            $sql = "
                SELECT discussion.*, user.user_name AS author_username 
                FROM discussion 
                JOIN user ON discussion.user_id = user.user_id
            ";
        }

        $result = mysqli_query($con, $sql);

        if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_array($result)) {
                $discussion_id = $row['discussion_id'];
                echo '<div class="box">';
                echo '<h3>' . $row['discussion_title'] . '</h3>';
                echo '<p>' . $row['discussion_text'] . '</p>';
                echo '<p>Created at: ' . $row['created_at'] . '</p>';
                echo '<p>Author: ' . $row['author_username'] . '</p>';

                // Fetch and display associated media
                $sql_media = "
                    SELECT media_type, media_URL 
                    FROM media 
                    WHERE discussion_id = $discussion_id
                ";
                $media_result = mysqli_query($con, $sql_media);

                if ($media_result && mysqli_num_rows($media_result) > 0) {
                    while ($media_row = mysqli_fetch_array($media_result)) {
                        $media_type = $media_row['media_type'];
                        $media_URL = $media_row['media_URL'];

                        if ($media_type === 'image') {
                            echo '<img src="' . htmlspecialchars($media_URL) . '" class="media" alt="Image">';
                        } else {
                            echo '<p>Unsupported media type: ' . htmlspecialchars($media_type) . '</p>';
                        }
                    }
                }
                echo '<br>';
                echo '<button onclick="toggleComments(' . $discussion_id . ')">Comments</button>';
                echo '<br>';

                echo '<div id="comments-' . $discussion_id . '" class="comments-section">';

                $comment_sql = "
                    SELECT comment.*, user.user_name AS commenter_username 
                    FROM comment 
                    JOIN user ON comment.user_id = user.user_id 
                    WHERE comment.discussion_id = $discussion_id 
                    ORDER BY comment.created_at DESC
                ";
                $comment_result = mysqli_query($con, $comment_sql);

                if ($comment_result && mysqli_num_rows($comment_result) > 0) {
                    echo '<h4>Comments:</h4>';
                    while ($comment_row = mysqli_fetch_array($comment_result)) {
                        echo '<p>' . $comment_row['commenter_username'] . ': ' . $comment_row['comment_text'] . ' - ' . $comment_row['created_at'] . '</p>';

                        // Fetch and display comment media
                        $sql_comment_media = "
                            SELECT media_type, media_URL 
                            FROM media 
                            WHERE comment_id = " . intval($comment_row['comment_id']) . "
                        ";
                        $comment_media_result = mysqli_query($con, $sql_comment_media);

                        if ($comment_media_result && mysqli_num_rows($comment_media_result) > 0) {
                            while ($comment_media_row = mysqli_fetch_array($comment_media_result)) {
                                $comment_media_type = $comment_media_row['media_type'];
                                $comment_media_URL = $comment_media_row['media_URL'];

                                if ($comment_media_type === 'image') {
                                    echo '<img src="' . htmlspecialchars($comment_media_URL) . '" class="media" alt="Comment Image">';
                                } else {
                                    echo '<p>Unsupported media type: ' . htmlspecialchars($comment_media_type) . '</p>';
                                }
                            }
                        }
                    }
                    echo '<br>';
                } else {
                    echo '<p>No comments yet.</p>';
                }
                

                if (isset($_SESSION['user_id'])) {
                    echo '<form method="post" action="comment.php" enctype="multipart/form-data">';
                    echo '<input type="hidden" name="discussion_id" value="' . $discussion_id . '">';
                    echo '<textarea name="comment_text" required placeholder="Add a comment..."></textarea>';
                    echo '<br>';
                    echo '<input type="file" name="fileToUpload" accept="image/*">';
                    echo '<br>';
                    echo '<br>';
                    echo '<input type="submit" value="Add Comment">';
                    echo '</form>';
                } else {
                    echo '<p>Please <a href="login.html">login</a> to add a comment.</p>';
                }

                echo '</div>'; 
                echo '</div>'; 
            }
        } else {
            echo 'No results found.';
        }

        mysqli_close($con);
        ?>
    </div>
</body>
</html>