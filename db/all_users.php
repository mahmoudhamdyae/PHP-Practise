<?php 
require "../profile/header.php";
?>

<h1>All Users</h1>


<?php 
require "connection.php";

$query = $connection->prepare("SELECT * FROM users");
$query->execute();

// $users = $query->fetchAll();
$customUser = $query->fetchAll(PDO::FETCH_CLASS, "CustomUser"); // fetch only if one row

echo "<pre>";
// var_dump($users);
var_dump($customUser);



class CustomUser {
    public $id;
    public $full_name;
    public $user_name;
    public $password;
    public $email;
    public $birth_date;
}



// foreach ($users as $user) {
//     echo "<br>";
//     var_dump($user);
//     echo "<br>";
//     echo($user['full_name']);
// }
?>



<?php
require "../profile/footer.php";
?>