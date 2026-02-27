<?php

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