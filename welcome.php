<?php
// start the session
session_start();
// check if the user is not logged in, then redirect user to login page
if(!isset($_SESSION['user_name']) && ($_SESSION['user_name']) !== true)
{
	header('location: login.php');
	exit;
}
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
<title>Welcome</title>
<meta charset="UTF-8">
<link rel = "stylesheet" type = "text/css" href = "style.css"> 
</head>
<body>

<div class="form-element">
<h1>Hello <strong> <?php echo $_SESSION["user_name"]; ?></strong>, welcome back.</h1>
<p> <a href="logout.php" role="button" aria-pressed="true">Log Out</a></p>
</div>

</body>
</html>
