<?php 
require "../profile/header.php";
?>

<h1>All Users</h1>


<?php 
require "connection.php";

$query = $connection->prepare("SELECT * FROM users");
$query->execute();

$users = $query->fetchAll();

var_dump($users);

foreach ($users as $user) {
    echo "<br>";
    var_dump($user);
    echo "<br>";
    echo($user['full_name']);
}
?>



<?php
require "../profile/footer.php";
?>