<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Tickets</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Bagel+Fat+One&family=Quicksand:wght@300..700&display=swap">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Bagel Fat One', sans-serif;
        }

        body {
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
            background: #010326;
            color: #222;
            padding: 20px;
        }

        h2 {
            color: gold;
            font-size: 50px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
            z-index: 1001;
        }

        form {
            text-align: center;
            margin-bottom: 20px;
            z-index: 1001;
        }

        input[type="text"] {
            width: 300px;
            padding: 10px;
            border-radius: 4px;
            border: 1px solid #ccc;
            z-index: 1001;
        }

        input[type="submit"] {
            padding: 10px 20px;
            background-color: #F2A765;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: opacity 0.3s ease;
            margin-left: 10px;
            z-index: 1001;
        }

        input[type="submit"]:hover {
            opacity: 0.8;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            background-color: rgba(255, 255, 255, 0.7);
            z-index: 1001;
        }

        table, th, td {
            border: 1px solid #ddd;
            z-index: 1001;
        }

        th, td {
            padding: 12px;
            text-align: left;
            z-index: 1001;
        }

        th {
            background-color: #F2A765;
            color: #222;
            z-index: 1001;
        }

        tr:nth-child(even) {
            background-color: rgba(255, 255, 255, 0.1);
            z-index: 1001;
        }

        tr:hover {
            background-color: #f1f1f1;
            z-index: 1001;
        }

        .status-open {
            color: #28a745;
            font-weight: bold;
            z-index: 1001;
        }

        .status-in_progress {
            color: red;
            font-weight: bold;
            z-index: 1001;
        }

        .status-closed {
            color: green;
            font-weight: bold;
            z-index: 1001;
        }

        .action-buttons {
            display: flex;
            gap: 10px;
            z-index: 1001;
        }

        .action-buttons button {
            padding: 8px 12px;
            background-color: #ff9d4d;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            z-index: 1001;
        }

        .action-buttons button:hover {
            background-color: #ffbe8d;
        }

        .reply-form {
            display: flex;
            flex-direction: column;
            gap: 10px;
            background-color: rgba(2, 2, 2, 0.8);
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
            z-index: 1001;
        }

        .reply-form textarea {
            width: 100%;
            padding: 10px;
            border-radius: 4px;
            border: 1px solid #ccc;
            resize: vertical;
            z-index: 1001;
        }

        .reply-form input[type="submit"] {
            align-self: flex-end;
            padding: 10px 20px;
            background-color: #F2A765;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            z-index: 1001;
        }

        .reply-form input[type="submit"]:hover {
            background-color: #218838;
            z-index: 1001;
        }

        .home-button {
            padding: 10px 100px;
            background-color: #ff9d4d;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            text-align: center;
            margin-top: 20px;
            z-index: 1001;
        }

        .home-button:hover {
            background-color: #ffbe8d;
        }

section{
    position:absolute;
    width:100vw;
    height:200vh;
    display:flex;
    justify-content:center;
    align-items:center;
    gap:2px;
    flex-wrap:wrap;
    overflow: hidden;
    z-index:0;
}
section::before{
    content:'';
    position: absolute;
    width: 100%;
    height: 100%;
    background:linear-gradient(#010326, #965edb, #9249e1,rgb(127, 45, 165),rgb(138, 33, 173), #010326);
    animation: animate 10s linear infinite;
}
/*#010326, #82c471, #cc926c,#d4cf6e,#cd5f5f, #010326*/
@keyframes animate {
    0%{
        transform: translateY(-100%);
    }
    100%{
        transform: translateY(100%);
    }
}
section span{
    position:relative;
    display:circle;
    width: calc(6.25vw - 2px);
    height: calc(6.25vw - 2px);
    border-radius: 30%;
    background:#222567;
    z-index: 2;
    transition: 1.3s;
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
        } 
    ?>
    </section>
<h2>Submitted Tickets</h2>

<form method="GET" action="">
    <input type="text" name="search" placeholder="Search by description">
    <input type="submit" value="Search">
</form>

<table>
    <tr>
        <th>ID</th>
        <th>Subject</th>
        <th>Description</th>
        <th>Status</th>
        <th>Created At</th>
        <th>Updated At</th>
        <th>Action</th>
    </tr>

    <?php
    include("conn.php");

    $search = "";
    if (isset($_GET['search'])) {
        $search = $_GET['search'];
    }

    $sql = "SELECT * FROM tickets";
    if ($search != "") {
        $sql .= " WHERE description LIKE '%" . mysqli_real_escape_string($con, $search) . "%'";
    }
    $sql .= " ORDER BY created_at DESC";
    $result = mysqli_query($con, $sql);

    if ($result) {
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $statusClass = '';
                switch ($row["status"]) {
                    case 'open':
                        $statusClass = 'status-open';
                        break;
                    case 'in_progress':
                        $statusClass = 'status-in_progress';
                        break;
                    case 'closed':
                        $statusClass = 'status-closed';
                        break;
                }

                echo "<tr>";
                echo "<td>" . $row["ticket_id"] . "</td>";
                echo "<td>" . htmlspecialchars($row["subject"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["description"]) . "</td>";
                echo "<td class='" . $statusClass . "'>" . ucfirst($row["status"]) . "</td>";
                echo "<td>" . $row["created_at"] . "</td>";
                echo "<td>" . $row["updated_at"] . "</td>";
                echo "<td class='action-buttons'>";
                echo "<button onclick='showReplyForm(" . $row["ticket_id"] . ")'>Reply</button>";
                if ($row["status"] != 'closed') {
                    echo "<button onclick='closeTicket(" . $row["ticket_id"] . ")'>Close Ticket</button>";
                }
                echo "</td>";
                echo "</tr>";
                echo "<tr id='replyForm" . $row["ticket_id"] . "' style='display:none;'>";
                echo "<td colspan='7'>";
                echo "<form method='POST' action='submit_reply.php' class='reply-form'>";
                echo "<input type='hidden' name='ticket_id' value='" . $row["ticket_id"] . "'>";
                echo "<textarea name='reply' rows='4' cols='50' placeholder='Enter your reply here...'></textarea>";
                echo "<input type='submit' value='Submit Reply'>";
                echo "</form>";
                echo "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='7'>No tickets found</td></tr>";
        }
    } else {
        echo "<tr><td colspan='7'>Error: " . mysqli_error($con) . "</td></tr>";
    }

    mysqli_close($con);
    ?>

</table>

<button class="home-button" onclick="window.location.href='home.php'">Home</button>

<script>
function showReplyForm(ticketId) {
    var form = document.getElementById('replyForm' + ticketId);
    if (form.style.display == 'none') {
        form.style.display = 'table-row';
    } else {
        form.style.display = 'none';
    }
}

function closeTicket(ticketId) {
    if (confirm('Are you sure you want to close this ticket?')) {
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "close_ticket.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function () {
            if (xhr.readyState == 4 && xhr.status == 200) {
                alert('Ticket closed successfully.');
                location.reload();
            }
        };
        xhr.send("ticket_id=" + ticketId);
    }
}
</script>

</body>
</html>
