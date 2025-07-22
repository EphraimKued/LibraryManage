<?php
session_start();
require_once "database1.php";

$error = ""; //Initialize the error variable

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['UserName']);
    $password = trim($_POST['Password']);

    // Use prepared statements to avoid SQL injection
    $stmt = $conn->prepare("SELECT * FROM users WHERE UserName = ? AND Password = ?");
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        //exit();

        $_SESSION['username'] = $user['UserName']; 

        header("Location: search.php");

        exit();

        
    } else {
        $error = "Wrong Username or Password";
    }

    $stmt->close();
}
?> 

<!DOCTYPE html>
<html lang="en">
<link rel="stylesheet" href="Css/Login.css?v=<?php echo time(); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
<nav>
        <ul>
            <li><a href="index.php" class="active">Home</a></li>
            <li><a href=""></a></li>
            <li><a href=""></a></li>
            <li><a href=""></a></li>
            
                
            </li>
        </ul>
    </nav>
<div class="form-container">
    <header><h1>Login</h1></header>
    <form method="post">
        <p>
            <label for="Username">Username:</label>
            <input type="text" id="Username" name="UserName" value="" required>
        </p>
        <p>
            <label for="Password">Password:</label>
            <input type="password" id="Password" name="Password" value="" required>
        </p>
        <button type="submit">Login</button>
        <?php if (!empty($error)): ?>
            <span class="error"><?php echo htmlspecialchars($error); ?></span> <!--this will display  the error message-->
        <?php endif; ?>
    </form>
    <div class="center-link">
        <a href="Register.php">Not a Member?</a>
    </div>
</div>
<footer class="footer">
    <p>&copy; 2024 Ephraim. All Rights Reserved.</p>
</footer>
</body>

</html>
