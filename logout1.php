<?php
//This will Destroy The users login kicking them out 
session_start();
session_destroy();
header("Location: login.php");
exit();
?>