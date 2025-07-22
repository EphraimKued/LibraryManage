<?php
session_start();

require_once "database1.php"; 
if (!isset($_SESSION['username'])) {
    header("Location: login.php"); 
    exit;
}







$username = $_SESSION['username']; 


// Display any messages passed
if (isset($_GET['message'])) {
    echo "<p style='color: green;'>" . htmlspecialchars($_GET['message']) . "</p>";
}




// Initialize variables for book title, author, and category from POST Form

$book_title = isset($_POST['title']) ? $_POST['title'] : '';
$author = isset($_POST['author']) ? $_POST['author'] : '';
$category = isset($_POST['category']) ? $_POST['category'] : '';

// Initialize the result variable to null if no result is posted 
$result = null;




// Handle the search if form 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($book_title) || !empty($author) || !empty($category)) {


        // Create the SQL query for search
        $sql = "SELECT * FROM Books WHERE 1";

        // Array to hold conditions that must be met 
        $conditions = [];

        // Adding conditions
        if (!empty($book_title)) {
            $conditions[] = "BookTitile LIKE ?";
        }
        if (!empty($author)) {
            $conditions[] = "Author LIKE ?";
        }
        if (!empty($category)) {
            $conditions[] = "Category = ?";
        }




        // Append conditions to the SQL query if needed
        if (count($conditions) > 0) {
            $sql .= " AND " . implode(" AND ", $conditions);
        }

        // Prepare the SQL query
        $stmt = $conn->prepare($sql);

        // Bind parameters to the prepared statement based on the conditions
        $params = [];
        if (!empty($book_title)) {
            $params[] = "%$book_title%";  // Adding % for partial search
        }
        if (!empty($author)) {
            $params[] = "%$author%";
        }
        if (!empty($category)) {
            $params[] = "$category";
        }

        // Bind parameters dynamically based on the conditions
        if (count($params) > 0) {
            $stmt->bind_param(str_repeat('s', count($params)), ...$params);
        }



        // Execute the query
        $stmt->execute();
        $result = $stmt->get_result();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Css/search.css?v=<?php echo time(); ?>">
    <title>Library Web</title>
</head>
<body>

<nav>
    <ul>
        <li><a href="ReservedBooks.php" class="active">Reserved Books</a></li>
    </ul>
</nav>

<header><h1>Book Search</h1></header>
<p><a href="logout1.php">Logout</a></p>

<div class="search-container">
    <form action="search.php" method="POST">
        <input type="text" name="title" placeholder="Search for Book Title" value="<?php echo htmlspecialchars($book_title); ?>">
        <input type="text" name="author" placeholder="Search for Author" value="<?php echo htmlspecialchars($author); ?>">
        <select name="category" class="styled-dropdown">
            <option value="">Select Category</option>
            <?php
            // Mapping of categories to numbers
            $categoryMapping = [
                'Health' => 1,
                'Business' => 2,
                'Biography' => 3,
                'Technology' => 4,
                'Travel' => 5,
                'Self-Help' => 6,
                'Cookery' => 7,
                'Fiction' => 8,
            ];





            // Loop through the categories
            foreach ($categoryMapping as $categoryName => $categoryNumber) {
                // Check if the category is selected


                
                $selected = (isset($_POST['category']) && $_POST['category'] == $categoryNumber) ? "selected" : "";
               



                echo "<option value='" . $categoryNumber . "' $selected>" . $categoryNumber . " - " . htmlspecialchars($categoryName) . "</option>";
            }
            ?>
        </select>
        <button type="submit">
            <img src="https://cdn-icons-png.flaticon.com/512/622/622669.png" alt="Search Icon">
        </button>
    </form>
</div>





<div class="book-list"> 
    <?php 
    // Display results only if there are search results and the query was executed
    if ($result !== null) {
        if ($result->num_rows > 0) {
            // Loop through each row of the result and display book details
            while ($row = $result->fetch_assoc()) {
                $isReserved = $row['Reserves'];
                
                echo "<div class='book'>";
                echo "<p>ISBN: " . $row['ISBN'] . "</p>";  
                echo "<h3><strong>Book Title:</strong> " . $row['BookTitile'] . "</h3>"; 
                echo "<p><strong>Author:</strong> " . $row['Author'] . "</p>"; 
                echo "<p><strong>Editor:</strong> " . $row['Editor'] . "</p>"; 
                echo "<p><strong>Year:</strong> " . $row['Year'] . "</p>"; 
                echo "<p><strong>Category:</strong> " . $row['Category'] . "</p>"; 

                if ($isReserved == 'Y') {
                    echo "<p><strong>Status:</strong> Reserved</p>";
                    echo "<button disabled>Reserve</button>"; // Disable the button if reserved
                } else {
                    echo "<p><strong>Status:</strong> Available</p>";
                    echo "<form action='reserve.php' method='POST' style='display:inline;'>
                            <input type='hidden' name='isbn' value='" . $row['ISBN'] . "'>
                            <button type='submit'>Reserve</button>
                          </form>";
                }

                echo "</div>";
            }
        } else {
            echo "<p>No results found for your search.</p>";
        }
    }
    ?>
</div> 






<footer class="footer">
    <p>&copy; 2024 Ephraim. All Rights Reserved.</p>
</footer>

</body>
</html>
