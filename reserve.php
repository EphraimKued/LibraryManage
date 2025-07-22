<?php
session_start();
require_once "database1.php"; 

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    // If not logged in, redirect to login page
    header("Location: login.php");
    exit;
}

// Get the logged-in user's username
$username = $_SESSION['username'];

// Check if ISBN is provided through the post 
if (isset($_POST['isbn'])) {
    $isbn = $_POST['isbn'];

    // this will check if the boo is still available 
    $checkAvailabilityQuery = "SELECT Reserves FROM Books WHERE ISBN = ?";
    $stmt = $conn->prepare($checkAvailabilityQuery);
    $stmt->bind_param("s", $isbn);
    $stmt->execute();
    $result = $stmt->get_result();
    $book = $result->fetch_assoc();

    if ($book && $book['Reserves'] === 'N') {
        // Book is available, so reserve it
        $updateQuery = "UPDATE Books SET ReservedBy = ?, Reserves = 'Y', ReserveDate = NOW() WHERE ISBN = ?";
        $updateStmt = $conn->prepare($updateQuery);
        $updateStmt->bind_param("ss", $username, $isbn);
        $reservationQuery = "INSERT INTO reservation (ISBN, Username, ReserveDate) VALUES (?, ?, NOW())";
        if ($updateStmt->execute()) {
            // If update is successful, redirect and print messege
            header("Location: search.php?message=Book reserved successfully!");
        } else {
           
            echo "Error: Could not reserve the book.";
        }
    } else {
        
        echo "Sorry, the book is already reserved.";
    }
} else {
    // If ISBN is not set in POST request, redirect back to the search page
    header("Location: search.php");
}
?>
