<?php 
require "../profile/header.php";
?>

<h1>connection</h1>


<?php

try {
    $connection = new PDO("mysql:host=localhost;dbname=mytest", "root", "");
    echo "connected";
}
catch (PDOException $e) {
    echo $e->getMessage();
}

?>






<?php
class User {
    public $full_name;
    public $user_name;
    public $password;
    public $email;
    public $birth_date;

    public const DB_HOST = "localhost";
    public const DB_USER = "root";
    public const DB_PASS = "";
    public const DB_NAME = "mytest";

    public function __construct(
        $full_name,
        $user_name,
        $password,
        $email,
        $birth_date
    )
    {
        $this->full_name = $full_name;
        $this->user_name = $user_name;
        $this->password = $password;
        $this->email = $email;
        $this->birth_date = $birth_date;
    }

    public function sayHello()
    {
        echo "HELLO";
    }

    public static function sayHelloStatic()
    {
        echo "HELLO STATIC";
    }
}

$user = new User(
    "John Doe",
    "John",
    "123456",
    "[EMAIL_ADDRESS]",
    "2000-01-01"
);

var_dump($user);
var_dump($user->full_name);
$user->sayHello();
$user::sayHelloStatic();
User::sayHelloStatic();
echo User::DB_NAME;

?>







<?php
require "../profile/footer.php";
?>