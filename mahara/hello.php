<html>
	<body>
		<?php
			echo '<p>Now, It\'s ';
			echo date('H:i jS F Y');
			echo '</p>';
		?>

		<form method="post" action="process.php">
			<label for="name">Name</label>
			<input type="text" name="name" id="name"/> <br>
			<label for="email">Email</label>
			<input type="email" name="email" id="email"/> <br>
			<label for="password">Password</label>
			<input type="password" name="password" id="password"/> <br>
			<label for="gender">Gender</label>
			<input type="radio" name="gender" value="male"/> Male
			<input type="radio" name="gender" value="female"/> Female
			<input type="submit" name="submit" value="Register"/>
		</form>	
	</body>
</html>