<?php
require_once "database1.php"; // Ensure you have a connection to the database

// Initialize variables for form fields
$username = $password = $confirm_password = $firstname = $surname = $address = $address2 = $city = $telephone = $mobile = "";

// Initialize error messages
$user_error = $pass_error = $confirm_pass_error = $firstname_error = $surname_error = $address_error = $city_error = $mobile_error = "";
$success_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collecting input Data
    $username = trim($_POST['UserName']);
    $password = trim($_POST['Password']);
    $confirm_password = trim($_POST['ConfirmPassword']);
    $firstname = trim($_POST['FirstName']);
    $surname = trim($_POST['SurName']);
    $address = trim($_POST['AddressLine']);
    $address2 = trim($_POST['AddressLine1']);
    $city = trim($_POST['City']);
    $telephone = trim($_POST['Telephone']);
    $mobile = trim($_POST['Mobile']);

    // SQL and check
    $sql_check = "SELECT * From users Where UserName = '$username'";
    $result = $conn->query($sql_check); 

    $errors = false;
    

    // This will check the validation of the fiends aswell as display erros
    if ($result->num_rows > 0){
        $user_error2 = "Error: username already exists. Please choose another Username";
        $errors = true;
    }
    if (empty($username)) {
        $user_error = "Username is required.";
        $errors = true;
    }
    if (empty($password)) {
        $pass_error = "Password is required.";
        $errors = true;
    } elseif (strlen($password) < 6) {
        $pass_error = "Password must be at least 6 characters long.";
        $errors = true;
    }

    if (empty($confirm_password)) {
        $confirm_pass_error = "Please confirm your password.";
        $errors = true;
    } elseif ($password !== $confirm_password) {
        $confirm_pass_error = "Passwords do not match.";
        $errors = true;
    }

    if (empty($firstname)) {
        $firstname_error = "First name is required.";
        $errors = true;
    }
    if (empty($surname)) {
        $surname_error = "Surname is required.";
        $errors = true;
    }
    if (empty($address)) {
        $address_error = "Address Line 1 is required.";
        $errors = true;
    }
    if (empty($city)) {
        $city_error = "City is required.";
        $errors = true;
    }
    if (empty($mobile)) {
        $mobile_error = "Mobile number is required.";
        $errors = true;
    } elseif (!ctype_digit($mobile)) {
        $mobile_error = "Mobile number must contain only digits.";
        $errors = true;
    } elseif (strlen($mobile) != 10) {
        $mobile_error = "Mobile number must be exactly 10 characters long.";
        $errors = true;
    }

    if(!$errors){
        $sql = "INSERT INTO Users(UserName, Password, FirstName, SurName, AddressLine, AddressLine1, City, Telephone, Mobile)
            VALUES ('$username', '$password', '$firstname', '$surname', '$address', '$address2', '$city', '$telephone', '$mobile')";
    // If no errors, process the form and save data to the database
    
    
    
    if ($conn->query($sql) === TRUE) {
    echo "New record created successfully"; //cheks if query has been inserted
    header("location: index.php");
    exit();
    } else {
    echo '<div style="color: #ff0000; font-weight: bold;">Error: ' . $conn->error . '</div>';
    
    echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
}

?>







<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="Css/Register.css?v=<?php echo time(); ?>">


    <style>
        
    </style>
</head>
<body>
<nav>
        <ul>
            <li><a href="index.php" class="active">Home</a></li>
            <li><a href="login.php" class="active">Login</a></li>
            <li><a href=""></a></li>
            <li><a href=""></a></li>
            
                
            </li>
        </ul>
    </nav>
   


<div class="form-container">
    <header><h1 >Register</h1></header>
    <form method="post">
        <p>
            <label for="Username">Username:</label>
            <input type="text" id="" name="UserName" value="<?php echo htmlspecialchars($username); ?>" required>
            <span class="error"><?php echo $user_error; echo $user_error2;?></span>
        </p>
        <p>
            <label for="Password">Password:</label>
            <input type="password" id="" name="Password" value="<?php echo htmlspecialchars($password); ?>" required>
            <span class="error"><?php echo $pass_error; ?></span>
        </p>

        <p>
            <label for="confirm_password">Confirm Password:</label>
            <input type="password" id="" name="ConfirmPassword">
            <span class="error"><?php echo $confirm_pass_error; ?></span>
        </p>


        <p>
            <label for="Firstname">First Name:</label>
            <input type="text" id="Firstname" name="FirstName" value="<?php echo htmlspecialchars($firstname); ?>" required>
            <span class="error"><?php echo $firstname_error; ?></span>
        </p>
        <p>
            <label for="Surname">Surname:</label>
            <input type="text" id="" name="SurName" value="<?php echo htmlspecialchars($surname); ?>" required>
            <span class="error"><?php echo $surname_error; ?></span>
        </p>
        <p>
            <label for="address">Address Line 1:</label>
            <input type="text" id="" name="AddressLine" value="<?php echo htmlspecialchars($address); ?>" required>
            <span class="error"><?php echo $address_error; ?></span>
        </p>
        <p>
            <label for="address2">Address Line 2:</label>
            <input type="text" id="" name="AddressLine1" value="<?php echo htmlspecialchars($address2); ?>">
        </p>
        <p>
            <label for="city">City:</label>
            <input type="text" id="" name="City" value="<?php echo htmlspecialchars($city); ?>" required>
            <span class="error"><?php echo $city_error; ?></span>
        </p>
        <p>
            <label for="telephone">Telephone:</label>
            <input type="tel" id="" name="Telephone" value="<?php echo htmlspecialchars($telephone); ?>">
        </p>
        <p>
            <label for="mobile">Mobile:</label>
            <input type="text" id="" name="Mobile" value="<?php echo htmlspecialchars($mobile); ?>" required>
            <span class="error"><?php echo $mobile_error; ?></span>
        </p>
        <button type="submit">Add New</button>
        <?php if (!empty($errors)): ?>
         <?php endif; ?>
    </form>
</div>


<footer class="footer">
    <p>&copy; 2024 Ephraim. All Rights Reserved.</p>
</footer>
 </body>
 </html>: