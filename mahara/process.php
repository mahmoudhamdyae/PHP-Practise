<?php

    if (isset($_POST['email']) && !empty($_POST["email"])) {
        echo $_POST["email"];
    } else {
        echo "please enter your email";
    }

    var_dump($_POST);
    print_r($_REQUEST);
    echo $_POST['name'];