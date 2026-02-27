<?php
// echo $POST['name'];
// echo '<br/>';
// echo $_POST['email'];

// Validation
$error_fields = array();
if (!isset($_POST['name']) && !empty($_POST['name'])) {
    $error_fields[] = "Name is required";
} else {
    $name = $_POST['name'];
}
if (!isset($_POST['email']) && filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL) === false) {
    $error_fields[] = "Email is required";
} else {
    $email = $_POST['email'];
}
if (!isset($_POST['password']) && !strlen($_POST['password'] > 5)) {
    $error_fields[] = "Password is required";
} else {
    $password = $_POST['password'];
}

if ($error_fields) {
    header("Location: form.php?error_fields=".implode(",", $error_fields));
    exit;
}




// Open the connection
$conn = mysqli_connect(
    "127.0.0.1", 
    "root",
     "root",
      "blog",
       8889
    );
if (!$conn) {
    echo "Connection failed: " . mysqli_connect_error();
    exit;
}

// Do the operation (SELECT, INSERT, ......)
$query = "SELECT * FROM `users`";
$result = mysqli_query($conn, $query);
while($row = mysqli_fetch_assoc($result)) {
    echo "Id: ".$row['id']."<br/>";
    echo "Name: ".$row['name']."<br/>";
    echo "Email: ".$row['email']."<br/>";
    echo str_repeat("-", 50)."<br/>";
}

// Close the connection
mysqli_free_result($result);
mysqli_close($conn);











/////// REQATCH THE LAST VIEO