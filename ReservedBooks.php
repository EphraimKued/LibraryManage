<?php
session_start();
require_once "database1.php"; 

// Check if user is logged in, otherwise redirect
if (!isset($_SESSION['username'])) {
    header("Location: login.php"); 
    exit;
}

$username = $_SESSION['username']; 

$message = ""; // Message to display to the user

//Resevation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['isbn'])) {
    $isbn = $_POST['isbn'];

    // Check if the book is reserved by this user
    $query = "SELECT Reserves FROM Books WHERE ISBN = ? AND ReservedBy = ?";
    $stmt = $conn->prepare($query);
    if ($stmt) {
        $stmt->bind_param("ss", $isbn, $username); 
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Book is reserved by the user the user has the option to remove it
            $updateQuery = "UPDATE Books SET Reserves = 'N', ReservedBy = NULL, ReserveDate = NULL WHERE ISBN = ?";
            $updateStmt = $conn->prepare($updateQuery);
            if ($updateStmt) {
        
                $updateStmt->bind_param("s", $isbn);
                if ($updateStmt->execute()) {
                    $message = "Reservation removed successfully.";
                } else {
                    $message = "Error: Could not remove reservation.";
                }
            } else {
                $message = "Error: Failed to prepare update query.";
            }
        } else {
            $message = "You have not reserved this book.";
        }


    } else {
        $message = "Error: Failed to prepare query.";
    }
}

// Displaying the reserved book
$sql = "SELECT * FROM Books WHERE ReservedBy = ? AND Reserves = 'Y'";
$stmt = $conn->prepare($sql);
if ($stmt) {
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = false;
    $message = "Error: Failed to fetch reserved books.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Css/Reserved.css?v=<?php echo time(); ?>">
    <title>Books Reserved</title>
</head>
<body>
<nav>
    <ul>
        <li><a href="index.php" class="active">Home</a></li>
        <li><a href="search.php" class="active">Search Book</a></li>
    </ul>
</nav>

<header><h1>Your Reserved Books</h1></header>

<?php if (!empty($message)): ?>
    <p><?php echo htmlspecialchars($message); ?></p>
<?php endif; ?>

<div class="reserved-books">
    <?php if ($result && $result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="book">
                <p>Book Title:  <?php echo  $row['BookTitile']; ?></p>
                <p>ISBN: <?php echo htmlspecialchars($row['ISBN']); ?></p>
                <form method="POST">
                    <input type="hidden" name="isbn" value="<?php echo htmlspecialchars($row['ISBN']); ?>">
                    <button type="submit">Remove Reservation</button>
                </form>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No books reserved.</p>
    <?php endif; ?>
</div>

<footer class="footer">
    <p>&copy; 2024 Ephraim. All Rights Reserved.</p>
</footer>
</body>
</html>
