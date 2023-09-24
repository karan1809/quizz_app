<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the score from the request
    $score = $_POST["score"];

    // Start the session (if not already started)
    session_start();

    if (isset($_SESSION['username'])) {
        $username = $_SESSION['username']; // Retrieve the username from the session

        // Connect to your database (replace these with your database credentials)
        $db_host = "localhost";
        $db_username = "root";
        $db_password = "";
        $db_name = "javaboi";

        $db_connection = new mysqli($db_host, $db_username, $db_password, $db_name);

        if ($db_connection->connect_error) {
            die("Connection failed: " . $db_connection->connect_error);
        }

        // Store the score in your database (use prepared statements to prevent SQL injection)
        $update_query = "UPDATE user_info SET points = ? WHERE username = ?";
        $stmt = $db_connection->prepare($update_query);
        
        if ($stmt) {
            $stmt->bind_param("is", $score, $username);
            if ($stmt->execute()) {
                echo '<script>
                setTimeout(function() {
                    window.location.href = "scoreboard.php";
                }, 3000); // 3000 milliseconds (3 seconds)
                </script>';
            } else {
                echo "Error storing score: " . $stmt->error;
            }
            $stmt->close();
        } else {
            echo "Error preparing statement: " . $db_connection->error;
        }

        // Close the database connection
        $db_connection->close();
    }
}
?>
