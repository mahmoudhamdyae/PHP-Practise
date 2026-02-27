<?php
// Check for errors
$error_arr = array();
if (isset($_GET['error_fields'])) {
    $error_arr = explode(",", $_GET['error_fields']);
}
?>
<html>
	<body>
		<form method="post" action="process_db.php">
			<label for="name">Name</label>
			<input type="text" name="name" id="name"/> <?php if (in_array("name", $error_arr)) echo"Please enter your name"; ?> <br>
			<label for="email">Email</label>
			<input type="email" name="email" id="email"/> <?php if (in_array("email", $error_arr)) echo"Please enter a valid email"; ?> <br>
			<label for="password">Password</label>
			<input type="password" name="password" id="password"/> <?php if (in_array("password", $error_arr)) echo"Please enter a password not less than 6 characters"; ?> <br>
			<label for="gender">Gender</label>
			<input type="radio" name="gender" value="male"/> Male
			<input type="radio" name="gender" value="female"/> Female
			<input type="submit" name="submit" value="Register"/>
		</form>	
	</body>
</html>