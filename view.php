<?php
include("session.php");
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css\internal_style.css">
    <title>View Post</title>
    <script>
        function toggleComments(postId) {
            var commentsSection = document.getElementById('comments-' + postId);
            var button = document.getElementById('toggle-comments-' + postId);

            if (commentsSection.style.display === 'none' || commentsSection.style.display === '') {
                commentsSection.style.display = 'block';
                button.textContent = 'Comments';
            } else {
                commentsSection.style.display = 'none';
                button.textContent = 'Comments';
            }
        }

        function toggleCommentForm(postId) {
            var commentForm = document.getElementById('comment-form-' + postId);
            var button = document.getElementById('toggle-comment-form-' + postId);

            if (commentForm.style.display === 'none' || commentForm.style.display === '') {
                commentForm.style.display = 'block';
                button.textContent = 'Comment';
            } else {
                commentForm.style.display = 'none';
                button.textContent = 'Add Comment';
            }
        }
    </script>
    <style>
        .box {
            background-color: rgba(0, 0, 0, 0.65);
            border-radius: 10px;
            box-shadow: 0 15px 50px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            width: 750px;
            height: auto;
            max-width: 90%;
            max-height: 80%;
            z-index: 10;
            padding: 40px;
            position: absolute;
            padding-bottom: 40px;
            top: 100px;
            overflow-y: auto;
        }
        .comments-section, .comment-form {
            margin-top: 10px;
            z-index: 1001;
        }
        .comments-section {
            display: none;
            z-index: 1001;
        }
        .comment-form {
            display: none;
            z-index: 1001;
        }
        .discussion-image {
            max-width: 100%;
            height: auto;
            margin-top: 10px;
            z-index: 1001;
        }
        h3{
            color: #F2DC91;
            z-index: 1001;
            font-size: 28px;
            font-family:'Courier New', Courier, monospace;
            font-weight: bolder;
            text-decoration: underline;
        }
        p{
            font-family: 'Courier New', Courier, monospace;
            z-index: 1001;
            font-size: 20px;
            color: #fff;
        }
        h1{
            color: #F2DC91;
            z-index: 1001;
            font-size: 50px;
            font-family:'Courier New', Courier, monospace;
            text-decoration: underline;
            position: absolute;
            top: 20px;
        }
        h4{
            color: #F2DC91;
            z-index: 1001;
            font-size: 24px;
            font-family:'Courier New', Courier, monospace;
            font-weight: bolder;
            text-decoration: underline;
            text-decoration: wavy;
        }
    </style>
</head>
<body>
    <h1>View Post</h1>
    <button style="width: 180px; height:30px; font-family: Courier New, Courier, monospace; font-size: 20px; font-weight: bold; z-index: 1001; top: 35px; left: 30px; position: absolute;"><a href="content.php">Back</a></button>;
<?php
    include("conn.php");
    if (isset($_GET['id'])) {
        $discussion_id = intval($_GET['id']);

        $sql = "
            SELECT discussion.*, user.user_name AS author_username 
            FROM discussion 
            JOIN user ON discussion.user_id = user.user_id 
            WHERE discussion.discussion_id = $discussion_id
        ";
        $result = mysqli_query($con, $sql);

        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_array($result);

            $discussion_id = $row['discussion_id'];
            echo '<div class="box">';
            echo '<h3>' . $row['discussion_title'] . '</h3>';
            echo '<p>' . $row['discussion_text'] . '</p>';
            echo '<p>Created at: ' . $row['created_at'] . '</p>';
            echo '<p>Author: ' . $row['author_username'] . '</p>';

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
                        // Handle other media types if needed
                        echo '<p>Unsupported media type: ' . htmlspecialchars($media_type) . '</p>';
                    }
                }
            }

            echo '<button onclick="toggleComments(' . $discussion_id . ')" style="width: 170px; height:25px; font-family: Courier New, Courier, monospace; font-size: 13.5px; margin-top: 15px; font-weight: bold; z-index: 1001;">Comments</button>';
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
                                echo '<img src="' . htmlspecialchars($comment_media_URL) . '" class="media" style="width: 100px; height: 100px; border-radius: 10px; color: #fff; margin-top: 30px; margin-bottom: 30px; font-family: Courier New, Courier, monospace;" alt="Comment Image">';
                            } else {
                                echo '<p>Unsupported media type: ' . htmlspecialchars($comment_media_type) . '</p>';
                            }
                        }
                    }
                }
            } else {
                echo '<p>No comments yet.</p>';
            }

            if (isset($_SESSION['user_id'])) {
                echo '<button id="toggle-comment-form-' . $discussion_id . '" onclick="toggleCommentForm(' . $discussion_id . ')">Add Comment</button>';
                echo '<div id="comment-form-' . $discussion_id . '" class="comment-form">';
                echo '<form method="post" action="comment.php">';
                echo '<input type="hidden" name="discussion_id" value="' . $discussion_id . '">';
                echo '<textarea name="comment_text" required></textarea>';
                echo '<br>';
                echo '<input style="" type="submit" value="Add Comment">';

                echo '</form>';
                echo '</div>';
            } else {
                echo '<p>Please <a style="color: #fff; font-weight: bold;" href="login.html">login</a> to add a comment.</p>';
            }

            echo '</div>'; 
            echo '</div>'; 
        } else {
            echo 'Post not found.';
        }
    } else {
        echo 'No post ID provided.';
    }

    mysqli_close($con);
?>
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
        } 
    ?>
</section>
</body>
</html>


