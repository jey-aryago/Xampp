<?php
// Database connection using PDO
$dsn = 'mysql:host=localhost;dbname=grocery_list;charset=utf8';
$username = 'root'; // Update with your DB username
$password = ''; // Update with your DB password

try {
    $pdo = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);

    // Check if form is submitted
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $itemName = trim($_POST['item_name'] ?? '');

        if (!empty($itemName)) {
            // Insert into 'items' table
            $sql = "INSERT INTO items (name, is_active) VALUES (:name, 0)";
            $stmt = $pdo->prepare($sql);

            $stmt->bindParam(':name', $itemName, PDO::PARAM_STR);

            if ($stmt->execute()) {
                header("Location: ../index.php"); // Redirect back to the main page
                exit();
            } else {
                echo "Error: Unable to add the item.";
            }
        } else {
            echo "Error: Item name cannot be empty.";
        }
    }
} catch (PDOException $e) {
    // Log error and display a user-friendly message
    error_log("Database connection error: " . $e->getMessage());
    echo "A database error occurred. Please try again later.";
}
?>
