<?php
session_start();

// Check if user is logged in
if (isset($_SESSION['id']) && isset($_SESSION['fname'])) {
    // Include your database connection code here
    $host = 'localhost';
    $dbname = 'supermancyber';
    $username = 'root';
    $password = '';

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Get group ID from URL if available
        $groupId = $_GET['group_id'] ?? null;

        // Ensure group ID is not empty and is numeric
        if (!empty($groupId) && is_numeric($groupId)) {
            // Query to check if user is associated with the requested group
            $queryCheckUserGroup = "SELECT COUNT(*) AS count, gm.is_admin FROM group_members gm WHERE group_id = :group_id AND user_id = :user_id";
            $stmtCheckUserGroup = $pdo->prepare($queryCheckUserGroup);
            $stmtCheckUserGroup->bindParam(':group_id', $groupId);
            $stmtCheckUserGroup->bindParam(':user_id', $_SESSION['id']);
            $stmtCheckUserGroup->execute();
            $userData = $stmtCheckUserGroup->fetch(PDO::FETCH_ASSOC);

            // If user is not associated with the requested group, show 403 Forbidden error
            if ($userData['count'] == 0) {
                http_response_code(403);
                echo "<h1>403 Forbidden</h1>";
                echo "<p>You are not authorized to access this group.</p>";
                exit;
            }

            // Save group ID in session
            $_SESSION['group_id'] = $groupId;

            // Query to get group details and group members from the database
            $query = "SELECT g.group_name, u.fname, gm.is_admin 
                      FROM groups g 
                      INNER JOIN group_members gm ON g.id = gm.group_id 
                      INNER JOIN users u ON gm.user_id = u.id
                      WHERE g.id = :group_id";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':group_id', $groupId);
            $stmt->execute();
            $group = $stmt->fetch(PDO::FETCH_ASSOC);

            // Check if group is found in the database
            if (!$group) {
                // If group is not found, send 404 Not Found header and display message
                http_response_code(404);
                echo "<h1>404 Not Found</h1>";
                echo "<p>The requested group was not found.</p>";
                exit;
            }

            // Query to get list of group members along with their roles
            $queryMembers = "SELECT u.fname, gm.is_admin, gm.user_id
                             FROM group_members gm 
                             INNER JOIN users u ON gm.user_id = u.id
                             WHERE gm.group_id = :group_id";
            $stmtMembers = $pdo->prepare($queryMembers);
            $stmtMembers->bindParam(':group_id', $groupId);
            $stmtMembers->execute();
            $members = $stmtMembers->fetchAll(PDO::FETCH_ASSOC);

            // Query to fetch chat messages
            $queryMessages = "SELECT message, sender, timestamp FROM chat_messages WHERE group_id = :group_id ORDER BY timestamp ASC";
            $stmtMessages = $pdo->prepare($queryMessages);
            $stmtMessages->bindParam(':group_id', $groupId);
            $stmtMessages->execute();
            $messages = $stmtMessages->fetchAll(PDO::FETCH_ASSOC);
        } else {
            // If group ID is invalid, redirect to previous page or another page
            header("Location: index.php");
            exit;
        }

    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    // If user is not logged in, redirect to login page
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Group Chat - <?php echo $group['group_name']; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .chat-container {
            border: 1px solid #ccc;
            height: 400px;
            overflow-y: scroll;
            margin-bottom: 10px;
            padding: 10px;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="mb-0">Group Chat - <?php echo $group['group_name']; ?></h2>
            <!-- Show clear chat button if user is admin -->
            <?php
            if ($userData['is_admin'] == 1) {
                echo '<form action="php/clear-chat.php" method="post">';
                echo '<input type="hidden" name="group_id" value="' . $groupId . '">';
                echo '<button type="submit" class="btn btn-danger mb-3">Clear Chat</button>';
                echo '</form>';
            }
            ?>
        </div>
        <div class="chat-container">
            <?php
            // Fetch and display chat messages using PHP
            if (isset($messages)) {
                foreach ($messages as $message) {
                    echo '<p><strong>' . $message['sender'] . ':</strong> ' . $message['message'] . '</p>';
                }
            }
            ?>
        </div>
        <form id="chat-form">
            <div class="mb-3">
                <input type="text" id="chat-message" class="form-control" placeholder="Type your message...">
            </div>
            <button type="submit" class="btn btn-primary">Send</button>
        </form>
        <a href="groups.php" class="btn btn-secondary mt-3">Back to Groups</a>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Function to send chat message via Ajax
        function sendChatMessage(message) {
            $.ajax({
                url: 'php/send-message.php',
                method: 'POST',
                data: { message: message },
                success: function(response) {
                    window.location.reload();
                }
            });
        }

        // Function to display chat message
        function displayChatMessage(message, sender) {
            $('.chat-container').append('<p><strong>' + sender + ':</strong> ' + message + '</p>');
            $('.chat-container').scrollTop($('.chat-container')[0].scrollHeight);
        }

        // Submit form using Ajax
        $('#chat-form').submit(function(e) {
            e.preventDefault();
            var message = $('#chat-message').val();
            sendChatMessage(message);
            $('#chat-message').val('');
        });

        // Function to periodically fetch and display new chat messages
        function fetchChatMessages() {
            $.ajax({
                url: 'fetch-messages.php',
                method: 'GET',
                success: function(response) {
                    // Parse JSON response
                    var messages = JSON.parse(response);
                    // Display each message
                    messages.forEach(function(message) {
                        displayChatMessage(message.message, message.sender);
                    });
                }
            });
        }

        // Call fetchChatMessages function when page is loaded
        $(document).ready(function() {
            fetchChatMessages();
        });
    </script>
</body>
</html>

